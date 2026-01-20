<?php

namespace Modules\Access\Models;

use Shanmuga\LaravelEntrust\Models\EntrustRole;

class Role extends EntrustRole
{
    protected $fillable = ['name', 'display_name', 'description'];
    public $timestamps = TRUE;

}
