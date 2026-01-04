<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnlineExamAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'online_exam_id',
        'student_id',
        'started_at',
        'submitted_at',
        'time_taken_minutes',
        'total_marks_obtained',
        'percentage',
        'status',
        'answers',
        'proctoring_data',
        'is_passed',
        'attempt_number'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'total_marks_obtained' => 'decimal:2',
        'percentage' => 'decimal:2',
        'answers' => 'array',
        'proctoring_data' => 'array',
        'is_passed' => 'boolean',
    ];

    /**
     * Get the online exam that this attempt belongs to.
     */
    public function onlineExam(): BelongsTo
    {
        return $this->belongsTo(OnlineExam::class);
    }

    /**
     * Get the student who made this attempt.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Calculate and update marks for this attempt.
     */
    public function calculateMarks()
    {
        $totalMarks = 0;
        $obtainedMarks = 0;

        foreach ($this->onlineExam->questions as $question) {
            $totalMarks += $question->pivot->marks;
            
            if (isset($this->answers[$question->id])) {
                $studentAnswer = $this->answers[$question->id];
                
                if ($question->type === 'mcq') {
                    if ($studentAnswer == $question->correct_answer) {
                        $obtainedMarks += $question->pivot->marks;
                    } elseif ($this->onlineExam->negative_marking && !empty($studentAnswer)) {
                        $obtainedMarks -= $this->onlineExam->negative_marks;
                    }
                }
                // Add more question types as needed
            }
        }

        $percentage = $totalMarks > 0 ? ($obtainedMarks / $totalMarks) * 100 : 0;
        $isPassed = $percentage >= (($this->onlineExam->passing_marks / $this->onlineExam->total_marks) * 100);

        $this->update([
            'total_marks_obtained' => $obtainedMarks,
            'percentage' => $percentage,
            'is_passed' => $isPassed
        ]);

        return $this;
    }

    /**
     * Check if this attempt is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if this attempt is submitted.
     */
    public function isSubmitted(): bool
    {
        return in_array($this->status, ['submitted', 'auto_submitted']);
    }

    /**
     * Get time remaining for this attempt in minutes.
     */
    public function getTimeRemainingMinutes(): int
    {
        if ($this->isSubmitted()) {
            return 0;
        }

        $examDuration = $this->onlineExam->duration_minutes;
        $elapsedMinutes = $this->started_at->diffInMinutes(now());
        
        return max(0, $examDuration - $elapsedMinutes);
    }

    /**
     * Check if this attempt has expired.
     */
    public function hasExpired(): bool
    {
        return $this->getTimeRemainingMinutes() <= 0;
    }

    /**
     * Auto submit if time has expired.
     */
    public function autoSubmitIfExpired()
    {
        if ($this->isInProgress() && $this->hasExpired()) {
            $this->update([
                'status' => 'auto_submitted',
                'submitted_at' => now(),
                'time_taken_minutes' => $this->onlineExam->duration_minutes
            ]);
            
            $this->calculateMarks();
        }
        
        return $this;
    }
}
