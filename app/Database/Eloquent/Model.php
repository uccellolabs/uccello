<?php

namespace Uccello\Core\Database\Eloquent;

use App\Models\UccelloModel;

class Model extends UccelloModel
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
        $this->tablePrefix = config('uccello.database.table_prefix');
    }

    protected function initTableName()
    {
        if ($this->table) {
            $this->table = $this->tablePrefix.$this->table;
        }
    }
}
