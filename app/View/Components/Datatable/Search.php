<?php

namespace Uccello\Core\View\Components\Datatable;

use Illuminate\View\Component;
use Uccello\Core\Facades\Uccello;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Workspace;
use Uccello\Core\Support\Structure\Field;

class Search extends Component
{
    public $value;

    public $viewName;

    private $workspace;
    private $module;
    private $field;

    /**
     * UCreate a new component instance.
     *
     * @param \Uccello\Core\Models\Workspace $workspace
     * @param \Uccello\Core\Models\Module $module
     * @param \Uccello\Core\Support\Structure\Field $field
     */
    public function __construct(Workspace $workspace, Module $module, Field $field)
    {
        $this->workspace = $workspace;
        $this->module = $module;
        $this->field = $field;

        $this->retrieveSearchValue();
        $this->retrieveUitypeViewName();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('uccello::components.datatable.search', [
            'workspace' => $this->workspace,
            'module' => $this->module
        ]);
    }

    private function retrieveSearchValue()
    {
        $this->value = '';
    }

    private function retrieveUitypeViewName()
    {
        $module = $this->module;

        $this->viewName = Uccello::view(
            $module->package,
            $module,
            "uitype.search.{$this->field->type}",
            "uccello::modules.default.uitype.search.string"
        );
    }
}
