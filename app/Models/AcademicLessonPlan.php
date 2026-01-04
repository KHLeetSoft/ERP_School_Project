<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class AcademicLessonPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'subject_id',
        'syllabus_id',
        'title',
        'lesson_number',
        'unit_number',
        'learning_objectives',
        'prerequisites',
        'materials_needed',
        'lesson_duration',
        'teaching_methods',
        'activities',
        'assessment_methods',
        'homework',
        'notes',
        'status',
        'planned_date',
        'actual_date',
        'completion_status',
        'difficulty_level',
        'estimated_student_count',
        'room_requirements',
        'technology_needed',
        'special_considerations',
    ];

    protected $casts = [
        'learning_objectives' => 'array',
        'prerequisites' => 'array',
        'materials_needed' => 'array',
        'teaching_methods' => 'array',
        'activities' => 'array',
        'assessment_methods' => 'array',
        'planned_date' => 'date',
        'actual_date' => 'date',
        'lesson_duration' => 'integer',
        'estimated_student_count' => 'integer',
        'difficulty_level' => 'integer',
        'status' => 'boolean',
        'completion_status' => 'string',
    ];

    // Difficulty levels
    const DIFFICULTY_BEGINNER = 1;
    const DIFFICULTY_INTERMEDIATE = 2;
    const DIFFICULTY_ADVANCED = 3;

    // Completion statuses
    const STATUS_PLANNED = 'planned';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_POSTPONED = 'postponed';
    const STATUS_CANCELLED = 'cancelled';

    public function scopeForSchool($query, ?int $schoolId)
    {
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }
        return $query;
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('completion_status', $status);
    }

    public function scopeByDifficulty($query, int $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('planned_date', '>=', now()->startOfDay());
    }

    public function scopeOverdue($query)
    {
        return $query->where('planned_date', '<', now()->startOfDay())
                    ->where('completion_status', '!=', self::STATUS_COMPLETED);
    }

    public function subject()
    {
        return $this->belongsTo(AcademicSubject::class, 'subject_id');
    }

    public function syllabus()
    {
        return $this->belongsTo(AcademicSyllabus::class, 'syllabus_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function getDifficultyTextAttribute(): string
    {
        return match($this->difficulty_level) {
            self::DIFFICULTY_BEGINNER => 'Beginner',
            self::DIFFICULTY_INTERMEDIATE => 'Intermediate',
            self::DIFFICULTY_ADVANCED => 'Advanced',
            default => 'Unknown'
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        $classes = match($this->completion_status) {
            self::STATUS_PLANNED => 'bg-info',
            self::STATUS_IN_PROGRESS => 'bg-warning',
            self::STATUS_COMPLETED => 'bg-success',
            self::STATUS_POSTPONED => 'bg-secondary',
            self::STATUS_CANCELLED => 'bg-danger',
            default => 'bg-secondary'
        };

        return '<span class="badge ' . $classes . '">' . ucfirst(str_replace('_', ' ', $this->completion_status)) . '</span>';
    }

    public function getDurationTextAttribute(): string
    {
        if (!$this->lesson_duration) return 'Not specified';
        
        $hours = intval($this->lesson_duration / 60);
        $minutes = $this->lesson_duration % 60;
        
        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$minutes}m";
        }
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->planned_date && 
               $this->planned_date < now()->startOfDay() && 
               $this->completion_status !== self::STATUS_COMPLETED;
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->planned_date && 
               $this->planned_date >= now()->startOfDay() && 
               $this->completion_status === self::STATUS_PLANNED;
    }

    public function getProgressPercentageAttribute(): int
    {
        return match($this->completion_status) {
            self::STATUS_PLANNED => 0,
            self::STATUS_IN_PROGRESS => 50,
            self::STATUS_COMPLETED => 100,
            self::STATUS_POSTPONED => 25,
            self::STATUS_CANCELLED => 0,
            default => 0
        };
    }

    public function getFormattedPlannedDateAttribute(): string
    {
        return $this->planned_date ? $this->planned_date->format('M d, Y (D)') : 'Not scheduled';
    }

    public function getFormattedActualDateAttribute(): string
    {
        return $this->actual_date ? $this->actual_date->format('M d, Y (D)') : 'Not completed';
    }

    public function getLearningObjectivesListAttribute(): array
    {
        return is_array($this->learning_objectives) ? $this->learning_objectives : [];
    }

    public function getMaterialsListAttribute(): array
    {
        return is_array($this->materials_needed) ? $this->materials_needed : [];
    }

    public function getActivitiesListAttribute(): array
    {
        return is_array($this->activities) ? $this->activities : [];
    }

    public function getAssessmentMethodsListAttribute(): array
    {
        return is_array($this->assessment_methods) ? $this->assessment_methods : [];
    }

    public function getTeachingMethodsListAttribute(): array
    {
        return is_array($this->teaching_methods) ? $this->teaching_methods : [];
    }

    public function getPrerequisitesListAttribute(): array
    {
        return is_array($this->prerequisites) ? $this->prerequisites : [];
    }
}
