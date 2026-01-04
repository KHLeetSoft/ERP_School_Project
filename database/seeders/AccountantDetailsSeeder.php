<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AccountantDetails;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AccountantDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create multiple accountant users with their details
        $accountants = [
            [
                'user' => [
                    'name' => 'Sarah Johnson',
                    'email' => 'sarah.johnson@school.com',
                    'password' => Hash::make('password123'),
                    'role_id' => 8, // Accountant role
                    'status' => true,
                    'school_id' => 1,
                    'admin_id' => 1,
                ],
                'details' => [
                    'phone' => '+1-555-0101',
                    'address' => '123 Main Street, Downtown, City 12345',
                    'qualification' => 'Bachelor of Commerce (B.Com)',
                    'experience_years' => 5,
                    'salary' => 45000.00,
                    'joining_date' => Carbon::now()->subYears(2),
                    'status' => 'active',
                    'bank_account' => '1234567890',
                    'ifsc_code' => 'SBIN0001234',
                    'pan_number' => 'ABCDE1234F',
                    'aadhar_number' => '123456789012',
                    'emergency_contact' => 'John Johnson',
                    'emergency_phone' => '+1-555-0102',
                ]
            ],
            [
                'user' => [
                    'name' => 'Michael Chen',
                    'email' => 'michael.chen@school.com',
                    'password' => Hash::make('password123'),
                    'role_id' => 8, // Accountant role
                    'status' => true,
                    'school_id' => 1,
                    'admin_id' => 1,
                ],
                'details' => [
                    'phone' => '+1-555-0103',
                    'address' => '456 Oak Avenue, Midtown, City 12346',
                    'qualification' => 'Master of Business Administration (MBA)',
                    'experience_years' => 8,
                    'salary' => 55000.00,
                    'joining_date' => Carbon::now()->subYears(3),
                    'status' => 'active',
                    'bank_account' => '2345678901',
                    'ifsc_code' => 'HDFC0002345',
                    'pan_number' => 'FGHIJ5678K',
                    'aadhar_number' => '234567890123',
                    'emergency_contact' => 'Lisa Chen',
                    'emergency_phone' => '+1-555-0104',
                ]
            ],
            [
                'user' => [
                    'name' => 'Emily Rodriguez',
                    'email' => 'emily.rodriguez@school.com',
                    'password' => Hash::make('password123'),
                    'role_id' => 8, // Accountant role
                    'status' => true,
                    'school_id' => 1,
                    'admin_id' => 1,
                ],
                'details' => [
                    'phone' => '+1-555-0105',
                    'address' => '789 Pine Street, Uptown, City 12347',
                    'qualification' => 'Certified Public Accountant (CPA)',
                    'experience_years' => 3,
                    'salary' => 42000.00,
                    'joining_date' => Carbon::now()->subMonths(8),
                    'status' => 'active',
                    'bank_account' => '3456789012',
                    'ifsc_code' => 'ICICI0003456',
                    'pan_number' => 'KLMNO9012P',
                    'aadhar_number' => '345678901234',
                    'emergency_contact' => 'Carlos Rodriguez',
                    'emergency_phone' => '+1-555-0106',
                ]
            ],
            [
                'user' => [
                    'name' => 'David Thompson',
                    'email' => 'david.thompson@school.com',
                    'password' => Hash::make('password123'),
                    'role_id' => 8, // Accountant role
                    'status' => true,
                    'school_id' => 1,
                    'admin_id' => 1,
                ],
                'details' => [
                    'phone' => '+1-555-0107',
                    'address' => '321 Elm Street, Suburb, City 12348',
                    'qualification' => 'Bachelor of Accounting',
                    'experience_years' => 12,
                    'salary' => 65000.00,
                    'joining_date' => Carbon::now()->subYears(5),
                    'status' => 'active',
                    'bank_account' => '4567890123',
                    'ifsc_code' => 'AXIS0004567',
                    'pan_number' => 'PQRST3456U',
                    'aadhar_number' => '456789012345',
                    'emergency_contact' => 'Mary Thompson',
                    'emergency_phone' => '+1-555-0108',
                ]
            ],
            [
                'user' => [
                    'name' => 'Lisa Wang',
                    'email' => 'lisa.wang@school.com',
                    'password' => Hash::make('password123'),
                    'role_id' => 8, // Accountant role
                    'status' => true,
                    'school_id' => 1,
                    'admin_id' => 1,
                ],
                'details' => [
                    'phone' => '+1-555-0109',
                    'address' => '654 Maple Drive, Westside, City 12349',
                    'qualification' => 'Chartered Accountant (CA)',
                    'experience_years' => 6,
                    'salary' => 48000.00,
                    'joining_date' => Carbon::now()->subMonths(18),
                    'status' => 'active',
                    'bank_account' => '5678901234',
                    'ifsc_code' => 'PNB0005678',
                    'pan_number' => 'UVWXY7890Z',
                    'aadhar_number' => '567890123456',
                    'emergency_contact' => 'James Wang',
                    'emergency_phone' => '+1-555-0110',
                ]
            ],
            [
                'user' => [
                    'name' => 'Robert Kim',
                    'email' => 'robert.kim@school.com',
                    'password' => Hash::make('password123'),
                    'role_id' => 8, // Accountant role
                    'status' => false, // Inactive accountant
                    'school_id' => 1,
                    'admin_id' => 1,
                ],
                'details' => [
                    'phone' => '+1-555-0111',
                    'address' => '987 Cedar Lane, Eastside, City 12350',
                    'qualification' => 'Bachelor of Finance',
                    'experience_years' => 2,
                    'salary' => 38000.00,
                    'joining_date' => Carbon::now()->subMonths(6),
                    'status' => 'inactive',
                    'bank_account' => '6789012345',
                    'ifsc_code' => 'BOI0006789',
                    'pan_number' => 'ZABCD1234E',
                    'aadhar_number' => '678901234567',
                    'emergency_contact' => 'Susan Kim',
                    'emergency_phone' => '+1-555-0112',
                ]
            ],
            [
                'user' => [
                    'name' => 'Jennifer Davis',
                    'email' => 'jennifer.davis@school.com',
                    'password' => Hash::make('password123'),
                    'role_id' => 8, // Accountant role
                    'status' => true,
                    'school_id' => 1,
                    'admin_id' => 1,
                ],
                'details' => [
                    'phone' => '+1-555-0113',
                    'address' => '147 Birch Street, Northside, City 12351',
                    'qualification' => 'Master of Accounting',
                    'experience_years' => 4,
                    'salary' => 44000.00,
                    'joining_date' => Carbon::now()->subMonths(12),
                    'status' => 'active',
                    'bank_account' => '7890123456',
                    'ifsc_code' => 'UNION000789',
                    'pan_number' => 'EFGHI5678J',
                    'aadhar_number' => '789012345678',
                    'emergency_contact' => 'Mark Davis',
                    'emergency_phone' => '+1-555-0114',
                ]
            ],
            [
                'user' => [
                    'name' => 'Christopher Lee',
                    'email' => 'christopher.lee@school.com',
                    'password' => Hash::make('password123'),
                    'role_id' => 8, // Accountant role
                    'status' => true,
                    'school_id' => 1,
                    'admin_id' => 1,
                ],
                'details' => [
                    'phone' => '+1-555-0115',
                    'address' => '258 Spruce Avenue, Southside, City 12352',
                    'qualification' => 'Bachelor of Business Administration (BBA)',
                    'experience_years' => 1,
                    'salary' => 35000.00,
                    'joining_date' => Carbon::now()->subMonths(3),
                    'status' => 'active',
                    'bank_account' => '8901234567',
                    'ifsc_code' => 'CANARA00089',
                    'pan_number' => 'JKLMN9012O',
                    'aadhar_number' => '890123456789',
                    'emergency_contact' => 'Sarah Lee',
                    'emergency_phone' => '+1-555-0116',
                ]
            ]
        ];

        foreach ($accountants as $accountantData) {
            // Create the user
            $user = User::create($accountantData['user']);
            
            // Create the accountant details
            AccountantDetails::create(array_merge(
                ['user_id' => $user->id],
                $accountantData['details']
            ));
        }

        $this->command->info('AccountantDetails seeded successfully!');
    }
}
