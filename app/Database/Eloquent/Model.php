<?php

namespace Uccello\Core\Database\Eloquent;

use Illuminate\Database\Eloquent\Model as DefaultModel;

class Model extends DefaultModel
{
    protected $tablePrefix;

    public function __construct(array $attributes = [ ])
    {
        parent::__construct($attributes);

        // Init table prefix
        $this->initTablePrefix();

        // Init table name
        $this->initTableName();
    }

    public function getTablePrefix()
    {
        return $this->tablePrefix;
    }

    protected function initTablePrefix()
    {
        $this->tablePrefix = '';
    }

    protected function initTableName()
    {
        if ($this->table)
        {
            $this->table = $this->tablePrefix.$this->table;
        }
    }
}