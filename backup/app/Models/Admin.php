<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'users'; // Very Important

    protected $guard = 'admin'; // Optional, for clarity

    protected $fillable = [
        'name', 'email', 'password', 'school_id', 'admin_id', 'role_id', 'username', 'status'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}

