<?php

namespace App\Imports;

use App\Models\BookCategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class BookCategoriesImport implements ToModel, WithHeadingRow
{
	public function __construct(private ?int $schoolId = null)
	{
	}

	public function model(array $row)
	{
		if (!isset($row['name'])) {
			return null;
		}

		return new BookCategory([
			'school_id' => $this->schoolId,
			'name' => $row['name'] ?? null,
			'slug' => $row['slug'] ?? Str::slug($row['name'] ?? ''),
			'description' => $row['description'] ?? null,
			'status' => $row['status'] ?? 'active',
		]);
	}
}


