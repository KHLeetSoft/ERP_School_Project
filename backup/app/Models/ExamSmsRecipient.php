<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSmsRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_sms_id',
        'recipient_type',
        'recipient_id',
        'phone',
        'status',
        'sent_at',
        'error',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}


