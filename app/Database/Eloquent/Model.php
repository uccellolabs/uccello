<?php

namespace Sardoj\Uccello\Database\Eloquent;

use Illuminate\Database\Eloquent\Model as DefaultModel;

class Model extends DefaultModel
{
    protected $tablePrefix;

    public function __construct()
    {
        parent::__construct();
        
        $this->setTablePrefix();

        $this->setTableName();
    }

    protected function setTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    protected function setTableName()
    {
        if($this->table)
        {
            $this->table = $this->tablePrefix . $this->table;
        }
    }
}