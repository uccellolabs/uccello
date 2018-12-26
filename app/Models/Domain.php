<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Collection;

class Domain extends Model
{
    use SoftDeletes;
    use Sluggable;

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
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'object',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true,
                'includeTrashed' => false
            ]
        ];
    }

    protected function initTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    public function parent()
    {
        return $this->belongsTo(self::class);
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
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
        return $this->belongsToMany(Module::class, $this->tablePrefix . 'domains_modules');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    /**
     * Returns all parents of a domain and insert also this domain if necessary.
     *
     * @param boolean $includeItself
     * @return Collection
     */
    public function parents($includeItself=true) : Collection
    {
        $parents = new Collection();

        if ($includeItself) {
            $parents[] = $this;
        }

        $domain = $this;

        while (!is_null($domain->parent)) {
            $domain = $domain->parent;
            $parents[] = $domain;
        }

        return $parents;
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

    public function getClassicMenuAttribute()
    {
        $userMenu = auth()->user()->menus()->where('type', 'classic')->where('domain_id', $this->id)->first();
        $domainMenu = $this->menus()->where('type', 'classic')->whereNull('user_id')->first();

        if (!is_null($userMenu)) {
            return $userMenu;
        } elseif (!is_null($domainMenu)) {
            return $domainMenu;
        } else {
            return null;
        }
    }

    public function getAdminMenuAttribute()
    {
        $userMenu = auth()->user()->menus()->where('type', 'admin')->where('domain_id', $this->id)->first();
        $domainMenu = $this->menus()->where('type', 'admin')->whereNull('user_id')->first();

        if (!is_null($userMenu)) {
            return $userMenu;
        } elseif (!is_null($domainMenu)) {
            return $domainMenu;
        } else {
            return null;
        }
    }
}
