<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'school_id',
		'question_category_id',
		'type',
		'difficulty',
		'question_text',
		'options',
		'correct_answer',
		'explanation',
		'marks',
		'status',
	];

	protected $casts = [
		'options' => 'array',
	];

	public function category()
	{
		return $this->belongsTo(QuestionCategory::class, 'question_category_id');
	}
}



