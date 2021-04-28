<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;
use Uccello\Core\Support\Traits\HasModuleStructure;

class Module extends Model
{
    use HasModuleStructure;

    public $table = 'modules';

    public $fillable = [
        'name',
        'data',
    ];

    public $casts = [
        'data' => 'object',
    ];

    public static function booted()
    {
        static::saving(function ($model) {
            $model->retrieveNameFromData();
        });
    }

    private function retrieveNameFromData()
    {
        if (empty($this->name) && optional($this->data)->name) {
            $this->name = $this->data->name;
        }
    }
}
