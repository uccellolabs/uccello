<?php

namespace Uccello\Core\Database\Eloquent;

use Illuminate\Database\Eloquent\Model as DefaultModel;
use Uccello\Core\Support\Traits\RelatedlistTrait;

class Model extends DefaultModel
{
    use RelatedlistTrait;

    protected $tablePrefix;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Set table prefix
        $this->setTablePrefix();

        // Set table name
        $this->setTableName();
    }

    protected function setTablePrefix()
    {
        $this->tablePrefix = '';
    }

    protected function setTableName()
    {
        if($this->table)
        {
            $this->table = $this->tablePrefix . $this->table;
        }
    }
}