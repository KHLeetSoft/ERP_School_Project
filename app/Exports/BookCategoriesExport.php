<?php

namespace App\Exports;

use App\Models\BookCategory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookCategoriesExport implements FromCollection, WithHeadings
{
	public function __construct(private ?int $schoolId = null)
	{
	}

	public function collection()
	{
		return BookCategory::select('id', 'school_id', 'name', 'slug', 'description', 'status', 'created_at', 'updated_at')
			->when($this->schoolId, function ($q) {
				$q->where('school_id', $this->schoolId);
			})
			->get();
	}

	public function headings(): array
	{
		return ['ID', 'School ID', 'Name', 'Slug', 'Description', 'Status', 'Created At', 'Updated At'];
	}
}


