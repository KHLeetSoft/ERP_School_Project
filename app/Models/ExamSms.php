<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamSms extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'exam_id',
        'title',
        'message_template',
        'audience_type',
        'class_name',
        'section_name',
        'schedule_at',
        'status',
        'sent_count',
        'failed_count',
    ];

    protected $casts = [
        'schedule_at' => 'datetime',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function recipients()
    {
        return $this->hasMany(ExamSmsRecipient::class, 'exam_sms_id');
    }
}


