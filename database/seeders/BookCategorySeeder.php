<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookCategory;

class BookCategorySeeder extends Seeder
{
	public function run(): void
	{
		$defaults = [
			['name' => 'Fiction', 'slug' => 'fiction'],
			['name' => 'Non-Fiction', 'slug' => 'non-fiction'],
			['name' => 'Science', 'slug' => 'science'],
			['name' => 'History', 'slug' => 'history'],
			['name' => 'Children', 'slug' => 'children'],
		];

		foreach ($defaults as $row) {
			BookCategory::firstOrCreate(
				['slug' => $row['slug']],
				[
					'name' => $row['name'],
					'description' => null,
					'status' => 'active',
				]
			);
		}
	}
}


