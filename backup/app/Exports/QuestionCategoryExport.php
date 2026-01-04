<?php

namespace App\Exports;

use App\Models\QuestionCategory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QuestionCategoryExport implements FromCollection, WithHeadings
{
	public function __construct(private ?int $schoolId = null)
	{
	}

	public function collection()
	{
		return QuestionCategory::select('name','description','icon','status')
			->when($this->schoolId, fn($q)=>$q->where('school_id', $this->schoolId))
			->orderBy('name')
			->get();
	}

	public function headings(): array
	{
		return ['name','description','icon','status'];
	}
}



