<?php

namespace Uccello\Core\Http\Controllers\Core;

use Schema;
use DB;
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
        if ($request->input('filter')) {
            $selectedFilterId = $request->input('filter');
            $selectedFilter = Filter::find($selectedFilterId);
        }

        if (empty($selectedFilter)) { // For example if the given filter does not exist
            $selectedFilter = $module->filters()->where('type', 'list')->first();
            $selectedFilterId = $selectedFilter->id;
        }

        // Get datatable columns
        $datatableColumns = Uccello::getDatatableColumns($module, $selectedFilterId);

        // Get filters
        $filters = Filter::where('module_id', $module->id)
            ->where('type', 'list')
            ->get();

        // Order by
        $filterOrderBy = (array) $selectedFilter->order_by;

        return $this->autoView(compact('datatableColumns', 'filters', 'selectedFilter', 'filterOrderBy'));
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
    public function processForContent(?Domain $domain, Module $module, Request $request)
    {
        $length = (int)$request->get('length') ?? env('UCCELLO_ITEMS_PER_PAGE', 15);

        $recordId = $request->get('id');
        $relatedListId = $request->get('relatedlist');
        $action = $request->get('action');

        // Pre-process
        $this->preProcess($domain, $module, $request, false);

        // Get model model class
        $modelClass = $module->model_class;

        // Check if the class exists
        if (!class_exists($modelClass)) {
            return false;
        }

        // Build query
        $query = $this->buildContentQuery();

        // Limit the number maximum of items per page
        $maxItemsPerPage = env('UCCELLO_MAX_ITEMS_PER_PAGE', 100);
        if ($length > $maxItemsPerPage) {
            $length = $maxItemsPerPage;
        }

        // If the query is for a related list, add conditions
        if ($relatedListId && $action !== 'select') {
            // Get related list
            $relatedList = Relatedlist::find($relatedListId);

            if ($relatedList && $relatedList->method) {
                // Related list method
                $method = $relatedList->method;

                // Update query
                $model = new $modelClass;
                $records = $model->$method($relatedList, $recordId, $query, $length);
            }
        } elseif ($relatedListId && $action === 'select') {
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

                // Make the quer
                $records = $query->whereNotIn($model->getKeyName(), $filteredRecordIds)->paginate($length);
            }
        } else {
            // Paginate results
            $records = $query->paginate($length);
        }

        $records->getCollection()->transform(function ($record) use ($domain, $module) {
            foreach ($module->fields as $field) {
                // If a special template exists, use it. Else use the generic template
                $uitypeViewName = sprintf('uitypes.list.%s', $field->uitype->name);
                $uitypeFallbackView = 'uccello::modules.default.uitypes.list.text';
                $uitypeViewToInclude = uccello()->view($field->uitype->package, $module, $uitypeViewName, $uitypeFallbackView);
                $record->{$field->name.'_html'} = view()->make($uitypeViewToInclude, compact('domain', 'module', 'record', 'field'))->render();
            }

            return $record;
        });

        return $records;
    }

    /**
     * Autocomplete a listing of the resources.
     * The result is formated differently if it is a classic query or one requested by datatable.
     * Filter on domain if domain_id column exists.
     * @param  \Uccello\Core\Models\Domain|null $domain
     * @param  \Uccello\Core\Models\Module $module
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function processForAutocomplete(?Domain $domain, Module $module, Request $request)
    {
        // If we don't use multi domains, find the first one
        if (!uccello()->useMultiDomains()) {
            $domain = Domain::first();
        }

        // Query
        $q = $request->get('q');

        // Model class
        $modelClass = $module->model_class;

        if ($q) {
            DB::statement("SET SESSION sql_mode = ''");
            $query = $modelClass::search($q);
        } else {
            $query = $modelClass::query();
        }

        return $query->paginate(10);
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
        $savePageLength = $request->input('save_page_length');

        // Optional data
        $data = [];
        if ($savePageLength) {
            $data["length"] = $request->input('page_length');
        }

        $filter = Filter::firstOrNew([
            'domain_id' => $domain->id,
            'module_id' => $module->id,
            'user_id' => auth()->id(),
            'name' => $request->input('name'),
            'type' => $request->input('type')
        ]);
        $filter->columns = $request->input('columns');
        $filter->conditions = $request->input('conditions') ?? null;
        $filter->order_by = $saveOrder ? $request->input('order') : null;
        $filter->is_default = $request->input('default');
        $filter->is_public = $request->input('public');
        $filter->data = !empty($data) ? $data : null;
        $filter->save();

        return $filter;
    }

    /**
     * Retrieve a filter by its id and delete it
     *
     * @param \Uccello\Core\Models\Domain|null $domain
     * @param \Uccello\Core\Models\Module $module
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function deleteFilter(?Domain $domain, Module $module, Request $request)
    {
        // Retrieve filter by id
        $filterId = $request->input('id');
        $filter = Filter::find($filterId);

        if ($filter) {
            if ($filter->readOnly) {
                // Response
                $success = false;
                $message = uctrans('error.filter.read_only', $module);
            } else {
                // Delete
                $filter->delete();

                // Response
                $success = true;
                $message = uctrans('success.filter.deleted', $module);
            }
        } else {
            // Response
            $success = false;
            $message = uctrans('error.filter.not_found', $module);
        }

        return [
            'success' => $success,
            'message' => $message
        ];
    }

    /**
     * Build query for retrieving content
     *
     * @return \Illuminate\Database\Eloquent\Builder;
     */
    protected function buildContentQuery()
    {
        $order = $this->request->get('order');
        $columns = $this->request->get('columns');

        $domain = $this->domain;
        $module = $this->module;

         // Get model model class
         $modelClass = $module->model_class;

         // Check if the class exists
         if (!class_exists($modelClass)) {
             return false;
         }

        // Filter on domain if column exists
        if (Schema::hasColumn((new $modelClass)->getTable(), 'domain_id')) {
            $query = $modelClass::where('domain_id', $domain->id);
        } else {
            $query = $modelClass::query();
        }

        // Search by column
        foreach ($columns as $fieldName => $column) {
            if (!empty($column[ "search" ])) {
                $searchValue = $column[ "search" ];
            } else {
                $searchValue = null;
            }

            // Get field by name and search by field column
            $field = $module->getField($fieldName);
            if (isset($searchValue) && !is_null($field)) {
                $query = $field->uitype->addConditionToSearchQuery($query, $field, $searchValue);
            }
        }

        // Order results
        if (!empty($order)) {
            foreach ($order as $fieldColumn => $value) {
                if (!is_null($field)) {
                    $query = $query->orderBy($fieldColumn, $value);
                }
            }
        }

        return $query;
    }
}
