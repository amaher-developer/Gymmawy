<?php

namespace Modules\Access\Models;

use Shanmuga\LaravelEntrust\Models\LaravelEntrustRole;

class Role extends LaravelEntrustRole
{
    protected $fillable = ['name', 'display_name', 'description'];
    public $timestamps = TRUE;

}
