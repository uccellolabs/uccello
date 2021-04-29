<?php

namespace Uccello\Core\Support\Structure;

class Block
{
    private $data;

    /**
     * Constructure
     *
     * @param \stdclass $data
     */
    public function __construct($data = null)
    {
        if ($data === null || is_object($data)) {
            $this->data = $data;
        } else {
            throw new \Exception('First argument must be an object');
        }
    }

    /**
     * Getter to retrieve an attribute from $data.
     *
     * @param string $attribute
     *
     * @return mixed
     */
    public function __get(string $attribute)
    {
        return optional($this->data)->{$attribute};
    }

    /**
     * Setter to update an attribute into $data.
     *
     * @param string $attribute
     * @param mixed $value
     */
    public function __set(string $attribute, $value)
    {
        $this->data[$attribute] = $value;
    }

    /**
     * Checks if block is visible in a view.
     * A block is visible if at least one field is visible.
     *
     * @param string $viewName
     *
     * @return boolean
     */
    public function isVisible(string $viewName)
    {
        $isVisible = false;

        if (optional($this->data)->fields) {
            foreach ($this->data->fields as $field) {
                if ($field->isVisible($viewName)) {
                    $isVisible = true;
                    break;
                }
            }
        }

        return $isVisible;
    }
}
