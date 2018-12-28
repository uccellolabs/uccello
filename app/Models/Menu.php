<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Menu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menus';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'object',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'domain_id',
        'user_id',
        'type',
        'data',
    ];

    protected function initTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
