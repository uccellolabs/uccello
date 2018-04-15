<?php

namespace Sardoj\Uccello;

use Sardoj\Uccello\Database\Eloquent\Model;

class Domain extends Model
{
    protected $table = 'domains';

    public function parent()
    {
        return $this->belongsTo(self::class);
    }

    public function children()
    {
        return $this->hasMany(self::class);
    }

    public function privileges()
    {
        return $this->hasMany(Privilege::class);
    }

    public function roles()
    {
        return $this->hasMany(Roles::class);
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, $this->tablePrefix . 'domains_modules');
    }
}
