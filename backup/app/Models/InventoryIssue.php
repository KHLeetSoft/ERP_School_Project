<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryIssue extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'inventory_item_id',
        'issue_type',
        'title',
        'description',
        'priority',
        'status',
        'quantity_affected',
        'estimated_cost',
        'issue_date',
        'resolved_date',
        'resolution_notes',
        'reported_by',
        'assigned_to',
        'location',
        'attachments',
        'is_active'
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'quantity_affected' => 'integer',
        'issue_date' => 'date',
        'resolved_date' => 'date',
        'attachments' => 'array',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('issue_type', $type);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getIsOverdueAttribute()
    {
        if ($this->status === 'closed' || $this->status === 'resolved') {
            return false;
        }

        $daysSinceIssue = $this->issue_date->diffInDays(now());
        
        switch ($this->priority) {
            case 'critical':
                return $daysSinceIssue > 1;
            case 'high':
                return $daysSinceIssue > 3;
            case 'medium':
                return $daysSinceIssue > 7;
            case 'low':
                return $daysSinceIssue > 14;
            default:
                return false;
        }
    }

    public function getDaysOpenAttribute()
    {
        if ($this->status === 'closed' || $this->status === 'resolved') {
            return $this->issue_date->diffInDays($this->resolved_date);
        }

        return $this->issue_date->diffInDays(now());
    }

    // Constants
    const ISSUE_TYPES = [
        'damaged' => 'Damaged',
        'lost' => 'Lost',
        'stolen' => 'Stolen',
        'maintenance' => 'Maintenance Required',
        'other' => 'Other'
    ];

    const PRIORITIES = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'critical' => 'Critical'
    ];

    const STATUSES = [
        'open' => 'Open',
        'in_progress' => 'In Progress',
        'resolved' => 'Resolved',
        'closed' => 'Closed'
    ];
}
