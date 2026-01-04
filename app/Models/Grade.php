<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'student_id',
        'class_name',
        'subject_name',
        'assignment_name',
        'assignment_type',
        'points_earned',
        'total_points',
        'percentage',
        'letter_grade',
        'comments',
        'graded_date',
        'status',
    ];

    protected $casts = [
        'points_earned' => 'decimal:2',
        'total_points' => 'decimal:2',
        'percentage' => 'decimal:2',
        'graded_date' => 'date',
    ];

    // Relationships
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    // Scopes
    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeByClass($query, $className)
    {
        return $query->where('class_name', $className);
    }

    public function scopeBySubject($query, $subjectName)
    {
        return $query->where('subject_name', $subjectName);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Accessors
    public function getGradeColorAttribute()
    {
        return match($this->letter_grade) {
            'A+', 'A' => 'success',
            'B+', 'B' => 'info',
            'C+', 'C' => 'warning',
            'D' => 'secondary',
            'F' => 'danger',
            default => 'secondary'
        };
    }

    public function getGradeBadgeAttribute()
    {
        $color = $this->grade_color;
        return "<span class='badge bg-{$color}'>{$this->letter_grade}</span>";
    }

    // Methods
    public function calculatePercentage()
    {
        if ($this->total_points > 0) {
            $this->percentage = ($this->points_earned / $this->total_points) * 100;
        }
        return $this->percentage;
    }

    public function calculateLetterGrade()
    {
        $percentage = $this->calculatePercentage();
        
        if ($percentage >= 97) return 'A+';
        if ($percentage >= 93) return 'A';
        if ($percentage >= 90) return 'A-';
        if ($percentage >= 87) return 'B+';
        if ($percentage >= 83) return 'B';
        if ($percentage >= 80) return 'B-';
        if ($percentage >= 77) return 'C+';
        if ($percentage >= 73) return 'C';
        if ($percentage >= 70) return 'C-';
        if ($percentage >= 67) return 'D+';
        if ($percentage >= 65) return 'D';
        return 'F';
    }
}
