<?php

namespace Uccello\Core\Support\Structure;

class Module
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
            // Set data
            $this->data = $data;

            // Convert structure
            $this->convertStructure();
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
        $this->data->{$attribute} = $value;

        if ($attribute === 'tabs') {
            $this->convertTabs();
        } elseif ($attribute === 'filters') {
            $this->convertFilters();
        } elseif ($attribute === 'relatedLists') {
            $this->convertRelatedLists();
        }
    }

    /**
     * Convert structure.
     * All structure object will be converted into specialized structure object.
     *
     * @return void
     */
    private function convertStructure()
    {
        // Tabs
        $this->convertTabs();

        // Filters
        $this->convertFilters();

        // Related lists
        $this->convertRelatedLists();
    }

    /**
     * Convert tabs. With cascade process,
     * all blocks and fields will be converted too
     *
     * @return void
     */
    private function convertTabs()
    {
        if (!empty($this->data->tabs)) {
            $tabs = collect();
            foreach ($this->data->tabs as $tab) {
                $tabs[] = new Tab($tab);
            }
            $this->data->tabs = $tabs;
        }
    }

    /**
     * Convert filters.
     *
     * @return void
     */
    private function convertFilters()
    {
        if (!empty($this->data->filters)) {
            $filters = collect();
            foreach ($this->data->filters as $filter) {
                $filters[] = new Filter($filter);
            }
            $this->data->filters = $filters;
        }
    }

    /**
     * Convert related lists.
     *
     * @return void
     */
    private function convertRelatedLists()
    {
        if (!empty($this->data->relatedLists)) {
            $relatedLists = collect();
            foreach ($this->data->relatedLists as $relatedList) {
                $relatedLists[] = new Relatedlist($relatedList);
            }
            $this->data->relatedLists = $relatedLists;
        }
    }
}
