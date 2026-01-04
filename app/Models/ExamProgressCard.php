<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamProgressCard extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'school_id',
		'exam_id',
		'class_name',
		'section_name',
		'student_id',
		'student_name',
		'admission_no',
		'roll_no',
		'overall_percentage',
		'overall_grade',
		'overall_result_status',
		'remarks',
		'status',
		'data',
	];

	protected $casts = [
		'data' => 'array',
	];

	public function exam()
	{
		return $this->belongsTo(Exam::class);
	}
}



