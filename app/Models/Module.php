<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Module extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'modules';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'object',
    ];

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function domains()
    {
        return $this->belongsToMany(Domain::class, $this->tablePrefix . 'domains_modules');
    }

    public function tabs()
    {
        return $this->hasMany(Tab::class)->orderBy('sequence');
    }

    public function blocks()
    {
        return $this->hasMany(Block::class)->orderBy('sequence');
    }

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    public function filters()
    {
        return $this->hasMany(Filter::class);
    }

    /**
     * Searches in the module a field by name.
     *
     * @param string $name
     * @return Field|null
     */
    public function getField($name) : ?Field
    {
        return $this->fields()->where('name', $name)->first();
    }

    /**
     * Checks if the module is active on a domain.
     *
     * @param Domain $domain
     * @return boolean
     */
    public function isActiveOnDomain(Domain $domain) : bool
    {
        $isActive = false;

        foreach ($this->domains as $domainActive) {
            if ($domainActive->id === $domain->id) {
                $isActive = true;
                break;
            }
        }

        return $isActive;
    }

    /**
     * Checks if the module is for administration.
     *
     * @return boolean
     */
    public function isAdminModule() : bool
    {
        return $this->data->admin ?? false;
    }
}
