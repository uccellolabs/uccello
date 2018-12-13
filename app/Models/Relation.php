<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Relation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'relations';

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
        'relatedlist_id', 'module_id', 'related_module_id', 'record_id', 'related_record_id', 'data'
    ];

    protected function initTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function relatedModule()
    {
        return $this->belongsTo(Module::class);
    }

    public function relatedlist()
    {
        return $this->belongsTo(Relatedlist::class);
    }
}
