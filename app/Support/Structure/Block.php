<?php

namespace Uccello\Core\Support\Structure;

class Block
{
    public $name;
    public $icon;
    public $closed = false;
    public $info;
    public $fields = [];

    /**
     * Constructure
     *
     * @param \stdClass|array|null $data
     */
    public function __construct($data = null)
    {
        if ($data === null || is_object($data) || is_array($data)) {
            // Convert to stdClass if necessary
            if (is_array($data)) {
                $data = json_decode(json_encode($data));
            }

            // Set data
            foreach ($data as $key => $val) {
                $this->{$key} = $val;
            }
        } else {
            throw new \Exception('First argument must be an object or an array');
        }
    }

    /**
     * Adds a new field.
     * Initialize fields collection if necessary.
     * Convert stdClass to Field if necessary.
     *
     * @param \stdClass|array|\Uccello\Core\Support\Structure\Field $field
     *
     * @return \Uccello\Core\Support\Structure\Field
     */
    public function addField($field)
    {
        // Initialize fields
        if (empty($this->fields)) {
            $this->fields = collect();
        }

        // Convert field if necessary
        if ($field instanceof Field === false) {
            $field = new Field($field);
        }

        // Add field
        $this->fields[] = $field;

        return $field;
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

        if ($this->fields) {
            foreach ($this->fields as $field) {
                if ($field->isVisible($viewName)) {
                    $isVisible = true;
                    break;
                }
            }
        }

        return $isVisible;
    }
}
