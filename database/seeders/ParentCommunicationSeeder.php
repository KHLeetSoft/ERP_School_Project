<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParentCommunication;
use App\Models\ParentDetail;
use App\Models\StudentDetail;
use App\Models\User;

class ParentCommunicationSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing data
        $parents = ParentDetail::all();
        $students = StudentDetail::all();
        $admins = User::where('role_id', 2)->get();

        if ($parents->isEmpty() || $admins->isEmpty()) {
            $this->command->warn('No parents or admins found. Skipping ParentCommunication seeding.');
            return;
        }

        $communicationTypes = ['email', 'sms', 'phone', 'meeting', 'letter'];
        $statuses = ['sent', 'delivered', 'read', 'failed'];
        $priorities = ['low', 'normal', 'high', 'urgent'];
        $categories = ['academic', 'behavior', 'attendance', 'fee', 'general'];

        $sampleMessages = [
            'academic' => [
                'Your child has shown excellent progress in mathematics this month.',
                'We would like to discuss your child\'s recent test results.',
                'Your child has been selected for the advanced science program.',
                'There are some concerns about homework completion that we need to address.',
                'Congratulations! Your child has won the academic excellence award.',
            ],
            'behavior' => [
                'We need to discuss some behavioral concerns in class.',
                'Your child has been very helpful and cooperative this week.',
                'There was a minor incident during recess that we should discuss.',
                'Your child has shown great leadership skills in group activities.',
                'We appreciate your child\'s positive attitude in the classroom.',
            ],
            'attendance' => [
                'We noticed your child has been absent for the past few days.',
                'Your child\'s attendance has been excellent this month.',
                'Please provide a medical certificate for recent absences.',
                'We appreciate your child\'s regular attendance.',
                'There are concerns about frequent late arrivals.',
            ],
            'fee' => [
                'This is a reminder about the upcoming fee payment deadline.',
                'Thank you for your prompt fee payment this month.',
                'There are outstanding fees that need to be settled.',
                'Your child\'s scholarship application has been approved.',
                'We offer flexible payment plans for your convenience.',
            ],
            'general' => [
                'Welcome to the new academic year!',
                'Parent-teacher meeting is scheduled for next week.',
                'School will be closed for the upcoming holiday.',
                'Sports day event details and schedule.',
                'Important announcement about school transportation.',
            ],
        ];

        $sampleSubjects = [
            'academic' => ['Academic Progress Report', 'Test Results Discussion', 'Advanced Program Selection', 'Homework Concerns', 'Academic Achievement'],
            'behavior' => ['Behavioral Discussion', 'Positive Behavior Recognition', 'Incident Report', 'Leadership Recognition', 'Classroom Behavior'],
            'attendance' => ['Attendance Concern', 'Excellent Attendance', 'Medical Certificate Required', 'Attendance Recognition', 'Late Arrival Notice'],
            'fee' => ['Fee Payment Reminder', 'Payment Confirmation', 'Outstanding Fees Notice', 'Scholarship Approval', 'Payment Plan Information'],
            'general' => ['New Academic Year Welcome', 'Parent-Teacher Meeting', 'School Holiday Notice', 'Sports Day Information', 'Transportation Update'],
        ];

        for ($i = 0; $i < 50; $i++) {
            $category = $categories[array_rand($categories)];
            $messageIndex = array_rand($sampleMessages[$category]);
            $subjectIndex = array_rand($sampleSubjects[$category]);
            
            $parent = $parents->random();
            $student = $students->random();
            $admin = $admins->random();
            $communicationType = $communicationTypes[array_rand($communicationTypes)];
            $status = $statuses[array_rand($statuses)];
            $priority = $priorities[array_rand($priorities)];
            
            $sentAt = now()->subDays(rand(1, 90))->subHours(rand(0, 23));
            $deliveredAt = $status === 'delivered' || $status === 'read' ? $sentAt->copy()->addMinutes(rand(1, 60)) : null;
            $readAt = $status === 'read' ? $deliveredAt->copy()->addMinutes(rand(5, 120)) : null;
            
            $cost = null;
            if (in_array($communicationType, ['sms', 'phone'])) {
                $cost = rand(1, 50) / 100; // Random cost between 0.01 and 0.50
            }

            $response = null;
            $responseAt = null;
            if (rand(1, 10) > 7) { // 30% chance of having a response
                $response = 'Thank you for the information. We will take necessary action.';
                $responseAt = $readAt ? $readAt->copy()->addHours(rand(1, 24)) : null;
            }

            ParentCommunication::create([
                'parent_detail_id' => $parent->id,
                'student_id' => rand(1, 10) > 7 ? $student->id : null, // 30% chance of linking to student
                'admin_id' => $admin->id,
                'communication_type' => $communicationType,
                'subject' => $sampleSubjects[$category][$subjectIndex],
                'message' => $sampleMessages[$category][$messageIndex],
                'status' => $status,
                'sent_at' => $sentAt,
                'delivered_at' => $deliveredAt,
                'read_at' => $readAt,
                'priority' => $priority,
                'category' => $category,
                'response' => $response,
                'response_at' => $responseAt,
                'communication_channel' => $this->getChannelByType($communicationType),
                'cost' => $cost,
                'notes' => rand(1, 10) > 8 ? 'Additional notes for this communication.' : null,
                'created_at' => $sentAt,
                'updated_at' => $responseAt ?? $readAt ?? $deliveredAt ?? $sentAt,
            ]);
        }

        $this->command->info('Parent Communication records seeded successfully!');
    }

    private function getChannelByType($type)
    {
        $channels = [
            'email' => ['Gmail', 'Outlook', 'School Email'],
            'sms' => ['Twilio', 'Nexmo', 'School SMS Gateway'],
            'phone' => ['Landline', 'Mobile', 'School Phone System'],
            'meeting' => ['In-Person', 'Video Call', 'Conference Room'],
            'letter' => ['Postal Mail', 'Hand Delivered', 'School Courier'],
        ];

        $typeChannels = $channels[$type] ?? ['General'];
        return $typeChannels[array_rand($typeChannels)];
    }
}
