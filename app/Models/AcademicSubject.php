<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicSubject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'name',
        'code',
        'type',
        'credit_hours',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'credit_hours' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeForSchool($query, ?int $schoolId)
    {
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }
        return $query;
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function scopeSearch($query, ?string $term)
    {
        if (!empty($term)) {
            $like = "%" . trim($term) . "%";
            $query->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('code', 'like', $like)
                    ->orWhere('type', 'like', $like);
            });
        }
        return $query;
    }
}


