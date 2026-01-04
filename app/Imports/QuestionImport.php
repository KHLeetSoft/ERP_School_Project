<?php

namespace App\Imports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionImport implements ToModel, WithHeadingRow
{
	public function __construct(private ?int $schoolId = null)
	{
	}

	public function model(array $row)
	{
		if (!isset($row['question_text'])) {
			return null;
		}
		return new Question([
			'school_id' => $this->schoolId,
			'question_category_id' => $row['question_category_id'] ?? null,
			'type' => $row['type'] ?? 'mcq',
			'difficulty' => $row['difficulty'] ?? null,
			'question_text' => $row['question_text'] ?? null,
			'options' => isset($row['options']) ? json_decode($row['options'], true) : null,
			'correct_answer' => $row['correct_answer'] ?? null,
			'explanation' => $row['explanation'] ?? null,
			'marks' => $row['marks'] ?? 1,
			'status' => $row['status'] ?? 'active',
		]);
	}
}



