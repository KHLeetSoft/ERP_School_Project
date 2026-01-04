<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeboardTag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color', 'description'];

    protected $casts = [
        'color' => 'string'
    ];

    // Relationships
    public function noticeboards()
    {
        return $this->belongsToMany(Noticeboard::class, 'noticeboard_tag');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getTagColorAttribute()
    {
        return $this->color ?: '#6c757d';
    }

    // Methods
    public function getUsageCount()
    {
        return $this->noticeboards()->count();
    }
}
