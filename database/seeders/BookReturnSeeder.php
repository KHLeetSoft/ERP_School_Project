<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookIssue;
use App\Models\BookReturn;
use Carbon\Carbon;

class BookReturnSeeder extends Seeder
{
	public function run(): void
	{
		$issues = BookIssue::whereNull('returned_at')->limit(5)->get();
		foreach ($issues as $issue) {
			$returnedAt = Carbon::now()->subDays(rand(0,2));
			BookReturn::create([
				'school_id' => $issue->school_id,
				'book_issue_id' => $issue->id,
				'book_id' => $issue->book_id,
				'student_id' => $issue->student_id,
				'returned_at' => $returnedAt,
				'condition' => 'Good',
				'fine_paid' => $issue->fine_amount ?? 0,
				'remarks' => 'Seeded return',
				'received_by' => null,
			]);
			$issue->update([
				'returned_at' => $returnedAt,
				'returned_by' => null,
				'status' => 'returned',
			]);
		}
	}
}


