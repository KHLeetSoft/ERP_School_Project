<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamGrade extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'grade',
        'grade_point',
        'min_percentage',
        'max_percentage',
        'remark',
        'description',
        'status',
    ];

    protected $casts = [
        'grade_point' => 'decimal:2',
        'min_percentage' => 'decimal:2',
        'max_percentage' => 'decimal:2',
    ];

    // Relationships
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeByGrade($query, $grade)
    {
        return $query->where('grade', $grade);
    }

    public function scopeByGradePoint($query, $gradePoint)
    {
        return $query->where('grade_point', $gradePoint);
    }

    public function scopeByPercentage($query, $percentage)
    {
        return $query->where('min_percentage', '<=', $percentage)
                    ->where('max_percentage', '>=', $percentage);
    }

    // Accessors
    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    public function getGradeRangeAttribute()
    {
        return $this->min_percentage . '% - ' . $this->max_percentage . '%';
    }

    public function getFormattedGradePointAttribute()
    {
        return number_format($this->grade_point, 2);
    }

    // Methods
    public function isActive()
    {
        return $this->is_active;
    }

    public function isInactive()
    {
        return $this->status === 'inactive';
    }

    public function matchesPercentage($percentage)
    {
        return $percentage >= $this->min_percentage && $percentage <= $this->max_percentage;
    }

    public function getGradeForPercentage($percentage)
    {
        return static::active()
                    ->where('min_percentage', '<=', $percentage)
                    ->where('max_percentage', '>=', $percentage)
                    ->first();
    }

    public static function getGradeByPercentage($percentage, $schoolId = null)
    {
        $query = static::active();
        
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }

        return $query->where('min_percentage', '<=', $percentage)
                    ->where('max_percentage', '>=', $percentage)
                    ->first();
    }

    public static function getAllGrades($schoolId = null)
    {
        $query = static::active();
        
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }

        return $query->orderBy('grade_point', 'desc')->get();
    }

    public static function getGradePoints($schoolId = null)
    {
        $query = static::active();
        
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }

        return $query->pluck('grade_point', 'grade')->toArray();
    }

    public function getGradeDescription()
    {
        return $this->description ?: $this->remark;
    }

    public function getGradeColor()
    {
        $colors = [
            'A+' => 'success',
            'A' => 'success',
            'B+' => 'info',
            'B' => 'info',
            'C+' => 'warning',
            'C' => 'warning',
            'D' => 'danger',
            'F' => 'danger'
        ];

        return $colors[$this->grade] ?? 'secondary';
    }

    public function getGradeBadgeClass()
    {
        $classes = [
            'A+' => 'badge-success',
            'A' => 'badge-success',
            'B+' => 'badge-info',
            'B' => 'badge-info',
            'C+' => 'badge-warning',
            'C' => 'badge-warning',
            'D' => 'badge-danger',
            'F' => 'badge-danger'
        ];

        return $classes[$this->grade] ?? 'badge-secondary';
    }
}


