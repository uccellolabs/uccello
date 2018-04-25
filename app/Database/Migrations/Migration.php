<?php

namespace Sardoj\Uccello\Database\Migrations;

use Illuminate\Database\Migrations\Migration as DefaultMigration;
use Sardoj\Uccello\Database\Migrations\Traits\TablePrefixTrait;

class Migration extends DefaultMigration
{
    use TablePrefixTrait;

    public function __construct()
    {        
        $this->setTablePrefix();
    }
}