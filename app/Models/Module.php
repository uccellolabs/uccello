<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;
use Uccello\Core\Support\Traits\HasModuleStructure;

class Module extends Model
{
    use HasModuleStructure;

    public $table = 'modules';

    public $fillable = [
        'name',
        'data',
    ];

    public $casts = [
        'data' => 'object',
    ];

    public static function booted()
    {
        static::saving(function ($model) {
            $model->retrieveNameFromData();
        });
    }

    private function retrieveNameFromData()
    {
        if (empty($this->name) && optional($this->data)->name) {
            $this->name = $this->data->name;
        }
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
        if (isset($this->data->package)) {
            $packageData = explode('/', $this->data->package);
            $package = array_pop($packageData);
        }

        return $package;
    }
}
