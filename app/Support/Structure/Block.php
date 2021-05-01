<?php

namespace Uccello\Core\Support\Structure;

class Block
{
    private $data;

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
            $this->data = $data;

            // Convert structure
            $this->convertStructure();
        } else {
            throw new \Exception('First argument must be an object or an array');
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
        $this->data->{$attribute} = $value;

        if ($attribute === 'fields') {
            $this->convertFields();
        }
    }

    /**
     * Adds a new field.
     * Initialize fields collection if necessary.
     * Convert stdClass to Field if necessary.
     *
     * @param \stdClass|array|\Uccello\Core\Support\Structure\Field $field
     * @param boolean $returnField
     *
     * @return \Uccello\Core\Support\Structure\Field
     */
    public function addField($field, $returnField = false)
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
     * Convert structure.
     * All structure object will be converted into specialized structure object.
     *
     * @return void
     */
    private function convertStructure()
    {
        // Fields
        $this->convertFields();
    }

    /**
     * Convert fields.
     *
     * @return void
     */
    private function convertFields()
    {
        if (!empty($this->data->fields)) {
            foreach ($this->data->fields as &$field) {
                if ($field instanceof Field === false) {
                    $field = new Field($field);
                }
            }
        }
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
