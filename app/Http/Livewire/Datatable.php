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

    protected $queryString = [
        'sortFieldName',
        'sortOrder' => ['except' => 'asc']
    ];

    public function render()
    {
        return view('uccello::livewire.datatable', [
            'fields' => $this->getFields(),
            'records' => $this->getPaginatedRecords()
        ]);
    }

    public function changeSortOrder($fieldName)
    {
        if ($this->sortFieldName === $fieldName) {
            $this->sortOrder = $this->sortOrder === 'asc' ? 'desc' : false;

            if ($this->sortOrder === false) {
                $this->reset('sortFieldName', 'sortOrder');
            }
        } else {
            $this->sortFieldName = $fieldName;
            $this->reset('sortOrder');
        }
    }

    private function getFields()
    {
        return $this->module->fields;
    }

    private function getPaginatedRecords()
    {
        $this->repository = new RecordRepository($this->module);
        $model = $this->repository->newInstance();

        if ($this->sortFieldName) {
            $model = $model->orderBy($this->sortFieldName, $this->sortOrder);
        }

        return $model->paginate(5);
    }
}
