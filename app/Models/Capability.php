<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Capability extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'capabilities';

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
