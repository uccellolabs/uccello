<?php

namespace Sardoj\Uccello;

use Sardoj\Uccello\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'modules';

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function domains()
    {
        return $this->belongsToMany(Domain::class, $this->tablePrefix . 'domains_modules');
    }

    public function tabs()
    {
        return $this->hasMany(Tab::class)->orderBy('sequence');
    }

    public function filters()
    {
        return $this->hasMany(Filter::class);
    }

    public function getFieldsAttribute()
    {
        $fields = [];

        foreach($this->tabs as $tab)
        {
            foreach($tab->blocks as $block)
            {
                foreach($block->fields as $field)
                {
                    $fields[] = $field;
                }
            }
        }

        return $fields;
    }
}
