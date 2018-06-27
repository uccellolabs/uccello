<?php

namespace Uccello\Core\Database\Migrations\Traits;

trait TablePrefixTrait
{
    protected $tablePrefix;

    protected function setTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');

        return $this->tablePrefix;
    }

    protected function getTablePrefix()
    {
        return $this->tablePrefix ?? $this->setTablePrefix();
    }
}