<?php

namespace Sardoj\Uccello;

use Sardoj\Uccello\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class Domain extends Model
{
    use HasSlug;
    use SoftDeletes;
    
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
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function parent()
    {
        return $this->belongsTo(self::class);
    }

    public function children()
    {
        return $this->hasMany(self::class);
    }

    public function privileges()
    {
        return $this->hasMany(Privilege::class);
    }

    public function roles()
    {
        return $this->hasMany(Roles::class);
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, $this->tablePrefix . 'domains_modules');
    }
}
