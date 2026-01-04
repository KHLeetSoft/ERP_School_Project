<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionPaper extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'school_id',
		'title',
		'subject_id',
		'total_marks',
		'duration_mins',
		'generator_payload',
		'status',
	];

	protected $casts = [
		'generator_payload' => 'array',
	];

	public function questions()
	{
		return $this->hasMany(QuestionPaperQuestion::class);
	}
}



