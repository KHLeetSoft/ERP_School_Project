<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Librarian;
use Illuminate\Support\Facades\Hash;

class LibrarianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create librarian users
        $librarians = [
            [
                'user' => [
                    'name' => 'Sarah Johnson',
                    'email' => 'sarah.johnson@school.com',
                    'password' => Hash::make('password123'),
                    'role' => 'Librarian',
                ],
                'librarian' => [
                    'employee_id' => 'LIB001',
                    'designation' => 'Head Librarian',
                    'department' => 'Library Services',
                    'phone' => '+1-555-0101',
                    'address' => '123 Library Street, Education City',
                    'date_of_birth' => '1985-03-15',
                    'gender' => 'female',
                    'joining_date' => '2020-01-15',
                    'bio' => 'Experienced librarian with 10+ years in educational institutions. Passionate about promoting literacy and digital resources.',
                    'specializations' => ['Digital Resources', 'Research Methods', 'Information Literacy'],
                    'certifications' => ['Master of Library Science', 'Certified Information Professional'],
                    'emergency_contact' => [
                        'name' => 'John Johnson',
                        'relationship' => 'Spouse',
                        'phone' => '+1-555-0102'
                    ],
                    'status' => 'active',
                ]
            ],
            [
                'user' => [
                    'name' => 'Michael Chen',
                    'email' => 'michael.chen@school.com',
                    'password' => Hash::make('password123'),
                    'role' => 'Librarian',
                ],
                'librarian' => [
                    'employee_id' => 'LIB002',
                    'designation' => 'Assistant Librarian',
                    'department' => 'Library Services',
                    'phone' => '+1-555-0103',
                    'address' => '456 Book Avenue, Education City',
                    'date_of_birth' => '1990-07-22',
                    'gender' => 'male',
                    'joining_date' => '2021-06-01',
                    'bio' => 'Tech-savvy librarian specializing in digital cataloging and modern library management systems.',
                    'specializations' => ['Digital Cataloging', 'Library Management Systems', 'Technical Services'],
                    'certifications' => ['Bachelor of Library Science', 'Digital Library Specialist'],
                    'emergency_contact' => [
                        'name' => 'Lisa Chen',
                        'relationship' => 'Sister',
                        'phone' => '+1-555-0104'
                    ],
                    'status' => 'active',
                ]
            ],
            [
                'user' => [
                    'name' => 'Emily Rodriguez',
                    'email' => 'emily.rodriguez@school.com',
                    'password' => Hash::make('password123'),
                    'role' => 'Librarian',
                ],
                'librarian' => [
                    'employee_id' => 'LIB003',
                    'designation' => 'Reference Librarian',
                    'department' => 'Library Services',
                    'phone' => '+1-555-0105',
                    'address' => '789 Knowledge Lane, Education City',
                    'date_of_birth' => '1988-11-08',
                    'gender' => 'female',
                    'joining_date' => '2019-09-15',
                    'bio' => 'Reference specialist with expertise in academic research and student support services.',
                    'specializations' => ['Reference Services', 'Academic Research', 'Student Support'],
                    'certifications' => ['Master of Library Science', 'Academic Librarian Certification'],
                    'emergency_contact' => [
                        'name' => 'Carlos Rodriguez',
                        'relationship' => 'Brother',
                        'phone' => '+1-555-0106'
                    ],
                    'status' => 'active',
                ]
            ]
        ];

        foreach ($librarians as $librarianData) {
            // Create user account
            $user = User::create($librarianData['user']);

            // Create librarian profile
            $librarian = Librarian::create(array_merge($librarianData['librarian'], ['user_id' => $user->id]));
        }
    }
}