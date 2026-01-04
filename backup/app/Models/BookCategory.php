<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BookCategory extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'school_id',
		'name',
		'slug',
		'description',
		'status',
	];

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

	public function school(): BelongsTo
	{
		return $this->belongsTo(School::class);
	}

	public function books(): HasMany
	{
		return $this->hasMany(Book::class, 'genre', 'name');
	}

	public function getActiveBooksCountAttribute(): int
	{
		return $this->books()->where('status', 'available')->count();
	}
}


