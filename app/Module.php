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

    public static function modules()
    {
        return static::all();
    }
}
