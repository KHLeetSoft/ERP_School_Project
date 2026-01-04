<?php

namespace App\Exports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QuestionExport implements FromCollection, WithHeadings
{
	public function __construct(private ?int $schoolId = null)
	{
	}

	public function collection()
	{
		return Question::select('question_category_id','type','difficulty','question_text','options','correct_answer','explanation','marks','status')
			->when($this->schoolId, fn($q)=>$q->where('school_id', $this->schoolId))
			->orderByDesc('id')->get();
	}

	public function headings(): array
	{
		return ['question_category_id','type','difficulty','question_text','options','correct_answer','explanation','marks','status'];
	}
}



