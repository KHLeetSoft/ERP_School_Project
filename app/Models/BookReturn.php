<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookReturn extends Model
{
	use HasFactory;

	protected $fillable = [
		'school_id',
		'book_issue_id',
		'book_id',
		'student_id',
		'returned_at',
		'condition',
		'fine_paid',
		'remarks',
		'received_by',
	];

	protected $casts = [
		'returned_at' => 'datetime',
		'fine_paid' => 'decimal:2',
	];

	public function issue(): BelongsTo
	{
		return $this->belongsTo(BookIssue::class, 'book_issue_id');
	}

	public function book(): BelongsTo
	{
		return $this->belongsTo(Book::class);
	}

	public function student(): BelongsTo
	{
		return $this->belongsTo(Student::class);
	}
}


