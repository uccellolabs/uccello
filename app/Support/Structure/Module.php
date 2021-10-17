<?php

namespace Uccello\Core\Support\Structure;

use Illuminate\Support\Str;
use Uccello\Core\Support\Structure\Field;
use Uccello\Core\Support\Structure\Filter;
use Uccello\Core\Support\Structure\RelatedList;
use Uccello\Core\Support\Structure\Tab;

class Module
{
    public $name;
    public $model;
    public $icon = 'extension';
    public $package = null;
    public $admin = false;
    public $required = false;

    public function name()
    {
        return property_exists($this, 'name') ? $this->name : Str::kebab(self::class);
    }

    public function isAdminModule()
    {
        return $this->admin;
    }

    public function isRequiredInWorkspace()
    {
        return $this->required;
    }

    public function tabs()
    {
        return [];
    }

    public function filters()
    {
        return [];
    }

    public function relatedLists()
    {
        return [];
    }
}
