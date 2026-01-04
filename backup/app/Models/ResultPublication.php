<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultPublication extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'result_announcement_id',
        'publication_title',
        'publication_content',
        'publication_type',
        'status',
        'published_at',
        'expires_at',
        'publication_data',
        'template_settings',
        'pdf_file_path',
        'is_featured',
        'allow_download',
        'require_authentication',
        'access_permissions',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'publication_data' => 'array',
        'template_settings' => 'array',
        'access_permissions' => 'array',
        'is_featured' => 'boolean',
        'allow_download' => 'boolean',
        'require_authentication' => 'boolean',
    ];

    /**
     * Get the school that owns this publication.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the result announcement that this publication belongs to.
     */
    public function resultAnnouncement(): BelongsTo
    {
        return $this->belongsTo(ResultAnnouncement::class);
    }

    /**
     * Get the user who created this publication.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this publication.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope for published publications.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where(function($q) {
                        $q->whereNull('published_at')
                          ->orWhere('published_at', '<=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>=', now());
                    });
    }

    /**
     * Scope for draft publications.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for archived publications.
     */
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    /**
     * Scope for featured publications.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Check if publication is currently active.
     */
    public function isActive(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }

        if ($this->published_at && now() < $this->published_at) {
            return false;
        }

        if ($this->expires_at && now() > $this->expires_at) {
            return false;
        }

        return true;
    }

    /**
     * Get publication type as readable text.
     */
    public function getPublicationTypeTextAttribute(): string
    {
        return str_replace('_', ' ', ucfirst($this->publication_type));
    }

    /**
     * Get publication status as readable text.
     */
    public function getStatusTextAttribute(): string
    {
        return ucfirst($this->status);
    }

    /**
     * Check if user has access to this publication.
     */
    public function userHasAccess(User $user): bool
    {
        if (!$this->require_authentication) {
            return true;
        }

        if (!$this->access_permissions) {
            return true;
        }

        // Check user role permissions
        if (in_array($user->role, $this->access_permissions)) {
            return true;
        }

        // Check specific user permissions
        if (in_array($user->id, $this->access_permissions)) {
            return true;
        }

        return false;
    }

    /**
     * Generate PDF file path.
     */
    public function generatePdfPath(): string
    {
        $filename = 'publication_' . $this->id . '_' . time() . '.pdf';
        return 'publications/' . $filename;
    }

    /**
     * Get download URL for the publication.
     */
    public function getDownloadUrl(): ?string
    {
        if (!$this->allow_download || !$this->pdf_file_path) {
            return null;
        }

        return asset('storage/' . $this->pdf_file_path);
    }
}
