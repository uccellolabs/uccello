<?php

namespace Sardoj\Uccello;

use Sardoj\Uccello\Database\Eloquent\Model;

class Privilege extends Model
{
    protected $table = 'privileges';

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
