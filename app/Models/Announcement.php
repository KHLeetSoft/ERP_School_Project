<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'priority',
        'status',
        'target_schools',
        'scheduled_at',
        'expires_at',
        'created_by'
    ];

    protected $casts = [
        'target_schools' => 'array',
        'scheduled_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function schools()
    {
        return School::whereIn('id', $this->target_schools ?? []);
    }
}
