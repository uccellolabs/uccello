<?php

namespace Sardoj\Uccello;

use Sardoj\Uccello\Database\Eloquent\Model;

class Permission extends Model
{
    const CAN_CREATE = 'create';
    const CAN_RETRIEVE = 'retrieve';
    const CAN_UPDATE = 'update';
    const CAN_DELETE = 'delete';
    const CAN_ADMIN = 'admin';

    protected $table = 'permissions';

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
