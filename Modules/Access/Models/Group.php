<?php

namespace Modules\Access\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
