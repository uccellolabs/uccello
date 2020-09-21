<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Connection extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'connections';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['datetime'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'datetime',
    ];

    protected function initTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
