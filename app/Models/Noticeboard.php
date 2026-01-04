<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Noticeboard extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'type', // announcement, news, event, policy, general
        'priority', // low, medium, high, urgent
        'status', // draft, published, archived
        'start_date',
        'end_date',
        'is_featured',
        'is_pinned',
        'author_id',
        'department_id',
        'target_audience', // all, staff, managers, specific_departments
        'attachments',
        'views_count',
        'is_public',
        'published_at',
        'expires_at'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_pinned' => 'boolean',
        'is_public' => 'boolean',
        'attachments' => 'array',
        'views_count' => 'integer'
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'published_at',
        'expires_at',
        'deleted_at'
    ];

    // Relationships
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function views()
    {
        return $this->hasMany(NoticeboardView::class);
    }

    public function comments()
    {
        return $this->hasMany(NoticeboardComment::class);
    }

    public function likes()
    {
        return $this->hasMany(NoticeboardLike::class);
    }

    public function tags()
    {
        return $this->belongsToMany(NoticeboardTag::class, 'noticeboard_tag');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now())
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'published')
                    ->where('start_date', '<=', now())
                    ->where(function($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', now());
                    });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%");
        });
    }

    // Accessors
    public function getIsActiveAttribute()
    {
        $now = now();
        return $this->status === 'published' &&
               $this->start_date <= $now &&
               ($this->end_date === null || $this->end_date >= $now);
    }

    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at < now();
    }

    public function getDaysUntilExpiryAttribute()
    {
        if (!$this->expires_at) return null;
        return now()->diffInDays($this->expires_at, false);
    }

    public function getPriorityColorAttribute()
    {
        return [
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'danger',
            'urgent' => 'dark'
        ][$this->priority] ?? 'secondary';
    }

    public function getTypeIconAttribute()
    {
        return [
            'announcement' => 'fas fa-bullhorn',
            'news' => 'fas fa-newspaper',
            'event' => 'fas fa-calendar-alt',
            'policy' => 'fas fa-file-contract',
            'general' => 'fas fa-info-circle'
        ][$this->type] ?? 'fas fa-info-circle';
    }

    // Methods
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function markAsRead($userId)
    {
        $this->views()->updateOrCreate(
            ['user_id' => $userId],
            ['viewed_at' => now()]
        );
    }

    public function isReadBy($userId)
    {
        return $this->views()->where('user_id', $userId)->exists();
    }

    public function togglePin()
    {
        $this->update(['is_pinned' => !$this->is_pinned]);
        return $this->is_pinned;
    }

    public function toggleFeature()
    {
        $this->update(['is_featured' => !$this->is_featured]);
        return $this->is_featured;
    }

    public function publish()
    {
        $this->update([
            'status' => 'published',
            'published_at' => now()
        ]);
    }

    public function archive()
    {
        $this->update(['status' => 'archived']);
    }

    public function duplicate()
    {
        $newNotice = $this->replicate();
        $newNotice->title = $this->title . ' (Copy)';
        $newNotice->status = 'draft';
        $newNotice->published_at = null;
        $newNotice->views_count = 0;
        $newNotice->save();

        return $newNotice;
    }
}
