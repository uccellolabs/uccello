<?php

namespace Sardoj\Uccello\Models;

use Sardoj\Uccello\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * List of capabilities.
     * The order is respected in profile permissions list.
     * Important: A capability name must begin by CAPABILITY_
     * 
     * @see Sardoj\Uccello\Helpers\Uccello
     */
    public const CAPABILITY_RETRIEVE            = 'retrieve';
    public const CAPABILITY_CREATE              = 'create';
    public const CAPABILITY_UPDATE              = 'update';
    public const CAPABILITY_DELETE              = 'delete';
    public const CAPABILITY_RETRIEVE_ONLY_API   = 'api.retrieve';
    public const CAPABILITY_CREATE_ONLY_API     = 'api.create';
    public const CAPABILITY_UPDATE_ONLY_API     = 'api.update';
    public const CAPABILITY_DELETE_ONLY_API     = 'api.delete';
    public const CAPABILITY_ADMIN               = 'admin';
    // public const CAN_VIEW_MAP  = 'map';
    // public const CAN_DESCRIBE  = 'describe';
    
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
