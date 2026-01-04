<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuestionCategory;

class QuestionCategorySeeder extends Seeder
{
	public function run(): void
	{
		$items = [
			['name' => 'Mathematics', 'icon' => 'bx bx-math', 'description' => 'Algebra, Geometry, Arithmetic', 'status' => 'active'],
			['name' => 'Science',     'icon' => 'bx bx-flask', 'description' => 'Physics, Chemistry, Biology', 'status' => 'active'],
			['name' => 'English',     'icon' => 'bx bx-book',  'description' => 'Grammar, Literature',          'status' => 'active'],
			['name' => 'History',     'icon' => 'bx bx-time',  'description' => 'World, Ancient, Modern',       'status' => 'inactive'],
		];
		foreach ($items as $it) {
			QuestionCategory::create(array_merge(['school_id' => 1], $it));
		}
	}
}



