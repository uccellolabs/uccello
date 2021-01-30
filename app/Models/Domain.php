<?php

namespace Uccello\Core\Models;

use App\Models\UccelloModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Auth;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Uccello\Core\Support\Traits\UccelloModule;
use Uccello\EloquentTree\Contracts\Tree;
use Uccello\EloquentTree\Traits\IsTree;

class Domain extends UccelloModel implements Searchable, Tree
{
    use SoftDeletes;
    use Sluggable;
    use UccelloModule;
    use IsTree;

    protected $tablePrefix;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'recordLabel',
        'uuid',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'domains';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'deleted_at' ];

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
        'name',
        'description',
        'parent_id',
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
        if (request()->has('parent')) {
            $parentRecord = Domain::find(request('parent'));

            if (!is_null($parentRecord)) {
                with($model)->setChildOf($parentRecord);
            } else { // Remove parent domain
                with($model)->setAsRoot();
            }
        }
    }

    protected function initTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
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

    public $searchableType = 'domain';

    public $searchableColumns = [
        'name'
    ];

    public function getSearchResult(): SearchResult
    {
        return new SearchResult(
            $this,
            $this->recordLabel
        );
    }

    public function privileges()
    {
        return $this->hasMany(Privilege::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, $this->tablePrefix.'domains_modules');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
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

    /**
     * Returns all admin modules activated in the domain
     *
     * @return array
     */
    protected function getAdminModulesAttribute() : array
    {
        $modules = [ ];

        foreach ($this->modules()->get() as $module) {
            if ($module->isAdminModule()) {
                $modules[ ] = $module;
            }
        }

        return $modules;
    }

    /**
     * Returns all not admin modules activated in the domain
     *
     * @return array
     */
    protected function getNotAdminModulesAttribute() : array
    {
        return Cache::rememberForever('not_admin_modules', function () {
            $modules = [ ];

            foreach ($this->modules()->get() as $module) {
                if (!$module->isAdminModule()) {
                    $modules[ ] = $module;
                }
            }

            return $modules;
        });
    }

    /**
     * Return main menu
     * Priority:
     * 1. User menu
     * 2. Domain menu
     * 3. Default menu
     *
     * @return \Uccello\Core\Models\Menu|null
     */
    public function getMainMenuAttribute()
    {
        $userMenu = Auth::user()->menus()->where('type', 'main')->where('domain_id', $this->id)->first();
        $domainMenu = $this->menus()->where('type', 'main')->whereNull('user_id')->first();
        $defaultMenu = Menu::where('type', 'main')->whereNull('domain_id')->whereNull('user_id')->first();

        if (!is_null($userMenu)) {
            return $userMenu;
        } elseif (!is_null($domainMenu)) {
            return $domainMenu;
        } elseif (!is_null($defaultMenu)) {
            return $defaultMenu;
        } else {
            return null;
        }
    }

    /**
     * Return admin menu
     * Priority:
     * 1. User menu
     * 2. Domain menu
     * 3. Default menu
     *
     * @return \Uccello\Core\Models\Menu|null
     */
    public function getAdminMenuAttribute()
    {
        $userMenu = Auth::user()->menus()->where('type', 'admin')->where('domain_id', $this->id)->first();
        $domainMenu = $this->menus()->where('type', 'admin')->whereNull('user_id')->first();
        $defaultMenu = Menu::where('type', 'admin')->whereNull('domain_id')->whereNull('user_id')->first();

        if (!is_null($userMenu)) {
            return $userMenu;
        } elseif (!is_null($domainMenu)) {
            return $domainMenu;
        } elseif (!is_null($defaultMenu)) {
            return $defaultMenu;
        } else {
            return null;
        }
    }
}
