<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamTabulation extends Model
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
		'total_marks',
		'max_total_marks',
		'percentage',
		'grade',
		'result_status',
		'rank',
		'remarks',
		'status',
	];

	protected $casts = [
		'total_marks' => 'decimal:2',
		'max_total_marks' => 'decimal:2',
		'percentage' => 'decimal:2',
		'rank' => 'integer',
	];

	// Relationships
	public function exam(): BelongsTo
	{
		return $this->belongsTo(Exam::class);
	}

	public function school(): BelongsTo
	{
		return $this->belongsTo(School::class);
	}

	public function student(): BelongsTo
	{
		return $this->belongsTo(User::class, 'student_id');
	}

	public function marks(): HasMany
	{
		return $this->hasMany(ExamMark::class, 'exam_id', 'exam_id')
					->where('student_id', $this->student_id);
	}

	// Scopes
	public function scopeByExam($query, $examId)
	{
		return $query->where('exam_id', $examId);
	}

	public function scopeByClass($query, $className)
	{
		return $query->where('class_name', $className);
	}

	public function scopeBySection($query, $sectionName)
	{
		return $query->where('section_name', $sectionName);
	}

	public function scopeByStudent($query, $studentId)
	{
		return $query->where('student_id', $studentId);
	}

	public function scopePassed($query)
	{
		return $query->where('result_status', 'pass');
	}

	public function scopeFailed($query)
	{
		return $query->where('result_status', 'fail');
	}

	public function scopePublished($query)
	{
		return $query->where('status', 'published');
	}

	public function scopeDraft($query)
	{
		return $query->where('status', 'draft');
	}

	public function scopeTopPerformers($query, $limit = 10)
	{
		return $query->orderBy('percentage', 'desc')->limit($limit);
	}

	public function scopeByRank($query, $rank)
	{
		return $query->where('rank', $rank);
	}

	public function scopeTopRank($query, $limit = 10)
	{
		return $query->orderBy('rank', 'asc')->limit($limit);
	}

	// Accessors
	public function getIsPassedAttribute()
	{
		return $this->result_status === 'pass';
	}

	public function getIsFailedAttribute()
	{
		return $this->result_status === 'fail';
	}

	public function getIsPublishedAttribute()
	{
		return $this->status === 'published';
	}

	public function getFormattedTotalMarksAttribute()
	{
		return $this->total_marks . ' / ' . $this->max_total_marks;
	}

	public function getFormattedPercentageAttribute()
	{
		return number_format($this->percentage, 2) . '%';
	}

	public function getGradePointAttribute()
	{
		// Calculate grade point based on percentage
		if ($this->percentage >= 90) return 4.0;
		if ($this->percentage >= 80) return 3.5;
		if ($this->percentage >= 70) return 3.0;
		if ($this->percentage >= 60) return 2.5;
		if ($this->percentage >= 50) return 2.0;
		if ($this->percentage >= 40) return 1.5;
		return 0.0;
	}

	public function getGradeDescriptionAttribute()
	{
		$gradeDescriptions = [
			'A+' => 'Excellent',
			'A' => 'Very Good',
			'B+' => 'Good',
			'B' => 'Satisfactory',
			'C+' => 'Average',
			'C' => 'Below Average',
			'D' => 'Poor',
			'F' => 'Fail'
		];

		return $gradeDescriptions[$this->grade] ?? 'Unknown';
	}

	public function getGradeColorAttribute()
	{
		$colors = [
			'A+' => 'success',
			'A' => 'success',
			'B+' => 'info',
			'B' => 'info',
			'C+' => 'warning',
			'C' => 'warning',
			'D' => 'danger',
			'F' => 'danger'
		];

		return $colors[$this->grade] ?? 'secondary';
	}

	public function getRankSuffixAttribute()
	{
		$suffixes = [
			1 => 'st',
			2 => 'nd',
			3 => 'rd'
		];

		$lastDigit = $this->rank % 10;
		$lastTwoDigits = $this->rank % 100;

		if ($lastTwoDigits >= 11 && $lastTwoDigits <= 13) {
			return 'th';
		}

		return $suffixes[$lastDigit] ?? 'th';
	}

	public function getFormattedRankAttribute()
	{
		return $this->rank . $this->rank_suffix;
	}

	// Methods
	public function isPassed()
	{
		return $this->is_passed;
	}

	public function isFailed()
	{
		return $this->is_failed;
	}

	public function isPublished()
	{
		return $this->is_published;
	}

	public function calculateGrade()
	{
		$percentage = $this->percentage;
		
		if ($percentage >= 90) return 'A+';
		if ($percentage >= 80) return 'A';
		if ($percentage >= 70) return 'B+';
		if ($percentage >= 60) return 'B';
		if ($percentage >= 50) return 'C+';
		if ($percentage >= 40) return 'C';
		if ($percentage >= 30) return 'D';
		return 'F';
	}

	public function calculateResultStatus()
	{
		return $this->percentage >= 40 ? 'pass' : 'fail';
	}

	public function calculatePercentage()
	{
		if ($this->max_total_marks > 0) {
			return round(($this->total_marks / $this->max_total_marks) * 100, 2);
		}
		return 0;
	}

	public function updateGradeAndStatus()
	{
		$this->percentage = $this->calculatePercentage();
		$this->grade = $this->calculateGrade();
		$this->result_status = $this->calculateResultStatus();
		$this->save();
	}

	public function getSubjectMarks()
	{
		return $this->marks()->get();
	}

	public function getSubjectWisePercentage()
	{
		$marks = $this->marks()->get();
		$subjectPercentages = [];

		foreach ($marks as $mark) {
			$subjectPercentages[$mark->subject_name] = $mark->percentage;
		}

		return $subjectPercentages;
	}

	public function getSubjectWiseGrades()
	{
		$marks = $this->marks()->get();
		$subjectGrades = [];

		foreach ($marks as $mark) {
			$subjectGrades[$mark->subject_name] = $mark->grade;
		}

		return $subjectGrades;
	}

	public function getSubjectWiseRank()
	{
		$marks = $this->marks()->get();
		$subjectRanks = [];

		foreach ($marks as $mark) {
			$subjectRanks[$mark->subject_name] = $mark->getRankInSubject();
		}

		return $subjectRanks;
	}

	public function getClassRank()
	{
		return static::where('exam_id', $this->exam_id)
					->where('class_name', $this->class_name)
					->where('percentage', '>', $this->percentage)
					->count() + 1;
	}

	public function getSectionRank()
	{
		return static::where('exam_id', $this->exam_id)
					->where('class_name', $this->class_name)
					->where('section_name', $this->section_name)
					->where('percentage', '>', $this->percentage)
					->count() + 1;
	}

	public function getOverallRank()
	{
		return static::where('exam_id', $this->exam_id)
					->where('percentage', '>', $this->percentage)
					->count() + 1;
	}

	public function updateRank()
	{
		$this->rank = $this->getClassRank();
		$this->save();
	}

	public static function updateAllRanks($examId, $className = null)
	{
		$query = static::where('exam_id', $examId);
		
		if ($className) {
			$query->where('class_name', $className);
		}

		$students = $query->orderBy('percentage', 'desc')->get();
		
		foreach ($students as $index => $student) {
			$student->rank = $index + 1;
			$student->save();
		}
	}
}



