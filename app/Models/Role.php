<?php

namespace Uccello\Core\Models;

use Gzero\EloquentTree\Model\Tree;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Uccello\Core\Support\Traits\UccelloModule;

class Role extends Tree implements Searchable
{
    use SoftDeletes;
    use UccelloModule;

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

    public static function boot()
    {
        parent::boot();

        // Linck to parent record
        static::created(function ($model) {
            static::linkToParentRecord($model);
        });

        // static::updatedParent(function ($model) {
        //     static::linkToParentRecord($model);
        // });

        static::updated(function ($model) {
            static::linkToParentRecord($model);
        });
    }

    public static function linkToParentRecord($model)
    {
        // Set parent record
        $parentRecord = Role::find(request('parent'));
        if (!is_null($parentRecord)) {
            with($model)->setChildOf($parentRecord);
        }
        // Remove parent domain
        else {
            with($model)->setAsRoot();
        }
    }

    /**
     * Check if node is root
     * This function check foreign key field
     *
     * @return bool
     */
    public function isRoot()
    {
        // return (empty($this->{$this->getTreeColumn('parent')})) ? true : false;
        return $this->{$this->getTreeColumn('path')} === $this->getKey() . '/'
                && $this->{$this->getTreeColumn('level')} === 0;
    }

    public function getSearchResult(): SearchResult
    {
        return new SearchResult(
            $this,
            $this->recordLabel
        );
    }

    public function __construct(array $attributes = [ ])
    {
        parent::__construct($attributes);

        // Init table prefix
        $this->initTablePrefix();

        // Init table name
        $this->initTableName();

        $this->addTreeEvents(); // Adding tree events
    }

    public function getTablePrefix()
    {
        return $this->tablePrefix;
    }

    protected function initTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    protected function initTableName()
    {
        if ($this->table)
        {
            $this->table = $this->tablePrefix.$this->table;
        }
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
