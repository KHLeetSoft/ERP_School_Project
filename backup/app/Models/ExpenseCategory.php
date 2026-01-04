<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'code',
        'description',
        'color',
        'icon',
        'budget_limit',
        'budget_period',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'budget_limit' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'category', 'name');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getTotalExpensesAttribute()
    {
        return $this->expenses()->sum('amount');
    }

    public function getMonthlyExpensesAttribute()
    {
        return $this->expenses()
            ->whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->sum('amount');
    }

    public function getBudgetUtilizationAttribute()
    {
        if (!$this->budget_limit) return null;
        
        $currentExpenses = $this->getMonthlyExpensesAttribute();
        return round(($currentExpenses / $this->budget_limit) * 100, 2);
    }

    public function getStatusColorAttribute()
    {
        if (!$this->budget_limit) return 'secondary';
        
        $utilization = $this->getBudgetUtilizationAttribute();
        if ($utilization >= 90) return 'danger';
        if ($utilization >= 75) return 'warning';
        return 'success';
    }
}
