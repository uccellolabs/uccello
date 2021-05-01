<?php

namespace Uccello\Core\View\Components\Datatable;

use Illuminate\View\Component;
use Uccello\Core\Support\Structure\Field;

class Td extends Component
{
    public $value;

    /**
     * UCreate a new component instance.
     *
     * @param \Uccello\Core\Support\Structure\Field $field
     * @param mixed $record
     */
    public function __construct(Field $field, $record)
    {
        $this->value = $field->value($record);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('uccello::components.datatable.td');
    }
}
