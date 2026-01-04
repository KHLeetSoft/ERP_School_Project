<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ParentDetail extends Model
{
    use HasFactory;

    protected $table = 'parent_details';

    protected $fillable = [
        'user_id',
        'school_id',
        'primary_contact_name',
        'father_name',
        'mother_name',
        'guardian_name',
        'phone_primary',
        'phone_secondary',
        'email_primary',
        'email_secondary',
        'address',
        'occupation_father',
        'occupation_mother',
        'income_range',
        'emergency_contact_name',
        'emergency_contact_phone',
        'status',
        'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(StudentDetail::class, 'parent_student', 'parent_detail_id', 'student_id')
            ->withPivot(['relation','notes'])
            ->withTimestamps();
    }
}


