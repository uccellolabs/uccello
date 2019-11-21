<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Entity extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'entities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'module_id',
        'record_id',
        'creator_id',
    ];

    protected $primaryKey = 'id';   // TODO: Change to "uid" to make joins withs modules tables possible ???
    public $incrementing = false;

    // Allow Eloquent to return id as string instead of int.
    protected $casts = ['id' => 'string'];

    protected function initTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function getModuleAttribute()
    {
        return Module::find($this->module_id);
    }

    public function getRecordAttribute()
    {
        return $this->module->model_class::find($this->record_id);
    }
}
