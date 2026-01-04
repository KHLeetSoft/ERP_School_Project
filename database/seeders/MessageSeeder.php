<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use App\Models\Message;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing users and departments
        $users = User::all();
        $departments = Department::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        // Seed messages
        $this->seedMessages($users, $departments);
        
        $this->command->info('Messages seeded successfully!');
    }

    private function seedMessages($users, $departments)
    {
        $subjects = [
            'Weekly Team Meeting',
            'Project Update - Q4 Goals',
            'New Policy Implementation',
            'Client Meeting Schedule',
            'System Maintenance Notice',
            'Holiday Schedule Update',
            'Training Session Reminder',
            'Budget Review Meeting',
            'Performance Review',
            'Team Building Event',
            'Software Update',
            'Security Alert',
            'Office Renovation',
            'New Employee Onboarding',
            'Quarterly Review',
            'Annual Report',
            'Client Feedback',
            'Product Launch',
            'Market Analysis',
            'Competitor Review',
            'Strategic Planning',
        ];

        $bodies = [
            'Please join us for our weekly team meeting where we will discuss progress and upcoming tasks.',
            'Here is the latest update on our Q4 project goals and milestones.',
            'We are implementing new policies effective next month. Please review the attached documents.',
            'Scheduled client meetings for the upcoming week. Please confirm your availability.',
            'System maintenance will be performed this weekend. Expect some downtime.',
            'Updated holiday schedule for the upcoming year. Mark your calendars.',
            'Reminder: Training session on new software tools next Tuesday.',
            'Budget review meeting scheduled for Friday. Please prepare your reports.',
            'Annual performance reviews are due next month. Schedule your meetings.',
            'Team building event planned for next month. More details to follow.',
            'New software version available. Please update your systems.',
            'Security alert: Please change your passwords and enable 2FA.',
            'Office renovation starting next week. Temporary workspace arrangements.',
            'New employee joining our team. Welcome event details.',
            'Quarterly review meeting scheduled. Prepare your presentations.',
            'Annual report is ready for review. Please provide feedback.',
            'Client feedback received. Action items to be discussed.',
            'Product launch scheduled for next month. Marketing materials needed.',
            'Market analysis report available. Key insights highlighted.',
            'Competitor review completed. Strategic recommendations attached.',
        ];

        $priorities = ['low', 'normal', 'high', 'urgent'];
        $types = ['direct', 'broadcast', 'announcement', 'system'];
        $statuses = ['draft', 'sent', 'read', 'archived'];

        foreach ($users as $user) {
            // Create 25-40 messages per user
            $messageCount = rand(25, 40);
            
            for ($i = 0; $i < $messageCount; $i++) {
                $message = Message::create([
                    'sender_id' => $user->id,
                    'recipient_id' => $users->random()->id,
                    'department_id' => $departments->isNotEmpty() ? $departments->random()->id : null,
                    'subject' => $subjects[array_rand($subjects)],
                    'body' => $bodies[array_rand($bodies)],
                    'priority' => $priorities[array_rand($priorities)],
                    'type' => $types[array_rand($types)],
                    'status' => $statuses[array_rand($statuses)],
                    'is_starred' => rand(0, 1),
                    'is_important' => rand(0, 1),
                    'is_flagged' => rand(0, 1),
                    'is_encrypted' => rand(0, 1),
                    'requires_acknowledgment' => rand(0, 1),
                    'acknowledged_at' => rand(0, 1) ? now() : null,
                    'attachments' => rand(0, 1) ? json_encode(['document.pdf', 'image.jpg', 'spreadsheet.xlsx']) : null,
                    'tags' => rand(0, 1) ? json_encode(['urgent', 'follow-up', 'review']) : null,
                    'metadata' => rand(0, 1) ? json_encode(['category' => 'work', 'priority' => 'high', 'department' => 'IT']) : null,
                    'read_at' => rand(0, 1) ? now() : null,
                    'sent_at' => now()->subDays(rand(0, 60)),
                    'expires_at' => rand(0, 1) ? now()->addDays(rand(1, 90)) : null,
                    'reply_count' => rand(0, 8),
                    'unique_identifier' => 'MSG-' . uniqid(),
                ]);
            }
        }
    }
}
