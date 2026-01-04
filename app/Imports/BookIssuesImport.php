<?php

namespace App\Imports;

use App\Models\BookIssue;
use App\Models\Book;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class BookIssuesImport implements ToModel, WithHeadingRow
{
	public function __construct(private ?int $schoolId = null)
	{
	}

	public function model(array $row)
	{
		if (!isset($row['book_id']) || !isset($row['student_id']) || !isset($row['issued_at']) || !isset($row['due_date'])) {
			return null;
		}
		$issuedAt = Carbon::parse($row['issued_at']);
		$dueDate = Carbon::parse($row['due_date']);
		$status = ($row['returned_at'] ?? null) ? 'returned' : ($dueDate->isPast() ? 'overdue' : 'issued');
		return new BookIssue([
			'school_id' => $this->schoolId,
			'book_id' => (int)$row['book_id'],
			'student_id' => (int)$row['student_id'],
			'issued_at' => $issuedAt,
			'due_date' => $dueDate,
			'returned_at' => isset($row['returned_at']) ? Carbon::parse($row['returned_at']) : null,
			'status' => $status,
			'fine_amount' => isset($row['fine_amount']) ? (float)$row['fine_amount'] : 0,
			'notes' => $row['notes'] ?? null,
			'issued_by' => null,
			'returned_by' => null,
		]);
	}
}


