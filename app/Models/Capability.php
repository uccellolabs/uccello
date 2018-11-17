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

    protected function setTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
