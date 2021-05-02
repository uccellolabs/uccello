<?php

namespace Uccello\Core\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Uccello\Core\Repositories\RecordRepository;

class Datatable extends Component
{
    use WithPagination;

    public $workspace;
    public $module;

    public $sortFieldName;
    public $sortOrder = 'asc';

    public $length;

    protected $queryString = [
        'sortFieldName',
        'sortOrder' => ['except' => 'asc'],
        'length'
    ];

    /**
     * Mount component.
     *
     * @return void
     */
    public function mount()
    {
        $this->length = config('uccello.datatable.length');
    }

    /**
     * Render page.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        $this->queryString['length'] = ['except' => config('uccello.datatable.length')];

        return view('uccello::livewire.datatable', [
            'workspace' => $this->workspace,
            'module' => $this->module,
            'fields' => $this->getModuleFields(),
            'records' => $this->getPaginatedRecords()
        ]);
    }

    /**
     * Change sort order.
     *
     * @param string $fieldName
     *
     * @return void
     */
    public function changeSortOrder($fieldName)
    {
        if ($this->sortFieldName === $fieldName) { // Same field
            $this->sortOrder = $this->sortOrder === 'asc' ? 'desc' : false;

            if ($this->sortOrder === false) {
                $this->reset('sortFieldName', 'sortOrder');
            }
        } else { // Other field
            $this->sortFieldName = $fieldName;
            $this->reset('sortOrder');
        }
    }

    /**
     * Retrieve all module fields.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getModuleFields()
    {
        return $this->module->fields;
    }

    /**
     * Retrives module records and paginate the results.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function getPaginatedRecords()
    {
        // Initialize
        $model = $this->getModuleModel();

        // Sort if necessary
        $model = $this->addOrderBy($model);

        // Paginate
        return $model->paginate($this->length);
    }

    /**
     * Make a new instance of module model.
     *
     * @return mixed
     */
    private function getModuleModel()
    {
        $repository = new RecordRepository($this->module);

        return $repository->newInstance();
    }

    /**
     * Add order by clause if a field is selected.
     *
     * @param mixed $model
     *
     * @return mixed
     */
    private function addOrderBy($model)
    {
        if ($this->sortFieldName) {
            $field = $this->getFieldByName($this->sortFieldName);

            if ($field) {
                $model = $model->orderBy($field->column, $this->sortOrder);
            }
        }

        return $model;
    }

    /**
     * Search field by name.
     *
     * @param string $name
     *
     * @return \Uccello\Core\Support\Structure\Field|null
     */
    private function getFieldByName($name)
    {
        $foundField = null;

        foreach ($this->module->fields as $field) {
            if ($field->name === $name) {
                $foundField = $field;
                break;
            }
        }

        return $foundField;
    }
}
