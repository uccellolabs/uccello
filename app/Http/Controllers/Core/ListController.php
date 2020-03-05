<?php

namespace Uccello\Core\Http\Controllers\Core;

use Schema;
use Illuminate\Http\Request;
use Spatie\Searchable\Search;
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

        // Add search conditions from request
        $selectedFilter = $this->addSearchConditions($selectedFilter);

        // Get datatable columns
        $datatableColumns = Uccello::getDatatableColumns($module, $selectedFilterId);

        // Get filters
        $filters = Filter::where('module_id', $module->id)  // Module
            ->where('type', 'list')                         // Type (list)
            ->where(function ($query) use($domain) {        // Domain
                $query->whereNull('domain_id')
                    ->orWhere('domain_id', $domain->getKey());
            })
            ->where(function ($query) {                     // User
                $query->where('is_public', true)
                    ->orWhere(function ($query) {
                        $query->where('is_public', false)
                            ->where('user_id', '=', auth()->id());
                    })
                    ->orWhere(function ($query) {
                        $query->where('is_public', false)
                            ->whereNull('user_id');
                    });
            })
            ->orderBy('order')
            ->get();

        // Order
        $filterOrder = (array) $selectedFilter->order;

        // See descendants
        $seeDescendants = request()->session()->get('descendants');

        // Use soft deleting
        $usesSoftDeleting = $this->isModuleUsingSoftDeleting();

        // Check if we want to display trash data
        $displayTrash = $this->isDisplayingTrash();

        return $this->autoView(compact(
            'datatableColumns',
            'filters',
            'selectedFilter',
            'filterOrder',
            'seeDescendants',
            'usesSoftDeleting',
            'displayTrash'
        ));
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
        $relatedModule = null;

        if ($request->has('descendants') && $request->get('descendants') !== $request->session()->get('descendants')) {
            $request->session()->put('descendants', $request->get('descendants'));
        }

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
                $records = $model->$method($relatedList, $recordId, $query)->paginate($length);
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
                if ($relatedList->module_id === $relatedList->related_module_id && !empty($recordId) && !$filteredRecordIds->contains($recordId)) {
                    $filteredRecordIds[] = (int)$recordId;
                }

                if ($relatedList->module_id) {
                    $relatedModule = ucmodule($relatedList->module_id);
                }

                // Make the quer
                $records = $query->whereNotIn($model->getKeyName(), $filteredRecordIds)->paginate($length);
            }
        } else {
            // Paginate results
            $records = $query->paginate($length);
        }

        $records->getCollection()->transform(function ($record) use ($domain, $module, $relatedModule) {
            foreach ($module->fields as $field) {
                // If a special template exists, use it. Else use the generic template
                $uitype = uitype($field->uitype_id);
                $uitypeViewName = sprintf('uitypes.list.%s', $uitype->name);
                $uitypeFallbackView = 'uccello::modules.default.uitypes.list.text';
                $uitypeViewToInclude = uccello()->view($module->package, $module, $uitypeViewName, $uitypeFallbackView);
                $record->{$field->name.'_html'} = view()->make($uitypeViewToInclude, compact('domain', 'module', 'record', 'field'))->render();
            }

            // Add primary key name and value
            $record->__primaryKey = $record->getKey();
            $record->__primaryKeyName = $record->getKeyName();

            if ($relatedModule) {
                $moduleName = str_replace('-', '_', $relatedModule->name);
                if ($record->$moduleName) {
                    $record->__relatedEntityName = $record->$moduleName->recordLabel ?? null;
                }
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

        $results = collect();
        if (method_exists($modelClass, 'getSearchResult') && property_exists($modelClass, 'searchableColumns')) {
            $searchResults = new Search();
            $searchResults->registerModel($modelClass, (array) (new $modelClass)->searchableColumns);
            $results = $searchResults->search($q)->take(config('uccello.max_results.autocomplete', 10));
        }

        return $results;
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
        $filter->order = $saveOrder ? $request->input('order') : null;
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
     * Check if the model class link to the module is using soft deleting.
     *
     * @return boolean
     */
    protected function isModuleUsingSoftDeleting()
    {
        $modelClass = $this->module->model_class;
        $model = new $modelClass;

        return method_exists($model, 'getDeletedAtColumn');
    }

    /**
     * Check if we want to display trash data
     *
     * @return boolean
     */
    protected function isDisplayingTrash()
    {
        return $this->isModuleUsingSoftDeleting() && $this->request->get('filter') === 'trash';
    }

    /**
     * Add to a filter all search conditions from defined in request
     *
     * @param \Uccello\Core\Models\Filter $filter
     * @return \Uccello\Core\Models\Filter
     */
    protected function addSearchConditions($filter)
    {
        if ($this->request->has('search')) {
            $conditions = [];
            foreach ((array) $this->request->search as $fieldName => $value) {
                $conditions[$fieldName] = $value;
            }

            if ($conditions) {
                $filter->conditions = [
                    'search' => $conditions
                ];
            }
        }

        return $filter;
    }

    /**
     * Build query for retrieving content
     *
     * @return \Illuminate\Database\Eloquent\Builder;
     */
    protected function buildContentQuery()
    {
        $filter = [
            'order' => $this->request->get('order'),
            'columns' => $this->request->get('columns'),
        ];

        // Get model model class
        $modelClass = $this->module->model_class;

        // Check if the class exists
        if (!class_exists($modelClass) || !method_exists($modelClass, 'scopeInDomain')) {
            return false;
        }

        //Filter with additionnal rules (uitype entity for exemple)
        if ($this->request->has('additional_rules') && $this->request->get('additional_rules')) {
            $rules = json_decode($this->request->get('additional_rules'));

            foreach ($rules as $column => $rule) {
                $filter['columns'][$column]['search'] = $rule;
            }
        }

        // Filter on domain if column exists
        $query = $modelClass::inDomain($this->domain, $this->request->session()->get('descendants'))
                            ->filterBy($filter);

        // Display trash if filter is selected
        if ($this->isDisplayingTrash()) {
            $query = $query->onlyTrashed();
        }

        return $query;
    }
}
