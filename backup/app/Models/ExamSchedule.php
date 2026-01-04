<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class ExamSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'exam_id',
        'class_name',
        'section_name',
        'subject_name',
        'exam_date',
        'start_time',
        'end_time',
        'room_no',
        'max_marks',
        'pass_marks',
        'invigilator_name',
        'notes',
        'status',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'max_marks' => 'decimal:2',
        'pass_marks' => 'decimal:2',
    ];

    // Relationships
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function marks(): HasMany
    {
        return $this->hasMany(ExamMark::class, 'exam_id', 'exam_id')
                    ->where('subject_name', $this->subject_name)
                    ->where('class_name', $this->class_name);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(ExamAttendance::class, 'exam_id', 'exam_id')
                    ->where('subject_name', $this->subject_name)
                    ->where('class_name', $this->class_name);
    }

    // Scopes
    public function scopeByClass($query, $className)
    {
        return $query->where('class_name', $className);
    }

    public function scopeBySubject($query, $subjectName)
    {
        return $query->where('subject_name', $subjectName);
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('exam_date', $date);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('exam_date', '>=', now()->toDateString())
                    ->where('status', 'scheduled');
    }

    public function scopeToday($query)
    {
        return $query->where('exam_date', now()->toDateString());
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Accessors
    public function getIsUpcomingAttribute()
    {
        return $this->exam_date > now()->toDateString() && $this->status === 'scheduled';
    }

    public function getIsTodayAttribute()
    {
        return $this->exam_date->isToday();
    }

    public function getIsPastAttribute()
    {
        return $this->exam_date < now()->toDateString();
    }

    public function getDurationAttribute()
    {
        if ($this->start_time && $this->end_time) {
            return $this->start_time->diffInMinutes($this->end_time);
        }
        return null;
    }

    public function getFormattedTimeAttribute()
    {
        if ($this->start_time && $this->end_time) {
            return $this->start_time->format('h:i A') . ' - ' . $this->end_time->format('h:i A');
        }
        return null;
    }

    public function getFormattedDateAttribute()
    {
        return $this->exam_date->format('M d, Y');
    }

    public function getFullDateTimeAttribute()
    {
        return $this->exam_date->format('M d, Y') . ' ' . $this->formatted_time;
    }

    // Methods
    public function isUpcoming()
    {
        return $this->is_upcoming;
    }

    public function isToday()
    {
        return $this->is_today;
    }

    public function isPast()
    {
        return $this->is_past;
    }

    public function getTotalStudents()
    {
        return $this->marks()->distinct('student_id')->count();
    }

    public function getPresentStudents()
    {
        return $this->attendances()->where('status', 'present')->count();
    }

    public function getAbsentStudents()
    {
        return $this->attendances()->where('status', 'absent')->count();
    }

    public function getPassPercentage()
    {
        $totalStudents = $this->getTotalStudents();
        if ($totalStudents == 0) return 0;

        $passedStudents = $this->marks()->where('result_status', 'pass')->count();
        return round(($passedStudents / $totalStudents) * 100, 2);
    }

    public function getAverageMarks()
    {
        return $this->marks()->avg('obtained_marks');
    }

    public function getTopPerformers($limit = 5)
    {
        return $this->marks()
                   ->orderBy('obtained_marks', 'desc')
                   ->limit($limit)
                   ->get();
    }

    public function getAttendancePercentage()
    {
        $totalStudents = $this->getTotalStudents();
        if ($totalStudents == 0) return 0;

        $presentStudents = $this->getPresentStudents();
        return round(($presentStudents / $totalStudents) * 100, 2);
    }
}


