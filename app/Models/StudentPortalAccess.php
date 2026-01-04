<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPortalAccess extends Model
{
    use HasFactory;

    protected $table = 'student_portal_accesses';

    protected $fillable = [
        'school_id',
        'student_id',
        'username',
        'email',
        'password_hash',
        'is_enabled',
        'last_login_at',
        'force_password_reset',
        'notes',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'force_password_reset' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentDetail::class, 'student_id');
    }
}


