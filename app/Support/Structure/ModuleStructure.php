<?php

namespace Uccello\Core\Support\Structure;

class ModuleStructure
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

        if ($attribute === 'tabs') {
            $this->convertTabs();
        } elseif ($attribute === 'filters') {
            $this->convertFilters();
        } elseif ($attribute === 'relatedLists') {
            $this->convertRelatedLists();
        }
    }

    /**
     * Adds a new tab.
     * Initialize tabs collection if necessary.
     * Convert stdClass to Tab if necessary.
     *
     * @param \stdClass|array|\Uccello\Core\Support\Structure\Tab $tab
     *
     * @return \Uccello\Core\Support\Structure\Tab
     */
    public function addTab($tab)
    {
        // Initialize tabs
        if (empty($this->tabs)) {
            $this->tabs = collect();
        }

        // Convert if necessary
        if ($tab instanceof Tab === false) {
            $tab = new Tab($tab);
        }

        // Add tab
        $this->tabs[] = $tab;

        return $tab;
    }

    /**
     * Adds a new fielter.
     * Initialize filters collection if necessary.
     * Convert stdClass to Filter if necessary.
     *
     * @param \stdClass|array|\Uccello\Core\Support\Structure\Filter $filter
     *
     * @return \Uccello\Core\Support\Structure\Filter
     */
    public function addFilter($filter)
    {
        // Initialize filters
        if (empty($this->filters)) {
            $this->filters = collect();
        }

        // Convert if necessary
        if ($filter instanceof Filter === false) {
            $filter = new Filter($filter);
        }

        // Add filter
        $this->filters[] = $filter;

        return $filter;
    }

    /**
     * Adds a new related list.
     * Initialize relatedLists collection if necessary.
     * Convert stdClass to RelatedList if necessary.
     *
     * @param \stdClass|array|\Uccello\Core\Support\Structure\RelatedList $relatedList
     *
     * @return \Uccello\Core\Support\Structure\RelatedList
     */
    public function addRelatedList($relatedList)
    {
        // Initialize relatedLists
        if (empty($this->relatedLists)) {
            $this->relatedLists = collect();
        }

        // Convert if necessary
        if ($relatedList instanceof RelatedList === false) {
            $relatedList = new RelatedList($relatedList);
        }

        // Add related list
        $this->relatedLists[] = $relatedList;

        return $relatedList;
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
            foreach ($this->data->tabs as &$tab) {
                if ($tab instanceof Field === false) {
                    $tab = new Tab($tab);
                }
            }
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
            foreach ($this->data->filters as &$filter) {
                if ($filter instanceof Filter === false) {
                    $filter = new Filter($filter);
                }
            }
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
            foreach ($this->data->relatedLists as &$relatedList) {
                if ($relatedList instanceof RelatedList === false) {
                    $relatedList = new RelatedList($relatedList);
                }
            }
        }
    }
}
