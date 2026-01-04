<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'class_name',
        'subject_name',
        'room_number',
        'day_of_week',
        'start_time',
        'end_time',
        'schedule_type',
        'description',
        'notes',
        'effective_from',
        'effective_until',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'effective_from' => 'date',
        'effective_until' => 'date',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Scopes
    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeByDay($query, $day)
    {
        return $query->where('day_of_week', $day);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        $today = now()->toDateString();
        return $query->where(function($q) use ($today) {
            $q->whereNull('effective_from')
              ->orWhere('effective_from', '<=', $today);
        })->where(function($q) use ($today) {
            $q->whereNull('effective_until')
              ->orWhere('effective_until', '>=', $today);
        });
    }

    // Accessors
    public function getTimeSlotAttribute()
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }

    public function getDayNameAttribute()
    {
        return ucfirst($this->day_of_week);
    }

    public function getStatusBadgeAttribute()
    {
        if (!$this->is_active) {
            return '<span class="badge bg-secondary">Inactive</span>';
        }

        $today = now()->toDateString();
        if ($this->effective_until && $this->effective_until < $today) {
            return '<span class="badge bg-warning">Expired</span>';
        }

        if ($this->effective_from && $this->effective_from > $today) {
            return '<span class="badge bg-info">Future</span>';
        }

        return '<span class="badge bg-success">Active</span>';
    }

    public function getTypeBadgeAttribute()
    {
        $colors = [
            'regular' => 'primary',
            'substitute' => 'warning',
            'special' => 'info',
        ];

        $color = $colors[$this->schedule_type] ?? 'secondary';
        return '<span class="badge bg-' . $color . '">' . ucfirst($this->schedule_type) . '</span>';
    }

    // Methods
    public function isToday()
    {
        return strtolower($this->day_of_week) === strtolower(now()->format('l'));
    }

    public function isCurrentTime()
    {
        if (!$this->isToday()) {
            return false;
        }

        $now = now();
        $start = $this->start_time;
        $end = $this->end_time;

        return $now->between($start, $end);
    }

    public function getDuration()
    {
        return $this->start_time->diffInMinutes($this->end_time);
    }

    public function getDurationFormatted()
    {
        $minutes = $this->getDuration();
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        if ($hours > 0) {
            return $hours . 'h ' . $mins . 'm';
        }

        return $mins . 'm';
    }
}
