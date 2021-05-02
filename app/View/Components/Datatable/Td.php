<?php

namespace Uccello\Core\View\Components\Datatable;

use Illuminate\View\Component;
use Uccello\Core\Facades\Uccello;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Workspace;
use Uccello\Core\Support\Structure\Field;

class Td extends Component
{
    public $value;

    public $viewName;

    private $workspace;
    private $module;
    private $field;
    private $record;

    /**
     * UCreate a new component instance.
     *
     * @param \Uccello\Core\Models\Workspace $workspace
     * @param \Uccello\Core\Models\Module $module
     * @param \Uccello\Core\Support\Structure\Field $field
     * @param mixed $record
     */
    public function __construct(Workspace $workspace, Module $module, Field $field, $record)
    {
        $this->workspace = $workspace;
        $this->module = $module;
        $this->field = $field;
        $this->record = $record;

        $this->retrieveFieldValue();
        $this->retrieveUitypeViewName();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('uccello::components.datatable.td', [
            'workspace' => $this->workspace,
            'module' => $this->module
        ]);
    }

    private function retrieveFieldValue()
    {
        $this->value = $this->field->value($this->record);
    }

    private function retrieveUitypeViewName()
    {
        $module = $this->record->module;
        $this->viewName = Uccello::view(
            $module->package,
            $module,
            "uitype.list.{$this->field->type}",
            "uccello::modules.default.uitype.list.string"
        );
    }
}
