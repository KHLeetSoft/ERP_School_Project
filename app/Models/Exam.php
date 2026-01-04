<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class Exam extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'title',
        'exam_type',
        'academic_year',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relationships
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ExamSchedule::class);
    }

    public function marks(): HasMany
    {
        return $this->hasMany(ExamMark::class);
    }

    public function tabulations(): HasMany
    {
        return $this->hasMany(ExamTabulation::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(ExamAttendance::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('start_date', '>', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed')
                    ->orWhere('end_date', '<', now());
    }

    public function scopeByType($query, $type)
    {
        return $query->where('exam_type', $type);
    }

    public function scopeByAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    // Accessors
    public function getIsActiveAttribute()
    {
        return $this->status === 'scheduled' && 
               now()->between($this->start_date, $this->end_date);
    }

    public function getIsUpcomingAttribute()
    {
        return $this->status === 'scheduled' && $this->start_date > now();
    }

    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed' || $this->end_date < now();
    }

    public function getDurationAttribute()
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->diffInDays($this->end_date) + 1;
        }
        return null;
    }

    public function getTotalSchedulesAttribute()
    {
        return $this->schedules()->count();
    }

    public function getTotalStudentsAttribute()
    {
        return $this->marks()->distinct('student_id')->count();
    }

    public function getTotalSubjectsAttribute()
    {
        return $this->schedules()->distinct('subject_name')->count();
    }

    // Methods
    public function isActive()
    {
        return $this->is_active;
    }

    public function isUpcoming()
    {
        return $this->is_upcoming;
    }

    public function isCompleted()
    {
        return $this->is_completed;
    }

    public function getSchedulesByClass($className)
    {
        return $this->schedules()->where('class_name', $className)->get();
    }

    public function getSchedulesBySubject($subjectName)
    {
        return $this->schedules()->where('subject_name', $subjectName)->get();
    }

    public function getStudentMarks($studentId)
    {
        return $this->marks()->where('student_id', $studentId)->get();
    }

    public function getClassResults($className)
    {
        return $this->tabulations()->where('class_name', $className)->get();
    }

    public function getPassPercentage()
    {
        $totalStudents = $this->total_students;
        if ($totalStudents == 0) return 0;

        $passedStudents = $this->tabulations()->where('result_status', 'pass')->count();
        return round(($passedStudents / $totalStudents) * 100, 2);
    }

    public function getAverageMarks()
    {
        return $this->tabulations()->avg('percentage');
    }

    public function getTopPerformers($limit = 10)
    {
        return $this->tabulations()
                   ->orderBy('percentage', 'desc')
                   ->limit($limit)
                   ->get();
    }
    public static function countForTeacher($teacherId)
    {
        try {
            if (Schema::hasColumn((new static)->getTable(), 'teacher_id')) {
                return static::where('teacher_id', $teacherId)->count();
            }
        } catch (\Exception $e) {
            // If there's any error checking the column or querying, return 0
            return 0;
        }

        // fallback behavior: return 0 (or change to another column/filter)
        return 0;
    }

}


