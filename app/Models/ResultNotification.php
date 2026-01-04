<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'result_announcement_id',
        'title',
        'message',
        'status',
        'scheduled_at',
        'sent_at',
        'target_audience',
        'channels',
        'stats',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'target_audience' => 'array',
        'channels' => 'array',
        'stats' => 'array',
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
