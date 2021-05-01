<?php

namespace Uccello\Core\Support\Traits;

use Uccello\Core\Support\Structure;
use Uccello\Core\Support\Structure\ModuleStructure;

trait HasModuleStructure
{
    /**
     * Retrieve module structure from data attribute.
     *
     * @return \stdClass|null
     */
    public function getStructureAttribute()
    {
        return new ModuleStructure($this->data->structure) ?? null;
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
                $tabs[] = $tab;
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
                    $blocks[] = $block;
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
                        $fields[] = $field;
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
        $filters = collect();

        if ($this->isStructureDefined()) {
            $filters = $this->structure->filters;
        }

        return $filters;
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
