<?php

namespace Uccello\Core\Http\Livewire;

use Livewire\Component;

class DatatableColumns extends Component
{
    public $workspace;
    public $module;
    public $visibleFields = [];

    // protected $listeners = [
    //     'visibleFieldsDetected' => 'onVisibleFieldsDetected'
    // ];

    public function mount()
    {
        $this->getVisibleFields();
    }

    // public function onVisibleFieldsDetected($visibleFields)
    // {
    //     $this->visibleFields = $visibleFields;
    // }

    /**
     * Render page.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        return view('uccello::livewire.datatable-columns', [
            'workspace' => $this->workspace,
            'module' => $this->module,
            'fields' => $this->getModuleFields(),
        ]);
    }

    /**
     * Toggle field visibility and emit event
     *
     * @param string $fieldName
     *
     * @return void
     */
    public function toggleFieldVisibility($fieldName)
    {
        $fieldNames = collect($this->visibleFields);

        if ($fieldNames->contains($fieldName)) {
            $this->visibleFields = $fieldNames->filter(function ($name) use ($fieldName) {
                return $name !== $fieldName;
            })->toArray();
        } else {
            $this->visibleFields[] = $fieldName;
        }

        $this->emit('visibleFieldsChanged', $this->visibleFields);
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
     * Detect fields to display by default
     *
     * @return array
     */
    private function getVisibleFields()
    {
        $this->visibleFields = [];

        if (empty($this->visibleFields)) {
            if (count($this->module->structure->filters) > 0) {
                $this->visibleFields = $this->module->structure->filters[0]->columns;
            }
        }

        return $this->visibleFields;
    }
}
