<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class NewsletterTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'name',
        'description',
        'html_content',
        'css_content',
        'thumbnail',
        'category',
        'is_active',
        'is_default',
        'variables',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'variables' => 'array'
    ];

    /**
     * Get the school that owns the template
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the user who created the template
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the template
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get newsletters using this template
     */
    public function newsletters()
    {
        return $this->hasMany(Newsletter::class, 'template_id');
    }

    /**
     * Scope by school
     */
    public function scopeBySchool($query, $schoolId = null)
    {
        $schoolId = $schoolId ?? Auth::user()->school_id;
        return $query->where('school_id', $schoolId);
    }

    /**
     * Scope active templates
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get variables list for display
     */
    public function getVariablesListAttribute()
    {
        if (empty($this->variables)) {
            return 'No variables';
        }
        return implode(', ', $this->variables);
    }

    /**
     * Check if template has specific variable
     */
    public function hasVariable($variable)
    {
        return in_array($variable, $this->variables ?? []);
    }

    /**
     * Get variables with template syntax for display
     */
    public function getVariablesWithSyntaxAttribute()
    {
        if (empty($this->variables)) {
            return [];
        }

        $variablesWithSyntax = [];
        foreach ($this->variables as $variable) {
            $variablesWithSyntax[] = "{{" . $variable . "}}";
        }

        return $variablesWithSyntax;
    }
    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($template) {
            if (Auth::check()) {
                $template->created_by = Auth::id();
                $template->updated_by = Auth::id();
                $template->school_id = Auth::user()->school_id;
            }
        });

        static::updating(function ($template) {
            if (Auth::check()) {
                $template->updated_by = Auth::id();
            }
        });
    }
}
