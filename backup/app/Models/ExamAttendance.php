<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamAttendance extends Model
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
		'exam_date',
		'subject_name',
		'attendance_status',
		'remarks',
		'status',
	];

	protected $casts = [
		'exam_date' => 'date',
	];

	public function exam()
	{
		return $this->belongsTo(Exam::class);
	}
}



