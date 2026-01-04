<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamMark extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'exam_id',
        'class_name',
        'section_name',
        'student_id',
        'student_name',
        'admission_no',
        'roll_no',
        'subject_name',
        'max_marks',
        'obtained_marks',
        'percentage',
        'grade',
        'result_status',
        'remarks',
        'status',
    ];

    protected $casts = [
        'max_marks' => 'decimal:2',
        'obtained_marks' => 'decimal:2',
        'percentage' => 'decimal:2',
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

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Scopes
    public function scopeByExam($query, $examId)
    {
        return $query->where('exam_id', $examId);
    }

    public function scopeByClass($query, $className)
    {
        return $query->where('class_name', $className);
    }

    public function scopeBySubject($query, $subjectName)
    {
        return $query->where('subject_name', $subjectName);
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopePassed($query)
    {
        return $query->where('result_status', 'pass');
    }

    public function scopeFailed($query)
    {
        return $query->where('result_status', 'fail');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeTopPerformers($query, $limit = 10)
    {
        return $query->orderBy('obtained_marks', 'desc')->limit($limit);
    }

    // Accessors
    public function getIsPassedAttribute()
    {
        return $this->result_status === 'pass';
    }

    public function getIsFailedAttribute()
    {
        return $this->result_status === 'fail';
    }

    public function getIsPublishedAttribute()
    {
        return $this->status === 'published';
    }

    public function getGradePointAttribute()
    {
        // Calculate grade point based on percentage
        if ($this->percentage >= 90) return 4.0;
        if ($this->percentage >= 80) return 3.5;
        if ($this->percentage >= 70) return 3.0;
        if ($this->percentage >= 60) return 2.5;
        if ($this->percentage >= 50) return 2.0;
        if ($this->percentage >= 40) return 1.5;
        return 0.0;
    }

    public function getGradeDescriptionAttribute()
    {
        $gradeDescriptions = [
            'A+' => 'Excellent',
            'A' => 'Very Good',
            'B+' => 'Good',
            'B' => 'Satisfactory',
            'C+' => 'Average',
            'C' => 'Below Average',
            'D' => 'Poor',
            'F' => 'Fail'
        ];

        return $gradeDescriptions[$this->grade] ?? 'Unknown';
    }

    public function getFormattedMarksAttribute()
    {
        return $this->obtained_marks . ' / ' . $this->max_marks;
    }

    public function getFormattedPercentageAttribute()
    {
        return number_format($this->percentage, 2) . '%';
    }

    // Methods
    public function isPassed()
    {
        return $this->is_passed;
    }

    public function isFailed()
    {
        return $this->is_failed;
    }

    public function isPublished()
    {
        return $this->is_published;
    }

    public function calculateGrade()
    {
        $percentage = $this->percentage;
        
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C+';
        if ($percentage >= 40) return 'C';
        if ($percentage >= 30) return 'D';
        return 'F';
    }

    public function calculateResultStatus()
    {
        return $this->percentage >= 40 ? 'pass' : 'fail';
    }

    public function calculatePercentage()
    {
        if ($this->max_marks > 0) {
            return round(($this->obtained_marks / $this->max_marks) * 100, 2);
        }
        return 0;
    }

    public function updateGradeAndStatus()
    {
        $this->percentage = $this->calculatePercentage();
        $this->grade = $this->calculateGrade();
        $this->result_status = $this->calculateResultStatus();
        $this->save();
    }

    public function getRankInClass()
    {
        return static::where('exam_id', $this->exam_id)
                    ->where('class_name', $this->class_name)
                    ->where('subject_name', $this->subject_name)
                    ->where('obtained_marks', '>', $this->obtained_marks)
                    ->count() + 1;
    }

    public function getRankInSubject()
    {
        return static::where('exam_id', $this->exam_id)
                    ->where('subject_name', $this->subject_name)
                    ->where('obtained_marks', '>', $this->obtained_marks)
                    ->count() + 1;
    }
}


