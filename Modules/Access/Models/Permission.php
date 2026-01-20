<?php

namespace Modules\Access\Models;

use Shanmuga\LaravelEntrust\Models\EntrustPermission;

class Permission extends EntrustPermission
{
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
