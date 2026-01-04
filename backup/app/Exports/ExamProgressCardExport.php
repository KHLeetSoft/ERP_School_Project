<?php

namespace App\Exports;

use App\Models\ExamProgressCard;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExamProgressCardExport implements FromCollection, WithHeadings
{
	public function __construct(private ?int $schoolId = null)
	{
	}

	public function collection()
	{
		return ExamProgressCard::select(
			'exam_id','class_name','section_name','student_id','student_name','admission_no','roll_no',
			'overall_percentage','overall_grade','overall_result_status','remarks','status'
		)
			->when($this->schoolId, fn($q) => $q->where('school_id', $this->schoolId))
			->orderByDesc('id')->get();
	}

	public function headings(): array
	{
		return [
			'exam_id','class_name','section_name','student_id','student_name','admission_no','roll_no',
			'overall_percentage','overall_grade','overall_result_status','remarks','status'
		];
	}
}



