<?php

namespace Sardoj\Uccello\Models;

use Sardoj\Uccello\Database\Eloquent\Model;

class Module extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'modules';

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

    public function filters()
    {
        return $this->hasMany(Filter::class);
    }

    /**
     * Returns all module fields.
     *
     * @return array
     */
    public function getFieldsAttribute(): array
    {
        $fields = [];

        foreach($this->tabs as $tab)
        {
            foreach($tab->blocks as $block)
            {
                foreach($block->fields as $field)
                {
                    $fields[] = $field;
                }
            }
        }

        return $fields;
    }

    /**
     * Checks if the module is active on a domain.
     *
     * @param Domain $domain
     * @return boolean
     */
    public function isActiveOnDomain(Domain $domain): bool
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
}
