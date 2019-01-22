<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Widget extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'widgets';

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
        'module_id',
        'label',
        'type',
        'class',
        'data',
    ];

    protected function initTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, $this->tablePrefix.'modules_widgets')->withPivot('data');
    }

    /**
     * Returns full package name if it is defined, else returns null
     *
     * @return string|null
     */
    public function getPackageAttribute()
    {
        return $this->data->package ?? null;
    }

    /**
     * Returns package name to use for translation if defined, else returns an empty string
     *
     * @return string
     */
    public function getLanguagePackageAttribute()
    {
        $package = '';

        if ($this->data->package ?? false) {
            $packageParts = explode('/', $this->data->package);
            $package = $packageParts[count($packageParts)-1].'::';
        }

        return $package;
    }

    /**
     * Returns widget label for translation (with package name as prefix)
     *
     * @return string
     */
    public function getLabelForTranslationAttribute()
    {
        return $this->languagePackage.'widgets.'.$this->label; // We have to create a locale file called widgets.php
    }
}
