<?php

namespace Uccello\Core\Support\Structure;

use Uccello\Core\Facades\Uccello;
use Uccello\Core\Models\Uitype;

class Field
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
        } else {
            throw new \Exception('First argument must be an object or an array');
        }
    }

    /**
     * Getter to retrieve an attribute from $data.
     * Checks if a method with the same attribute's name exists,
     * else checks if the attribute exists.
     *
     * @param string $attribute
     *
     * @return mixed
     */
    public function __get(string $attribute)
    {
        if (method_exists($this, $attribute)) {
            return $this->{$attribute}();
        } else {
            return optional($this->data)->{$attribute};
        }
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
    }

    /**
     * Checks if field is visible in a view.
     *
     * @param string $viewName
     *
     * @return boolean
     */
    public function isVisible(string $viewName)
    {
        $isVisible = false;

        if ($viewName === 'everywhere') {
            $isVisible = $this->isVisibleEverywhere();
        } elseif ($viewName === 'create') {
            $isVisible = $this->isVisibleInCreateView();
        } elseif ($viewName === 'edit') {
            $isVisible = $this->isVisibleInEditView();
        } elseif ($viewName === 'detail') {
            $isVisible = $this->isVisibleInDetailView();
        } elseif ($viewName === 'list') {
            $isVisible = $this->isVisibleInListView();
        } else {
            throw "View name $viewName is invalid";
        }

        return $isVisible;
    }

    /**
     * Check if field is visible everywhere
     *
     * @return boolean
     */
    public function isVisibleEverywhere()
    {
        return optional($this->data)->visible === true
            ||
            (
                $this->isVisibleInCreateView()
                &&
                $this->isVisibleInEditView()
                &&
                $this->isVisibleInDetailView()
                &&
                $this->isVisibleInListView()
            );
    }

    /**
     * Check if field is visible in create view.
     *
     * @return boolean
     */
    public function isVisibleInCreateView()
    {
        return optional($this->data)->visible === true
            ||
            (
                is_object($this->data->visible)
                &&
                optional($this->data->visible)->create === true
            );
    }

    /**
     * Check if field is visible in edit view.
     *
     * @return boolean
     */
    public function isVisibleInEditView()
    {
        return optional($this->data)->visible === true
            ||
            (
                is_object($this->data->visible)
                &&
                optional($this->data->visible)->edit === true
            );
    }

    /**
     * Check if field is visible in detail view.
     *
     * @return boolean
     */
    public function isVisibleInDetailView()
    {
        return optional($this->data)->visible === true
            ||
            (
                is_object($this->data->visible)
                &&
                optional($this->data->visible)->detail === true
            );
    }

    /**
     * Check if field is visible in list view.
     *
     * @return boolean
     */
    public function isVisibleInListView()
    {
        return optional($this->data)->visible === true
            ||
            (
                is_object($this->data->visible)
                &&
                optional($this->data->visible)->list === true
            );
    }

    /**
     * Return column name according to the following priorities:
     * 1 - Column name defined in the field structure
     * 2 - Field type default column name
     *
     * @return string
     */
    public function column()
    {
        if (!empty($this->data->column)) {
            $column = $this->data->column;
        } else {
            $column = $this->type === 'entity' ? $this->name.'_id' : $this->name;
        }

        return $column;
    }

    /**
     * Return uitype
     *
     * @return \Uccello\Core\Models\Uitype|null
     */
    public function uitype()
    {
        return Uccello::uitype($this->type);
    }

    /**
     * Return formatted value
     *
     * @param mixed $record
     *
     * @return string
     */
    public function value($record)
    {
        $uitypeInstance = $this->getUitypeInstance();

        return $uitypeInstance ? $uitypeInstance->value($this, $record) : '';
    }

    /**
     * Return raw value
     *
     * @param mixed $record
     *
     * @return mixed|null
     */
    public function rawValue($record)
    {
        $uitypeInstance = $this->getUitypeInstance();

        return $uitypeInstance ? $uitypeInstance->rawValue($this, $record) : null;
    }

    /**
     * Makes an instance of Uitype
     *
     * @return mixed|null
     */
    private function getUitypeInstance()
    {
        $instance = null;

        $uitype = $this->uitype();
        if (!empty($uitype)) {
            $uitypeClass = $uitype->class;
            $instance = new $uitypeClass;
        }

        return $instance;
    }
}
