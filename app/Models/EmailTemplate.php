<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'name',
        'subject',
        'content',
        'variables',
        'category',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
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
     * Scope to get only active templates
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get templates by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to get templates by school
     */
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    /**
     * Get variables list as formatted string
     */
    public function getVariablesListAttribute()
    {
        if (empty($this->variables)) {
            return 'No variables';
        }
        return implode(', ', $this->variablesWithSyntax);
    }

    /**
     * Get category badge class
     */
    public function getCategoryBadgeAttribute()
    {
        $badges = [
            'notification' => 'badge-info',
            'reminder' => 'badge-warning',
            'alert' => 'badge-danger',
            'marketing' => 'badge-success',
            'welcome' => 'badge-primary',
            'general' => 'badge-secondary'
        ];

        return $badges[$this->category] ?? 'badge-secondary';
    }

    /**
     * Check if template has specific variable
     */
    public function hasVariable($variable)
    {
        // Remove template syntax if present
        $cleanVariable = str_replace(['{{', '}}'], '', $variable);
        return in_array($cleanVariable, $this->variables ?? []);
    }

    /**
     * Get template content with variables replaced
     */
    public function getContentWithVariables($variables = [])
    {
        $content = $this->content;
        
        foreach ($variables as $key => $value) {
            $content = str_replace("{{" . $key . "}}", $value, $content);
        }
        
        return $content;
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
}
