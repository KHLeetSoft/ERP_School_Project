<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveManagement;
use App\Models\Staff;
use App\Models\User;
use Carbon\Carbon;

class LeaveManagementSeeder extends Seeder
{
    public function run(): void
    {
        $schoolId = 1; // Default school ID
        $staff = Staff::where('school_id', $schoolId)->get();
        $adminUser = User::where('school_id', $schoolId)->first();

        if ($staff->isEmpty() || !$adminUser) {
            $this->command->warn('No staff or admin user found. Skipping leave management seeder.');
            return;
        }

        $leaveTypes = ['casual', 'sick', 'annual', 'maternity', 'paternity', 'bereavement', 'study', 'other'];
        $statuses = ['pending', 'approved', 'rejected', 'cancelled'];
        $halfDayTypes = ['morning', 'afternoon'];

        // Generate leaves for the current year and next year
        $currentYear = now()->year;
        $months = range(1, 12);

        foreach ($staff as $member) {
            // Generate 3-8 leaves per staff member
            $leaveCount = rand(3, 8);
            
            for ($i = 0; $i < $leaveCount; $i++) {
                $leaveType = $leaveTypes[array_rand($leaveTypes)];
                $status = $statuses[array_rand($statuses)];
                
                // Generate random dates
                $startMonth = $months[array_rand($months)];
                $startDay = rand(1, 28); // Avoid month-end issues
                $startDate = Carbon::create($currentYear, $startMonth, $startDay);
                
                // End date can be same day or up to 14 days later
                $duration = rand(1, 14);
                $endDate = $startDate->copy()->addDays($duration - 1);
                
                // Ensure dates are not in the past for pending/approved leaves
                if (in_array($status, ['pending', 'approved']) && $startDate->isPast()) {
                    $startDate = now()->addDays(rand(1, 30));
                    $endDate = $startDate->copy()->addDays($duration - 1);
                }
                
                // Calculate total days
                $totalDays = $startDate->diffInDays($endDate) + 1;
                
                // Half day logic (only for single day leaves)
                $halfDay = false;
                $halfDayType = null;
                if ($totalDays == 1 && rand(0, 1)) {
                    $halfDay = true;
                    $halfDayType = $halfDayTypes[array_rand($halfDayTypes)];
                    $totalDays = 0.5;
                }
                
                // Generate leave data
                $leaveData = [
                    'school_id' => $schoolId,
                    'staff_id' => $member->id,
                    'leave_type' => $leaveType,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'total_days' => $totalDays,
                    'reason' => $this->generateReason($leaveType),
                    'status' => $status,
                    'half_day' => $halfDay,
                    'half_day_type' => $halfDayType,
                    'emergency_contact' => $this->generateEmergencyContact(),
                    'emergency_contact_phone' => $this->generatePhoneNumber(),
                    'address_during_leave' => $this->generateAddress(),
                    'created_by' => $adminUser->id,
                    'updated_by' => $adminUser->id,
                ];
                
                // Add approval/rejection data based on status
                if ($status === 'approved') {
                    $leaveData['approved_by'] = $adminUser->id;
                    $leaveData['approved_at'] = $startDate->copy()->subDays(rand(1, 7));
                } elseif ($status === 'rejected') {
                    $leaveData['rejected_by'] = $adminUser->id;
                    $leaveData['rejected_at'] = $startDate->copy()->subDays(rand(1, 3));
                    $leaveData['rejection_reason'] = $this->generateRejectionReason();
                }
                
                LeaveManagement::create($leaveData);
            }
        }

        $this->command->info('Leave Management data seeded successfully!');
    }

    private function generateReason($leaveType): string
    {
        $reasons = [
            'casual' => [
                'Personal work',
                'Family function',
                'Medical appointment',
                'Bank work',
                'Personal emergency',
                'Wedding ceremony',
                'Religious function'
            ],
            'sick' => [
                'Fever and cold',
                'Stomach infection',
                'Headache and migraine',
                'Back pain',
                'Dental appointment',
                'Eye checkup',
                'General illness'
            ],
            'annual' => [
                'Family vacation',
                'Personal break',
                'Long weekend trip',
                'Home visit',
                'Rest and relaxation',
                'Personal development',
                'Family time'
            ],
            'maternity' => [
                'Pregnancy and delivery',
                'Post-delivery care',
                'Maternal health',
                'Baby care',
                'Recovery period'
            ],
            'paternity' => [
                'Wife delivery',
                'Newborn care',
                'Family support',
                'Paternal responsibilities'
            ],
            'bereavement' => [
                'Family member passed away',
                'Funeral arrangements',
                'Grieving period',
                'Family support needed'
            ],
            'study' => [
                'Exam preparation',
                'Course completion',
                'Research work',
                'Academic conference',
                'Study leave for higher education'
            ],
            'other' => [
                'Special circumstances',
                'Unforeseen situation',
                'Emergency leave',
                'Special permission',
                'Administrative leave'
            ]
        ];

        $typeReasons = $reasons[$leaveType] ?? $reasons['casual'];
        return $typeReasons[array_rand($typeReasons)];
    }

    private function generateEmergencyContact(): string
    {
        $names = [
            'Rajesh Kumar', 'Priya Sharma', 'Amit Patel', 'Neha Singh',
            'Vikram Verma', 'Anjali Gupta', 'Rahul Mehta', 'Kavita Joshi',
            'Sanjay Malhotra', 'Pooja Reddy', 'Arun Iyer', 'Meera Nair'
        ];

        return $names[array_rand($names)];
    }

    private function generatePhoneNumber(): string
    {
        $prefixes = ['+91', '+91', '+91', '+91'];
        $prefix = $prefixes[array_rand($prefixes)];
        $number = rand(7000000000, 9999999999);
        
        return $prefix . $number;
    }

    private function generateAddress(): string
    {
        $addresses = [
            'Home address - Mumbai, Maharashtra',
            'Relative\'s place - Delhi, NCR',
            'Family home - Bangalore, Karnataka',
            'Emergency accommodation - Pune, Maharashtra',
            'Temporary residence - Hyderabad, Telangana',
            'Family residence - Chennai, Tamil Nadu',
            'Emergency contact address - Kolkata, West Bengal'
        ];

        return $addresses[array_rand($addresses)];
    }

    private function generateRejectionReason(): string
    {
        $reasons = [
            'Insufficient staff coverage during requested period',
            'Leave request submitted too late',
            'Maximum leave quota exceeded',
            'Critical project deadline during requested period',
            'Insufficient documentation provided',
            'Leave request conflicts with mandatory training',
            'Emergency work requirements during requested period',
            'Leave balance insufficient for requested duration'
        ];

        return $reasons[array_rand($reasons)];
    }
}
