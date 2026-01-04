<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentDocument;
use App\Models\StudentDetail;
use Illuminate\Support\Str;

class StudentDocumentsSeeder extends Seeder
{
    public function run(): void
    {
        $students = StudentDetail::all();
        if ($students->isEmpty()) return;

        foreach ($students as $student) {
            $count = rand(1, 2);
            for ($i = 0; $i < $count; $i++) {
                StudentDocument::create([
                    'school_id' => $student->school_id ?? null,
                    'student_id' => $student->id,
                    'document_type' => ['ID Card','Certificate','Report','Other'][array_rand(['ID Card','Certificate','Report','Other'])],
                    'title' => 'Sample Doc ' . strtoupper(Str::random(4)),
                    'original_name' => null,
                    'file_path' => null,
                    'mime_type' => null,
                    'file_size' => null,
                    'status' => 'active',
                    'notes' => 'Seeded document entry',
                ]);
            }
        }
    }
}


