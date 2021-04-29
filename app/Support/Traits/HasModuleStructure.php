<?php

namespace Uccello\Core\Support\Traits;

use Uccello\Core\Support\Structure\Block;
use Uccello\Core\Support\Structure\Field;
use Uccello\Core\Support\Structure\Tab;

trait HasModuleStructure
{
    /**
     * Retrieve module structure from data attribute.
     *
     * @return \stdClass|null
     */
    public function getStructureAttribute()
    {
        return $this->data->structure ?? null;
    }

    /**
     * Retrieve modules tabs from structure.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTabsAttribute()
    {
        $tabs = collect();

        if ($this->isStructureDefined()) {
            foreach ($this->structure->tabs ?? [] as $tab) {
                $tabs[] = new Tab($tab);
            }
        }

        return $tabs;
    }

    /**
     * Retrieve modules blocks from structure.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getBlocksAttribute()
    {
        $blocks = collect();

        if ($this->isStructureDefined()) {
            foreach ($this->structure->tabs ?? [] as $tab) {
                foreach ($tab->blocks ?? [] as $block) {
                    $blocks[] = new Block($block);
                }
            }
        }

        return $blocks;
    }

    /**
     * Retrieve modules fields from structure.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getFieldsAttribute()
    {
        $fields = collect();

        if ($this->isStructureDefined()) {
            foreach ($this->structure->tabs ?? [] as $tab) {
                foreach ($tab->blocks ?? [] as $block) {
                    foreach ($block->fields ?? [] as $field) {
                        $fields[] = new Field($field);
                    }
                }
            }
        }

        return $fields;
    }

    /**
     * Retrieve modules filters from structure.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getFiltersAttribute()
    {
        if ($this->isStructureDefined()) {
            $filters = $this->structure->filters;
        } else {
            $filters = [];
        }

        return collect($filters);
    }

    /**
     * Checks if a structure is defined
     *
     * @return boolean
     */
    private function isStructureDefined()
    {
        return $this->structure !== null;
    }
}
