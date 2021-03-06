<?php

namespace Uccello\Core\Models;

use App\Models\UccelloModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Uccello\Core\Support\Traits\UccelloModule;
use Uccello\EloquentTree\Contracts\Tree;
use Uccello\EloquentTree\Traits\IsTree;

class Role extends UccelloModel implements Searchable, Tree
{
    use SoftDeletes;
    use UccelloModule;
    use IsTree;

    protected $tablePrefix;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'deleted_at' ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'domain_id'
    ];

    public $searchableType = 'role';

    public $searchableColumns = [
        'name'
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
            } else { // Remove parent domain
                with($model)->setAsRoot();
            }
        }
    }

    public function getSearchResult(): SearchResult
    {
        return new SearchResult(
            $this,
            $this->recordLabel
        );
    }

    protected function initTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function privileges()
    {
        return $this->hasMany(Privilege::class);
    }

    public function profiles()
    {
        return $this->belongsToMany(Profile::class, $this->tablePrefix.'profiles_roles');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'uccello_privileges');
    }

    /**
     * Returns record label
     *
     * @return string
     */
    public function getRecordLabelAttribute() : string
    {
        return $this->name;
    }
}
