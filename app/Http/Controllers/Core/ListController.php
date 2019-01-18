<?php

namespace Uccello\Core\Http\Controllers\Core;

use Schema;
use Illuminate\Http\Request;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Facades\Uccello;
use Uccello\Core\Models\Relatedlist;
use Uccello\Core\Models\Filter;

class ListController extends Controller
{
    protected $viewName = 'list.main';

    /**
     * Check user permissions
     */
    protected function checkPermissions()
    {
        $this->middleware('uccello.permissions:retrieve');
    }

    /**
     * @inheritDoc
     */
    public function process(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        // Selected filter
        $selectedFilterId = $request->input('filter') ?? null;

        // Get datatable columns
        $datatableColumns = Uccello::getDatatableColumns($module, $selectedFilterId);

        // Get filters
        $filters = Filter::where('module_id', $module->id)
            ->where('type', 'list')
            ->get();

        return $this->autoView(compact('datatableColumns', 'filters', 'selectedFilterId'));
    }

    /**
     * Display a listing of the resources.
     * The result is formated differently if it is a classic query or one requested by datatable.
     * Filter on domain if domain_id column exists.
     * @param  \Uccello\Core\Models\Domain|null $domain
     * @param  \Uccello\Core\Models\Module $module
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function processForDatatable(?Domain $domain, Module $module, Request $request)
    {
        // If we don't use multi domains, find the first one
        if (!uccello()->useMultiDomains()) {
            $domain = Domain::first();
        }

        // Get data formated for Datatable
        $result = $this->getResultForDatatable($domain, $module, $request);

        return $result;
    }

    /**
     * Save list filter into database
     *
     * @param \Uccello\Core\Models\Domain|null $domain
     * @param \Uccello\Core\Models\Module $module
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function saveFilter(?Domain $domain, Module $module, Request $request)
    {
        $saveOrder = $request->input('save_order');
        $saveRowsNumber = $request->input('save_rows_number');

        $filter = Filter::firstOrNew([
            'domain_id' => $domain->id,
            'module_id' => $module->id,
            'user_id' => auth()->id(),
            'name' => $request->input('name'),
            'type' => $request->input('type')
        ]);
        $filter->columns = $request->input('columns');
        $filter->conditions = []; //TODO: Save conditions
        $filter->order_by = $saveOrder ? $request->input('order') : null;
        $filter->rows_number = $saveRowsNumber ? $request->input('rows_number') : null;
        $filter->is_default = $request->input('default');
        $filter->is_public = $request->input('public');
        $filter->save();

        return $filter;
    }

    /**
     * Get result formatted for Datatable
     *
     * @param  \Uccello\Core\Models\Domain $domain
     * @param  \Uccello\Core\Models\Module $module
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function getResultForDatatable(Domain $domain, Module $module, Request $request)
    {
        $draw = (int)$request->get('draw');
        $start = (int)$request->get('start');
        $length = (int)$request->get('length');
        $order = $request->get('order');
        $columns = $request->get('columns');
        $recordId = $request->get('id');
        $relatedListId = $request->get('relatedlist');
        $action = $request->get('action');

        // Get model model class
        $modelClass = $module->model_class;

        // If the class exists, make the query
        if (class_exists($modelClass)) {

            // Filter on domain if column exists
            if (Schema::hasColumn((new $modelClass)->getTable(), 'domain_id')) {
                // Count all results
                $total = $modelClass::where('domain_id', $domain->id)->count();

                // Paginate results
                $query = $modelClass::where('domain_id', $domain->id);
            } else {
                // Count all results
                $total = $modelClass::count();

                // Paginate results
                $query = $modelClass::whereRaw('1');
            }

            // Search by column
            foreach ($columns as $column) {
                $fieldName = $column[ "data" ];
                $searchValue = $column[ "search" ][ "value" ];

                // Get field by name and search by field column
                $field = $module->getField($fieldName);
                if (isset($searchValue) && !is_null($field)) {
                    $query = $field->uitype->addConditionToSearchQuery($query, $field, $searchValue);
                }
            }

            // Count filtered results
            $totalFiltered = $query->count();

            $initialQuery = $query;
            $query = $query->skip($start)->take($length);

            // Order results
            foreach ($order as $orderInfo) {
                $columnIndex = (int)$orderInfo[ "column" ];
                $column = $columns[ $columnIndex ];
                $fieldName = $column[ "data" ];

                // Get field by name and order by field column
                $field = $module->getField($fieldName);
                if (!is_null($field)) {
                    $query = $query->orderBy($field->column, $orderInfo[ "dir" ]);
                }
            }

            // If the query is for a related list, add conditions
            if ($relatedListId && $action !== 'select') {
                // Get related list
                $relatedList = Relatedlist::find($relatedListId);

                if ($relatedList && $relatedList->method) {
                    // Related list method
                    $method = $relatedList->method;
                    $countMethod = $method.'Count';

                    // Update query
                    $model = new $modelClass;
                    $records = $model->$method($relatedList, $recordId, $query, $start, $length);

                    // Count all results
                    $total = $model->$countMethod($relatedList, $recordId);
                    $totalFiltered = $total;
                }
            }
            elseif ($relatedListId && $action === 'select') {
                // Get related list
                $relatedList = Relatedlist::find($relatedListId);

                if ($relatedList && $relatedList->method) {
                    // Related list method
                    $method = $relatedList->method;
                    $recordIdsMethod = $method . 'RecordIds';

                    // Get related records ids
                    $model = new $modelClass;
                    $filteredRecordIds = $model->$recordIdsMethod($relatedList, $recordId);

                    // Add the record id itself to be filtered
                    if ($relatedList->related_module_id === $module->id && !empty($recordId) && !$filteredRecordIds->contains($recordId)) {
                        $filteredRecordIds[] = (int)$recordId;
                    }

                    // Make the query
                    $records = $query->whereNotIn($model->getKeyName(), $filteredRecordIds)->get();

                    // Count all results
                    $total = $initialQuery->whereNotIn($model->getKeyName(), $filteredRecordIds)->count();
                    $totalFiltered = $total;
                }
            }
            else {
                // Make the query
                $records = $query->get();
            }

            foreach ($records as &$record) {
                foreach ($module->fields as $field) {
                    $displayedValue = $field->uitype->getFormattedValueToDisplay($field, $record);

                    if ($displayedValue !== $record->{$field->column}) {
                        $record->{$field->name} = $displayedValue;
                    }
                }
            }

            $data = $records;

        } else {
            $data = [ ];
            $total = 0;
            $totalFiltered = 0;
        }

        return [
            "data" => $data->toArray(),
            "draw" => $draw,
            "recordsTotal" => $total,
            "recordsFiltered" => $totalFiltered,
        ];
    }
}
