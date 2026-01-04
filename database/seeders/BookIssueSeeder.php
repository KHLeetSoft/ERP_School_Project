<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookIssue;
use App\Models\Book;
use App\Models\Student;
use Carbon\Carbon;


class BookIssueSeeder extends Seeder
{
	public function run(): void
	{
		// Pull students from the students table (FK target)
		$students = Student::inRandomOrder()->take(10)->get();
		if ($students->isEmpty()) {
			// Fallback: create one minimal student if none exist
			$students = collect([
				Student::create([
					'school_id' => 1,
					'class_section_id' => null,
					'admission_no' => 'ADM-SEED-001',
					'roll_no' => '01',
					'first_name' => 'Seed',
					'last_name' => 'Student',
					'gender' => 'Male',
					'date_of_birth' => now()->subYears(12)->toDateString(),
					'phone' => null,
					'email' => null,
					'address' => null,
					'status' => 'active',
					'created_by' => null,
					'updated_by' => null,
				])
			]);
		}
		$books = Book::get();
		if ($students->isEmpty() || $books->isEmpty()) {
			return;
		}

		$booksBySchoolId = $books->groupBy('school_id');

		foreach ($students as $student) {
			$schoolBooks = $booksBySchoolId->get($student->school_id, collect());
			$book = ($schoolBooks->isNotEmpty() ? $schoolBooks : $books)->random();

			$scenario = rand(1, 100); // 1-40 issued, 41-70 overdue, 71-100 returned
			$issuedAt = Carbon::now()->subDays(rand(1, 10))->setMinute(0)->setSecond(0);
			$dueDate = (clone $issuedAt)->addDays(7);
			$returnedAt = null;
			$status = 'issued';
			$fine = 0.0;

			if ($scenario > 40 && $scenario <= 70) { // overdue
				$dueDate = Carbon::now()->subDays(rand(1, 5));
				$status = 'overdue';
				$fine = max(0, $dueDate->diffInDays(Carbon::now(), false)) * 2.0;
			} elseif ($scenario > 70) { // returned
				$returnedAt = Carbon::now()->subDays(rand(0, 3));
				$status = 'returned';
				$fine = 0.0;
			}

			BookIssue::create([
				'school_id' => $student->school_id,
				'book_id' => $book->id,
				'student_id' => $student->id,
				'issued_at' => $issuedAt,
				'due_date' => $dueDate,
				'returned_at' => $returnedAt,
				'status' => $status,
				'fine_amount' => $fine,
				'notes' => 'Seeded issue record',
				'issued_by' => null,
				'returned_by' => null,
			]);
		}
	}
}


