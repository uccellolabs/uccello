<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Module extends Model
{
    public $table = 'modules';

    public $fillable = [
        'name',
        'data',
    ];

    public $casts = [
        'data' => 'object',
    ];

    public function getStructureAttribute()
    {
        return !empty($this->data) ? $this->data->structure : null;
    }

    public function getInstance()
    {
        $modelClass = $this->data->model ?? null;
        return !empty($modelClass) ? new $modelClass : null;
    }
}
