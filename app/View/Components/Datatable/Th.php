<?php

namespace Uccello\Core\View\Components\Datatable;

use Illuminate\View\Component;
use Uccello\Core\Support\Structure\Field;

class Th extends Component
{
    public $fieldName;
    public $sortFieldName;
    public $sortOrder;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Field $field, $sortFieldName = null, $sortOrder = null)
    {
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
        return view('uccello::components.datatable.th');
    }
}
