<?php

namespace Modules\Access\Models;

use Shanmuga\LaravelEntrust\Models\EntrustRole;

class Role extends EntrustRole
{
    protected $fillable = ['name', 'display_name', 'description'];
    public $timestamps = TRUE;

    /**
     * Alias for permissions() for backward compatibility
     */
    public function perms()
    {
        return $this->permissions();
    }
}
