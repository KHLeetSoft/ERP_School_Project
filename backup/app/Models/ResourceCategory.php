<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ResourceCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'name',
        'slug',
        'description',
        'color',
        'icon',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class, 'category_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Accessors
    public function getResourceCountAttribute()
    {
        return $this->resources()->count();
    }

    public function getPublishedResourceCountAttribute()
    {
        return $this->resources()->where('status', 'published')->count();
    }

    public function getFormattedColorAttribute()
    {
        return $this->color ?: '#007bff';
    }

    // Methods
    public function isActive()
    {
        return $this->is_active;
    }

    public function getResourcesCount()
    {
        return $this->resources()->count();
    }

    public function getPublishedResourcesCount()
    {
        return $this->resources()->where('status', 'published')->count();
    }

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // Static methods
    public static function getBySchool($schoolId)
    {
        return static::where('school_id', $schoolId)
                    ->active()
                    ->ordered()
                    ->get();
    }

    public static function getDefaultCategories()
    {
        return [
            [
                'name' => 'Lesson Plans',
                'slug' => 'lesson-plans',
                'description' => 'Detailed lesson plans and teaching guides',
                'color' => '#28a745',
                'icon' => 'bx-book-open',
                'sort_order' => 1,
            ],
            [
                'name' => 'Worksheets',
                'slug' => 'worksheets',
                'description' => 'Printable worksheets and activity sheets',
                'color' => '#ffc107',
                'icon' => 'bx-file-blank',
                'sort_order' => 2,
            ],
            [
                'name' => 'Presentations',
                'slug' => 'presentations',
                'description' => 'PowerPoint presentations and slides',
                'color' => '#17a2b8',
                'icon' => 'bx-slideshow',
                'sort_order' => 3,
            ],
            [
                'name' => 'Videos',
                'slug' => 'videos',
                'description' => 'Educational videos and multimedia content',
                'color' => '#dc3545',
                'icon' => 'bx-video',
                'sort_order' => 4,
            ],
            [
                'name' => 'Documents',
                'slug' => 'documents',
                'description' => 'PDFs, Word documents, and other files',
                'color' => '#6c757d',
                'icon' => 'bx-file',
                'sort_order' => 5,
            ],
            [
                'name' => 'Quizzes & Tests',
                'slug' => 'quizzes-tests',
                'description' => 'Assessment materials and test papers',
                'color' => '#fd7e14',
                'icon' => 'bx-edit',
                'sort_order' => 6,
            ],
            [
                'name' => 'Images & Graphics',
                'slug' => 'images-graphics',
                'description' => 'Educational images, diagrams, and graphics',
                'color' => '#e83e8c',
                'icon' => 'bx-image',
                'sort_order' => 7,
            ],
            [
                'name' => 'Links & References',
                'slug' => 'links-references',
                'description' => 'External links and reference materials',
                'color' => '#20c997',
                'icon' => 'bx-link',
                'sort_order' => 8,
            ],
        ];
    }
}