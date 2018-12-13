<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Filter extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'filters';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'columns' => 'object',
        'conditions' => 'object',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'module_id',
        'domain_id',
        'user_id',
        'name',
        'type',
        'columns',
        'conditions',
        'order_by',
        'is_default',
        'is_public',
    ];

    protected function initTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
