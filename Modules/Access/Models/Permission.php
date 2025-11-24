<?php

namespace Modules\Access\Models;

use Shanmuga\LaravelEntrust\Models\LaravelEntrustPermission;

class Permission extends LaravelEntrustPermission
{
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
