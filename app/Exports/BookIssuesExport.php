<?php

namespace App\Exports;

use App\Models\BookIssue;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookIssuesExport implements FromCollection, WithHeadings
{
	public function __construct(private ?int $schoolId = null)
	{
	}

	public function collection()
	{
		return BookIssue::select('id', 'school_id', 'book_id', 'student_id', 'issued_at', 'due_date', 'returned_at', 'status', 'fine_amount', 'notes', 'issued_by', 'returned_by', 'created_at', 'updated_at')
			->when($this->schoolId, function ($q) {
				$q->where('school_id', $this->schoolId);
			})
			->get();
	}

	public function headings(): array
	{
		return ['ID', 'School ID', 'Book ID', 'Student ID', 'Issued At', 'Due Date', 'Returned At', 'Status', 'Fine Amount', 'Notes', 'Issued By', 'Returned By', 'Created At', 'Updated At'];
	}
}


