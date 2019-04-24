<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Capability extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'capabilities';

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
        'data',
    ];

    protected function initTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
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
}
