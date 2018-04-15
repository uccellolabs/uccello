<?php

namespace Sardoj\Uccello\Database\Migrations;

use Illuminate\Database\Migrations\Migration as DefaultMigration;

class Migration extends DefaultMigration
{
    protected $tablePrefix;

    public function __construct()
    {        
        $this->setTablePrefix();
    }

    protected function setTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }
}