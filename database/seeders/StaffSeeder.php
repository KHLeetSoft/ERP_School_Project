<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $schoolId = School::first()?->id ?? 1;
        $userId = User::first()?->id ?? 1;

        $departments = [
            'Administration', 'Academic', 'Finance', 'Human Resources', 
            'Information Technology', 'Marketing', 'Operations', 'Teaching'
        ];

        $designations = [
            'Principal', 'Vice Principal', 'Head of Department', 'Senior Teacher',
            'Teacher', 'Assistant Teacher', 'Administrative Officer', 'Accountant',
            'Clerk', 'Receptionist', 'IT Support', 'Maintenance Staff'
        ];

        $employmentTypes = ['full-time', 'part-time', 'contract'];
        $genders = ['male', 'female'];
        $cities = ['Mumbai', 'Delhi', 'Bangalore', 'Chennai', 'Kolkata', 'Hyderabad', 'Pune', 'Ahmedabad'];
        $states = ['Maharashtra', 'Delhi', 'Karnataka', 'Tamil Nadu', 'West Bengal', 'Telangana', 'Gujarat'];

        $staffData = [];

        // Generate 50 staff members
        for ($i = 1; $i <= 50; $i++) {
            $firstName = $this->getRandomFirstName();
            $lastName = $this->getRandomLastName();
            $department = $departments[array_rand($departments)];
            $designation = $designations[array_rand($designations)];
            $employmentType = $employmentTypes[array_rand($employmentTypes)];
            $gender = $genders[array_rand($genders)];
            $city = $cities[array_rand($cities)];
            $state = $states[array_rand($states)];

            // Generate realistic salary based on designation and department
            $salary = $this->getSalaryForDesignation($designation, $department);

            // Generate joining date (within last 10 years)
            $joiningDate = Carbon::now()->subYears(rand(0, 10))->subDays(rand(0, 365));

            // Generate employee ID
            $employeeId = 'EMP' . date('Y') . str_pad($i, 4, '0', STR_PAD_LEFT);

            $staffData[] = [
                'school_id' => $schoolId,
                'employee_id' => $employeeId,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => strtolower($firstName . '.' . $lastName . rand(1, 999) . '@school.com'),
                'phone' => '+91' . rand(7000000000, 9999999999),
                'date_of_birth' => Carbon::now()->subYears(rand(25, 60))->subDays(rand(0, 365)),
                'gender' => $gender,
                'address' => rand(1, 999) . ', ' . $this->getRandomStreet() . ', ' . $city,
                'city' => $city,
                'state' => $state,
                'country' => 'India',
                'postal_code' => rand(100000, 999999),
                'designation' => $designation,
                'department' => $department,
                'hire_date' => $joiningDate,
                'contract_end_date' => $employmentType === 'contract' ? $joiningDate->copy()->addYears(rand(1, 3)) : null,
                'salary' => $salary,
                'employment_type' => $employmentType,
                'status' => rand(1, 10) <= 8 ? 'active' : 'inactive', // 80% active
                'emergency_contact_name' => $this->getRandomFirstName() . ' ' . $this->getRandomLastName(),
                'emergency_contact_phone' => '+91' . rand(7000000000, 9999999999),
                'emergency_contact_relationship' => $this->getRandomRelationship(),
                'bank_name' => $this->getRandomBankName(),
                'bank_account_number' => rand(1000000000, 9999999999),
                'ifsc_code' => strtoupper(substr($this->getRandomBankName(), 0, 4)) . '00' . rand(1000, 9999),
                'pan_number' => strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1) . rand(100000, 999999) . substr($firstName, 0, 1)),
                'aadhar_number' => rand(100000000000, 999999999999),
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert in chunks to avoid memory issues
        foreach (array_chunk($staffData, 100) as $chunk) {
            Staff::insert($chunk);
        }

        $this->command->info('Staff seeder completed successfully!');
        $this->command->info('Created ' . count($staffData) . ' staff members.');
    }

    private function getRandomFirstName(): string
    {
        $names = [
            'Aarav', 'Aisha', 'Arjun', 'Ananya', 'Aditya', 'Avni', 'Arnav', 'Aaradhya',
            'Vivaan', 'Zara', 'Vihaan', 'Myra', 'Advait', 'Kiara', 'Aarush', 'Riya',
            'Krishna', 'Anaya', 'Shaurya', 'Pari', 'Dhruv', 'Ira', 'Kabir', 'Ahana',
            'Reyansh', 'Aarvi', 'Aryan', 'Diya', 'Vedant', 'Saanvi', 'Ishaan', 'Navya',
            'Arin', 'Aisha', 'Aarav', 'Anika', 'Vivaan', 'Zara', 'Arjun', 'Myra',
            'Aditya', 'Kiara', 'Arnav', 'Riya', 'Krishna', 'Anaya', 'Shaurya', 'Pari'
        ];
        return $names[array_rand($names)];
    }

    private function getRandomLastName(): string
    {
        $names = [
            'Sharma', 'Verma', 'Patel', 'Kumar', 'Singh', 'Gupta', 'Malhotra', 'Kapoor',
            'Joshi', 'Chopra', 'Reddy', 'Nair', 'Iyer', 'Menon', 'Pillai', 'Nayar',
            'Bhat', 'Rao', 'Mehta', 'Desai', 'Chauhan', 'Solanki', 'Thakur', 'Yadav',
            'Kaur', 'Kaur', 'Kaur', 'Kaur', 'Kaur', 'Kaur', 'Kaur', 'Kaur',
            'Shah', 'Tiwari', 'Mishra', 'Pandey', 'Dubey', 'Trivedi', 'Saxena', 'Sinha'
        ];
        return $names[array_rand($names)];
    }

    private function getRandomStreet(): string
    {
        $streets = [
            'Main Street', 'Park Avenue', 'Lake Road', 'Hill Street', 'River View',
            'Garden Lane', 'Sunset Boulevard', 'Ocean Drive', 'Mountain View', 'Valley Road',
            'Forest Lane', 'Beach Road', 'City Center', 'University Road', 'Hospital Road',
            'School Street', 'Market Road', 'Temple Street', 'Church Lane', 'Mosque Road'
        ];
        return $streets[array_rand($streets)];
    }

    private function getRandomBankName(): string
    {
        $banks = [
            'State Bank of India', 'HDFC Bank', 'ICICI Bank', 'Axis Bank', 'Punjab National Bank',
            'Bank of Baroda', 'Canara Bank', 'Union Bank of India', 'Bank of India', 'Central Bank of India',
            'Indian Bank', 'UCO Bank', 'Punjab & Sind Bank', 'Bank of Maharashtra', 'Indian Overseas Bank'
        ];
        return $banks[array_rand($banks)];
    }

    private function getSalaryForDesignation(string $designation, string $department): float
    {
        $baseSalary = match($designation) {
            'Principal' => 80000,
            'Vice Principal' => 60000,
            'Head of Department' => 50000,
            'Senior Teacher' => 40000,
            'Teacher' => 30000,
            'Assistant Teacher' => 25000,
            'Administrative Officer' => 45000,
            'Accountant' => 35000,
            'Clerk' => 20000,
            'Receptionist' => 18000,
            'IT Support' => 32000,
            'Maintenance Staff' => 15000,
            default => 25000
        };

        // Add department bonus
        $departmentBonus = match($department) {
            'Administration' => 1.2,
            'Academic' => 1.1,
            'Finance' => 1.15,
            'Human Resources' => 1.1,
            'Information Technology' => 1.25,
            'Marketing' => 1.2,
            'Operations' => 1.1,
            'Teaching' => 1.1,
            default => 1.0
        };

        return round($baseSalary * $departmentBonus * (0.9 + (rand(0, 20) / 100)), 2);
    }

    private function getRandomRelationship(): string
    {
        $relationships = [
            'Spouse', 'Parent', 'Sibling', 'Child', 'Friend', 'Relative', 'Guardian'
        ];
        return $relationships[array_rand($relationships)];
    }
}
