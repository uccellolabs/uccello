<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'icon',
        'model_class',
        'data'
    ];

    protected function initTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function domains()
    {
        return $this->belongsToMany(Domain::class, $this->tablePrefix.'domains_modules');
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

    public function relatedlists()
    {
        return $this->hasMany(Relatedlist::class, 'module_id')->orderBy('sequence');
    }

    public function links()
    {
        return $this->hasMany(Link::class, 'module_id')->orderBy('sequence');
    }

    public function detailLinks()
    {
        return $this->hasMany(Link::class, 'module_id')->where('type', 'detail')->orderBy('sequence');
    }

    public function detailActionLinks()
    {
        return $this->hasMany(Link::class, 'module_id')->where('type', 'detail.action')->orderBy('sequence');
    }

    public function widgets()
    {
        return $this->belongsToMany(Widget::class, $this->tablePrefix.'modules_widgets')->withPivot('data');
    }

    /**
     * Returns module package name
     *
     * @return string|null
     */
    public function getPackageAttribute() : ?string
    {
        $package = ''; // For modules created directory in the host application

        // Get only package name if defined (Format: vendor/package)
        if (isset($this->data->package))
        {
            $packageData = explode('/', $this->data->package);
            $package = array_pop($packageData);
        }

        return $package;
    }

    /**
     * Return all module links to display in the menu
     *
     * @return array
     */
    public function getMenuLinksAttribute() : array
    {
        $menuLinks = [ ];

        //TODO: Adds capability needed

        if (isset($this->data->menu)) {
            // One route
            if (is_string($this->data->menu)) {
                $link = new \StdClass;
                $link->label = $this->name;
                $link->route = $this->data->menu;
                $link->icon = $this->icon;
                $menuLinks[ ] = $link;
            }
            // Several routes
            elseif (is_array($this->data->menu)) {
                foreach ($this->data->menu as $link) {
                    if (empty($link->icon)) {
                        $link->icon = $this->icon;
                    }
                    $menuLinks[ ] = $link;
                }
            }
            // No route wanted
            elseif ($this->data->menu === false) {
                // Nothing to do
            }
        }
        // No route defined, add it automaticaly
        else {
            $link = new \StdClass;
            $link->label = $this->name;
            $link->route = 'uccello.list';
            $link->icon = $this->icon;
            $menuLinks[ ] = $link;
        }

        return $menuLinks;
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

    /**
     * Check if the module is mandatory.
     *
     * @return boolean
     */
    public function isMandatory() : bool
    {
        return $this->data->mandatory ?? false;
    }
}
