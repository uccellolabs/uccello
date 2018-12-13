<?php

namespace Uccello\Core\Database\Migrations;

use Illuminate\Database\Migrations\Migration as DefaultMigration;
use Uccello\Core\Database\Migrations\Traits\TablePrefixTrait;

class Migration extends DefaultMigration
{
    use TablePrefixTrait;

    public function __construct()
    {
        $this->initTablePrefix();
    }
}