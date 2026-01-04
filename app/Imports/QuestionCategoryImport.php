<?php

namespace App\Imports;

use App\Models\QuestionCategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionCategoryImport implements ToModel, WithHeadingRow
{
	public function __construct(private ?int $schoolId = null)
	{
	}

	public function model(array $row)
	{
		if (!isset($row['name'])) {
			return null;
		}
		return new QuestionCategory([
			'school_id' => $this->schoolId,
			'name' => $row['name'],
			'description' => $row['description'] ?? null,
			'icon' => $row['icon'] ?? null,
			'status' => $row['status'] ?? 'active',
		]);
	}
}



