<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionPaperQuestion extends Model
{
	use HasFactory;

	protected $fillable = [
		'question_paper_id',
		'question_id',
		'marks',
		'ordering',
	];

	public function paper()
	{
		return $this->belongsTo(QuestionPaper::class, 'question_paper_id');
	}

	public function question()
	{
		return $this->belongsTo(Question::class);
	}
}



