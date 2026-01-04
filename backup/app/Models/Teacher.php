<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'qualification',
        'experience',
        'subject',
        'joining_date',
        'salary',
        'status',
        'profile_image',
        'bio',
        'specialization',
        'certifications',
        'emergency_contact',
        'bank_details',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'certifications' => 'array',
        'bank_details' => 'array',
    ];

    // Relationship with User model
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Get teacher's name from user relationship
    public function getNameAttribute()
    {
        return $this->user->name;
    }

    // Get teacher's email from user relationship
    public function getEmailAttribute()
    {
        return $this->user->email;
    }

    // Check if teacher is active
    public function isActive()
    {
        return $this->status === 'active' && $this->user->status === 'active';
    }
    public function classes()
    {
        return $this->hasMany(ClassRoom::class, 'teacher_id');
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, ClassRoom::class, 'teacher_id', 'class_id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'teacher_id');
    }
}
