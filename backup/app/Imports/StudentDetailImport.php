<?php

namespace App\Imports;

use App\Models\StudentDetail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentDetailImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $user = User::firstOrCreate(
            ['email' => $row['email']],
            [
                'name' => $row['name'],
                'password' => Hash::make('password'), // default password
                'role_id' => 6 // student role
            ]
        );
        return new StudentDetail([
            'user_id' => $user->id,
            'school_id' => $row['school_id'] ?? null,
            'class_id' => $row['class_id'] ?? null,
            'section_id' => $row['section_id'] ?? null,
            'roll_no' => $row['roll_no'] ?? null,
            'admission_no' => $row['admission_no'] ?? null,
            'dob' => $row['dob'] ?? null,
            'gender' => $row['gender'] ?? null,
            'blood_group' => $row['blood_group'] ?? null,
            'religion' => $row['religion'] ?? null,
            'nationality' => $row['nationality'] ?? null,
            'category' => $row['category'] ?? null,
            'guardian_name' => $row['guardian_name'] ?? null,
            'guardian_contact' => $row['guardian_contact'] ?? null,
            'address' => $row['address'] ?? null,
            'profile_image' => $row['profile_image'] ?? 'uploads/students/default.png',
        ]);
    }
}
