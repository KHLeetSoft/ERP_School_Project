<?php

namespace App\Exports;

use App\Models\ExamTabulation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExamTabulationExport implements FromCollection, WithHeadings
{
	public function __construct(private ?int $schoolId = null)
	{
	}

	public function collection()
	{
		return ExamTabulation::select('exam_id','class_name','section_name','student_id','student_name','admission_no','roll_no','total_marks','max_total_marks','percentage','grade','result_status','rank','remarks','status')
			->when($this->schoolId, fn($q) => $q->where('school_id', $this->schoolId))
			->orderByDesc('id')->get();
	}

	public function headings(): array
	{
		return ['exam_id','class_name','section_name','student_id','student_name','admission_no','roll_no','total_marks','max_total_marks','percentage','grade','result_status','rank','remarks','status'];
	}
}



