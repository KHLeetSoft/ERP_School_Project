<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicSyllabus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'subject_id',
        'term',
        'title',
        'description',
        'total_units',
        'completed_units',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'total_units' => 'integer',
        'completed_units' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function scopeForSchool($query, ?int $schoolId)
    {
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }
        return $query;
    }

    public function subject()
    {
        return $this->belongsTo(AcademicSubject::class, 'subject_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function getProgressPercentAttribute(): int
    {
        $total = (int) ($this->total_units ?? 0);
        $done = (int) ($this->completed_units ?? 0);
        if ($total <= 0) {
            return 0;
        }
        return (int) round(($done / $total) * 100);
    }
}


