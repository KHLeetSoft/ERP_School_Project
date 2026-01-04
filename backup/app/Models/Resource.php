<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Resource extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'teacher_id',
        'category_id',
        'title',
        'slug',
        'description',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'file_extension',
        'external_url',
        'content',
        'metadata',
        'visibility',
        'is_featured',
        'is_pinned',
        'download_count',
        'view_count',
        'rating',
        'rating_count',
        'status',
        'published_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_featured' => 'boolean',
        'is_pinned' => 'boolean',
        'download_count' => 'integer',
        'view_count' => 'integer',
        'rating' => 'decimal:2',
        'rating_count' => 'integer',
        'published_at' => 'datetime',
    ];

    // Relationships
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ResourceCategory::class, 'category_id');
    }

    // Scopes
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    public function scopePrivate($query)
    {
        return $query->where('visibility', 'private');
    }

    public function scopeShared($query)
    {
        return $query->where('visibility', 'shared');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%");
        });
    }

    // Accessors
    public function getIsPublishedAttribute()
    {
        return $this->status === 'published';
    }

    public function getIsDraftAttribute()
    {
        return $this->status === 'draft';
    }

    public function getIsArchivedAttribute()
    {
        return $this->status === 'archived';
    }

    public function getIsPublicAttribute()
    {
        return $this->visibility === 'public';
    }

    public function getIsPrivateAttribute()
    {
        return $this->visibility === 'private';
    }

    public function getIsSharedAttribute()
    {
        return $this->visibility === 'shared';
    }

    public function getFileSizeFormattedAttribute()
    {
        if (!$this->file_size) return null;

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return Storage::url($this->file_path);
        }
        return null;
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->type === 'image' && $this->file_path) {
            return Storage::url($this->file_path);
        }
        return null;
    }

    public function getTypeIconAttribute()
    {
        $icons = [
            'file' => 'bx-file',
            'link' => 'bx-link',
            'text' => 'bx-text',
            'video' => 'bx-video',
            'image' => 'bx-image',
            'document' => 'bx-file-pdf',
            'presentation' => 'bx-slideshow',
            'worksheet' => 'bx-file-blank',
            'quiz' => 'bx-edit',
            'other' => 'bx-file',
        ];

        return $icons[$this->type] ?? 'bx-file';
    }

    public function getTypeColorAttribute()
    {
        $colors = [
            'file' => 'primary',
            'link' => 'info',
            'text' => 'secondary',
            'video' => 'danger',
            'image' => 'success',
            'document' => 'warning',
            'presentation' => 'info',
            'worksheet' => 'warning',
            'quiz' => 'danger',
            'other' => 'secondary',
        ];

        return $colors[$this->type] ?? 'secondary';
    }

    // Methods
    public function isPublished()
    {
        return $this->is_published;
    }

    public function isDraft()
    {
        return $this->is_draft;
    }

    public function isArchived()
    {
        return $this->is_archived;
    }

    public function isPublic()
    {
        return $this->is_public;
    }

    public function isPrivate()
    {
        return $this->is_private;
    }

    public function isShared()
    {
        return $this->is_shared;
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }

    public function updateRating($newRating)
    {
        $totalRating = ($this->rating * $this->rating_count) + $newRating;
        $this->rating_count++;
        $this->rating = $totalRating / $this->rating_count;
        $this->save();
    }

    public function publish()
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function unpublish()
    {
        $this->update([
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function archive()
    {
        $this->update(['status' => 'archived']);
    }

    public function deleteFile()
    {
        if ($this->file_path && Storage::exists($this->file_path)) {
            Storage::delete($this->file_path);
        }
    }

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($resource) {
            if (empty($resource->slug)) {
                $resource->slug = Str::slug($resource->title);
            }
        });

        static::updating(function ($resource) {
            if ($resource->isDirty('title') && empty($resource->slug)) {
                $resource->slug = Str::slug($resource->title);
            }
        });

        static::deleting(function ($resource) {
            $resource->deleteFile();
        });
    }

    // Static methods
    public static function getByTeacher($teacherId, $status = 'published')
    {
        return static::where('teacher_id', $teacherId)
                    ->where('status', $status)
                    ->orderBy('created_at', 'desc')
                    ->get();
    }

    public static function getByCategory($categoryId, $status = 'published')
    {
        return static::where('category_id', $categoryId)
                    ->where('status', $status)
                    ->orderBy('created_at', 'desc')
                    ->get();
    }

    public static function getFeatured($schoolId = null)
    {
        $query = static::featured()->published();
        
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }

    public static function getPopular($schoolId = null, $limit = 10)
    {
        $query = static::published();
        
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }
        
        return $query->orderBy('view_count', 'desc')
                    ->orderBy('download_count', 'desc')
                    ->limit($limit)
                    ->get();
    }

    public static function getRecent($schoolId = null, $limit = 10)
    {
        $query = static::published();
        
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }
        
        return $query->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get();
    }
}