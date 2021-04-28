<?php

namespace Uccello\Core\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Uccello\Core\Database\Eloquent\Model;
use Uccello\Core\Database\Factories\WorkspaceFactory;
use Uccello\Core\Support\Traits\UccelloModule;
use Uccello\EloquentTree\Contracts\Tree;
use Uccello\EloquentTree\Traits\IsTree;

class Workspace extends Model implements Tree
{
    use HasFactory, SoftDeletes, Sluggable, IsTree, UccelloModule;

    public $table = 'workspaces';

    public $fillable = [
        'name',
        'parent_id',
        'data',
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
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true,
                'includeTrashed' => false
            ]
        ];
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return WorkspaceFactory::new();
    }
}
