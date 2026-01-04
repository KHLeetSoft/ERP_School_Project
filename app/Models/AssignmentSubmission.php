<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'submission_text',
        'submission_file',
        'submitted_at',
        'grade',
        'feedback',
        'graded_at',
        'graded_by',
        'status',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'grade' => 'decimal:2',
    ];

    // Relationships
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    // Scopes
    public function scopeGraded($query)
    {
        return $query->whereNotNull('grade');
    }

    public function scopePending($query)
    {
        return $query->whereNull('grade');
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    // Accessors
    public function getIsGradedAttribute()
    {
        return !is_null($this->grade);
    }

    public function getIsPassedAttribute()
    {
        if (!$this->is_graded || !$this->assignment) {
            return false;
        }

        return $this->grade >= $this->assignment->passing_marks;
    }

    public function getGradePercentageAttribute()
    {
        if (!$this->is_graded || !$this->assignment) {
            return null;
        }

        return round(($this->grade / $this->assignment->max_marks) * 100, 2);
    }
}
