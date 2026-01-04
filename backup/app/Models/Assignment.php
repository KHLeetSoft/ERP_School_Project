<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'class_id',
        'section_id',
        'subject_id',
        'teacher_id',
        'title',
        'description',
        'file',
        'assigned_date',
        'due_date',
        'priority',
        'status',
        'max_marks',
        'passing_marks',
    ];

    protected $casts = [
        'assigned_date' => 'datetime',
        'due_date' => 'datetime',
        'max_marks' => 'integer',
        'passing_marks' => 'integer',
    ];

    // Relationships
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    // Scopes
    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['published', 'assigned']);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('due_date', '>', now());
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now());
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => '<span class="badge bg-secondary">Draft</span>',
            'published' => '<span class="badge bg-success">Published</span>',
            'assigned' => '<span class="badge bg-primary">Assigned</span>',
            'completed' => '<span class="badge bg-info">Completed</span>',
            'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    public function getPriorityBadgeAttribute()
    {
        $badges = [
            'low' => '<span class="badge bg-success">Low</span>',
            'medium' => '<span class="badge bg-warning">Medium</span>',
            'high' => '<span class="badge bg-danger">High</span>',
        ];

        return $badges[$this->priority] ?? '<span class="badge bg-secondary">Normal</span>';
    }

    public function getDueDateFormattedAttribute()
    {
        return $this->due_date->format('M d, Y H:i');
    }

    public function getTimeRemainingAttribute()
    {
        $now = now();
        $dueDate = $this->due_date;

        if ($dueDate->isPast()) {
            return 'Overdue';
        }

        $diff = $now->diff($dueDate);

        if ($diff->days > 0) {
            return $diff->days . ' days remaining';
        } elseif ($diff->h > 0) {
            return $diff->h . ' hours remaining';
        } else {
            return $diff->i . ' minutes remaining';
        }
    }

    public function getIsOverdueAttribute()
    {
        return $this->due_date->isPast() && !in_array($this->status, ['completed', 'cancelled']);
    }

    public function getIsAvailableAttribute()
    {
        return in_array($this->status, ['published', 'assigned']);
    }

    public function getSubmissionCountAttribute()
    {
        return $this->submissions()->count();
    }

    public function getGradedCountAttribute()
    {
        return $this->submissions()->whereNotNull('grade')->count();
    }

    public function getPendingCountAttribute()
    {
        return $this->submissions()->whereNull('grade')->count();
    }

    public function getClassSectionNameAttribute()
    {
        $className = $this->schoolClass ? $this->schoolClass->name : 'Unknown Class';
        $sectionName = $this->section ? $this->section->name : '';
        
        return $className . ($sectionName ? ' - ' . $sectionName : '');
    }

    public function getSubjectNameAttribute()
    {
        return $this->subject ? $this->subject->name : 'Unknown Subject';
    }

    // Methods
    public function isOverdue()
    {
        return $this->due_date->isPast() && !in_array($this->status, ['completed', 'cancelled']);
    }

    public function isAvailable()
    {
        return in_array($this->status, ['published', 'assigned']);
    }

    public function getCompletionPercentage()
    {
        if ($this->submission_count == 0) {
            return 0;
        }

        return round(($this->graded_count / $this->submission_count) * 100, 2);
    }

    public function getAverageGrade()
    {
        return $this->submissions()
            ->whereNotNull('grade')
            ->avg('grade');
    }

    public function getPassingCount()
    {
        return $this->submissions()
            ->where('grade', '>=', $this->passing_marks)
            ->count();
    }

    public function getFailingCount()
    {
        return $this->submissions()
            ->where('grade', '<', $this->passing_marks)
            ->count();
    }
}