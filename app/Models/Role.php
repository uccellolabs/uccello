<?php

namespace Uccello\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Uccello\Core\Database\Eloquent\Model;
use Uccello\Core\Database\Factories\RoleFactory;
use Uccello\Core\Support\Traits\UccelloModule;
use Uccello\EloquentTree\Contracts\Tree;
use Uccello\EloquentTree\Traits\IsTree;

class Role extends Model implements Tree
{
    use HasFactory, SoftDeletes, IsTree, UccelloModule;

    public $table = 'roles';

    public $fillable = [
        'name',
        'parent_id',
        'description',
        'data',
        'workspace_id',
    ];

    public $casts = [
        'data' => 'object',
    ];

    protected $stopEventPropagation = false;

    public static function boot()
    {
        parent::boot();

        // Linck to parent record
        static::created(function ($model) {
            static::linkToParentRecord($model);
        });

        static::updated(function ($model) {
            if (!$model->stopEventPropagation) {
                $model->stopEventPropagation = true;
                static::linkToParentRecord($model);
            }
        });
    }

    public static function linkToParentRecord($model)
    {
        // Set parent record
        if (request()->has('parent')) {
            $parentRecord = $model::find(request('parent'));

            if (!is_null($parentRecord)) {
                with($model)->setChildOf($parentRecord);
            } else { // Remove parent workspace
                with($model)->setAsRoot();
            }
        }
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return RoleFactory::new();
    }
}
