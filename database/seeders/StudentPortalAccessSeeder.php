<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentPortalAccess;
use App\Models\StudentDetail;
use Illuminate\Support\Facades\Hash;

class StudentPortalAccessSeeder extends Seeder
{
    public function run(): void
    {
        $students = StudentDetail::limit(15)->get();
        $counter = 1;
        foreach ($students as $student) {
            $username = 'student'.str_pad((string)$counter, 3, '0', STR_PAD_LEFT);
            StudentPortalAccess::updateOrCreate(
                ['student_id' => $student->id],
                [
                    'school_id' => $student->school_id,
                    'username' => $username,
                    'email' => optional($student->user)->email,
                    'password_hash' => Hash::make('password123'),
                    'is_enabled' => true,
                    'force_password_reset' => false,
                    'notes' => 'Seeded access',
                ]
            );
            $counter++;
        }
    }
}


