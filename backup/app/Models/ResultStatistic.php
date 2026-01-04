<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'result_announcement_id',
        'title',
        'filters',
        'metrics',
        'generated_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'filters' => 'array',
        'metrics' => 'array',
        'generated_at' => 'datetime',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function resultAnnouncement(): BelongsTo
    {
        return $this->belongsTo(ResultAnnouncement::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}


