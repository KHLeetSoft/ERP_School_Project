<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'title',
        'author',
        'published_year',
        'isbn',
        'genre',
        'description',
        'stock_quantity',
        'shelf_location',
        'status',
    ];

    protected $casts = [
        'published_year' => 'integer',
        'stock_quantity' => 'integer',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function issues(): HasMany
    {
        return $this->hasMany(BookIssue::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(BookReturn::class);
    }

    public function activeIssues(): HasMany
    {
        return $this->hasMany(BookIssue::class)->where('status', 'issued');
    }

    public function getAvailableQuantityAttribute(): int
    {
        return $this->stock_quantity - $this->activeIssues()->count();
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->available_quantity > 0 && $this->status === 'available';
    }
}


