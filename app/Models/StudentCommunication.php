<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentCommunication extends Model
{
    use HasFactory;

    protected $table = 'student_communications';

    protected $fillable = [
        'school_id',
        'student_id',
        'class_id',
        'subject',
        'message',
        'channel', // sms | email | notice
        'status',  // draft | sent | scheduled
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentDetail::class, 'student_id');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}


