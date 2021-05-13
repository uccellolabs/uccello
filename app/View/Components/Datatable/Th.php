<?php

namespace Uccello\Core\View\Components\Datatable;

use Illuminate\View\Component;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Workspace;
use Uccello\Core\Support\Structure\Field;

class Th extends Component
{
    public $fieldName;
    public $sortFieldName;
    public $sortOrder;

    private $workspace;
    private $module;
    private $field;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Workspace $workspace, Module $module, Field $field, $sortFieldName = null, $sortOrder = null)
    {
        $this->workspace = $workspace;
        $this->module = $module;
        $this->field = $field;
        $this->fieldName = $field->name;
        $this->sortFieldName = $sortFieldName;
        $this->sortOrder = $sortOrder;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('uccello::components.datatable.th', [
            'workspace' => $this->workspace,
            'module' => $this->module,
            'field' => $this->field
        ]);
    }
}
