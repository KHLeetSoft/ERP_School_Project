<?php

namespace App\Exports;

use App\Models\BookReturn;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookReturnsExport implements FromCollection, WithHeadings
{
	public function __construct(private ?int $schoolId = null)
	{
	}

	public function collection()
	{
		return BookReturn::select('id','school_id','book_issue_id','book_id','student_id','returned_at','condition','fine_paid','remarks','received_by','created_at','updated_at')
			->when($this->schoolId, fn($q) => $q->where('school_id', $this->schoolId))
			->get();
	}

	public function headings(): array
	{
		return ['ID','School ID','Book Issue ID','Book ID','Student ID','Returned At','Condition','Fine Paid','Remarks','Received By','Created At','Updated At'];
	}
}


