<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OnlineExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'title',
        'description',
        'class_id',
        'section_id',
        'subject_id',
        'duration_minutes',
        'total_marks',
        'passing_marks',
        'start_datetime',
        'end_datetime',
        'negative_marking',
        'negative_marks',
        'randomize_questions',
        'show_result_immediately',
        'status',
        'instructions',
        'allow_calculator',
        'allow_notes',
        'max_attempts',
        'enable_proctoring',
        'proctoring_settings'
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'negative_marking' => 'boolean',
        'randomize_questions' => 'boolean',
        'show_result_immediately' => 'boolean',
        'allow_calculator' => 'boolean',
        'allow_notes' => 'boolean',
        'enable_proctoring' => 'boolean',
        'proctoring_settings' => 'array',
        'negative_marks' => 'decimal:2',
    ];

    /**
     * Get the class that this exam belongs to.
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Get the section that this exam belongs to.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    /**
     * Get the subject that this exam belongs to.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Get the questions for this exam.
     */
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'online_exam_questions')
                    ->withPivot(['marks', 'order_number'])
                    ->orderBy('online_exam_questions.order_number');
    }

    /**
     * Get all attempts for this exam.
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(OnlineExamAttempt::class);
    }

    /**
     * Get completed attempts for this exam.
     */
    public function completedAttempts(): HasMany
    {
        return $this->hasMany(OnlineExamAttempt::class)
                    ->whereIn('status', ['submitted', 'auto_submitted']);
    }

    /**
     * Scope for active exams.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'published')
                    ->where('start_datetime', '<=', now())
                    ->where('end_datetime', '>=', now());
    }

    /**
     * Scope for upcoming exams.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'published')
                    ->where('start_datetime', '>', now());
    }

    /**
     * Scope for completed exams.
     */
    public function scopeCompleted($query)
    {
        return $query->where('end_datetime', '<', now());
    }

    /**
     * Check if exam is currently active.
     */
    public function isActive(): bool
    {
        return $this->status === 'published' 
               && now()->between($this->start_datetime, $this->end_datetime);
    }

    /**
     * Check if exam is upcoming.
     */
    public function isUpcoming(): bool
    {
        return $this->status === 'published' 
               && now() < $this->start_datetime;
    }

    /**
     * Check if exam is completed.
     */
    public function isCompleted(): bool
    {
        return now() > $this->end_datetime;
    }

    /**
     * Get student's attempt for this exam.
     */
    public function getStudentAttempt($studentId)
    {
        return $this->attempts()
                    ->where('student_id', $studentId)
                    ->latest('attempt_number')
                    ->first();
    }

    /**
     * Check if student can take this exam.
     */
    public function canStudentTakeExam($studentId): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        $attemptCount = $this->attempts()
                            ->where('student_id', $studentId)
                            ->count();

        return $attemptCount < $this->max_attempts;
    }

    /**
     * Get exam statistics.
     */
    public function getStatistics()
    {
        $attempts = $this->completedAttempts();
        
        return [
            'total_attempts' => $attempts->count(),
            'passed_count' => $attempts->where('is_passed', true)->count(),
            'failed_count' => $attempts->where('is_passed', false)->count(),
            'average_score' => $attempts->avg('percentage') ?? 0,
            'highest_score' => $attempts->max('percentage') ?? 0,
            'lowest_score' => $attempts->min('percentage') ?? 0,
        ];
    }
}
