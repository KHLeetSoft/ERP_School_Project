<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AiQuestionGeneration extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'school_id',
		'title',
		'lesson_file_path',
		'prompt_text',
		'num_questions',
		'model_name',
		'temperature',
		'status',
		'generated_questions',
	];

	protected $casts = [
		'generated_questions' => 'array',
		'temperature' => 'float',
		'num_questions' => 'integer',
	];
}



