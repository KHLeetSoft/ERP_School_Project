<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookIssue extends Model
{
	use HasFactory;

	protected $fillable = [
		'school_id',
		'book_id',
		'student_id',
		'issued_at',
		'due_date',
		'returned_at',
		'status',
		'fine_amount',
		'notes',
		'issued_by',
		'returned_by',
	];

	protected $casts = [
		'issued_at' => 'datetime',
		'due_date' => 'datetime',
		'returned_at' => 'datetime',
		'fine_amount' => 'decimal:2',
	];

	public function book(): BelongsTo
	{
		return $this->belongsTo(Book::class);
	}

	public function student(): BelongsTo
	{
		return $this->belongsTo(Student::class);
	}

	public function issuer(): BelongsTo
	{
		return $this->belongsTo(User::class, 'issued_by');
	}

	public function returner(): BelongsTo
	{
		return $this->belongsTo(User::class, 'returned_by');
	}
}


