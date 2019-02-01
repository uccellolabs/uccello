<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Preference extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'preferences';

    protected function initTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    /**
     * Returns record label from authenticated user data
     *
     * @return string
     */
    public function getRecordLabelAttribute() : string
    {
        return trim(auth()->user()->first_name.' '.auth()->user()->last_name);
    }
}
