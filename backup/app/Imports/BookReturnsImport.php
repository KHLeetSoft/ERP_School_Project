<?php

namespace App\Imports;

use App\Models\BookReturn;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class BookReturnsImport implements ToModel, WithHeadingRow
{
	public function __construct(private ?int $schoolId = null)
	{
	}

	public function model(array $row)
	{
		if (!isset($row['book_issue_id']) || !isset($row['returned_at'])) { return null; }
		return new BookReturn([
			'school_id' => $this->schoolId,
			'book_issue_id' => (int) $row['book_issue_id'],
			'book_id' => (int) ($row['book_id'] ?? 0),
			'student_id' => (int) ($row['student_id'] ?? 0),
			'returned_at' => Carbon::parse($row['returned_at']),
			'condition' => $row['condition'] ?? null,
			'fine_paid' => isset($row['fine_paid']) ? (float) $row['fine_paid'] : 0,
			'remarks' => $row['remarks'] ?? null,
			'received_by' => null,
		]);
	}
}


