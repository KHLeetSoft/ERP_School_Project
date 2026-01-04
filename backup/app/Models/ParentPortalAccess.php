<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParentPortalAccess extends Model
{
    use HasFactory;

    protected $table = 'parent_portal_accesses';

    protected $fillable = [
        'school_id',
        'parent_detail_id',
        'username',
        'email',
        'password_hash',
        'is_enabled',
        'last_login_at',
        'force_password_reset',
        'access_level',
        'permissions',
        'notes',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'force_password_reset' => 'boolean',
        'last_login_at' => 'datetime',
        'permissions' => 'array',
    ];

    public function parentDetail(): BelongsTo
    {
        return $this->belongsTo(ParentDetail::class, 'parent_detail_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_detail_id', 'id');
    }
}
