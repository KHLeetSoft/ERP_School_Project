<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsletterSubscriber;
use App\Models\School;
use App\Models\User;

class NewsletterSubscriberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first school and admin user
        $school = School::first();
        $admin = User::where('role_id', 2)->first(); // Assuming role_id 2 is admin

        if (!$school || !$admin) {
            $this->command->warn('School or Admin user not found. Skipping NewsletterSubscriberSeeder.');
            return;
        }

        $subscribers = [
            // Students
            [
                'email' => 'john.doe@student.com',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'phone' => '+1234567890',
                'status' => 'active',
                'source' => 'website',
                'subscribed_at' => now()->subDays(30),
                'last_email_sent_at' => now()->subDays(5),
                'email_count' => 8,
                'open_count' => 6,
                'click_count' => 3,
                'bounce_count' => 0,
                'complaint_count' => 0,
                'metadata' => ['grade' => '10th', 'section' => 'A', 'parent_email' => 'parent.doe@email.com'],
            ],
            [
                'email' => 'jane.smith@student.com',
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'phone' => '+1234567891',
                'status' => 'active',
                'source' => 'website',
                'subscribed_at' => now()->subDays(25),
                'last_email_sent_at' => now()->subDays(3),
                'email_count' => 6,
                'open_count' => 5,
                'click_count' => 2,
                'bounce_count' => 0,
                'complaint_count' => 0,
                'metadata' => ['grade' => '11th', 'section' => 'B', 'parent_email' => 'parent.smith@email.com'],
            ],
            [
                'email' => 'mike.johnson@student.com',
                'first_name' => 'Mike',
                'last_name' => 'Johnson',
                'phone' => '+1234567892',
                'status' => 'active',
                'source' => 'website',
                'subscribed_at' => now()->subDays(20),
                'last_email_sent_at' => now()->subDays(1),
                'email_count' => 4,
                'open_count' => 3,
                'click_count' => 1,
                'bounce_count' => 0,
                'complaint_count' => 0,
                'metadata' => ['grade' => '9th', 'section' => 'C', 'parent_email' => 'parent.johnson@email.com'],
            ],

            // Parents
            [
                'email' => 'parent.doe@email.com',
                'first_name' => 'Robert',
                'last_name' => 'Doe',
                'phone' => '+1234567893',
                'status' => 'active',
                'source' => 'website',
                'subscribed_at' => now()->subDays(30),
                'last_email_sent_at' => now()->subDays(5),
                'email_count' => 8,
                'open_count' => 7,
                'click_count' => 4,
                'bounce_count' => 0,
                'complaint_count' => 0,
                'metadata' => ['student_name' => 'John Doe', 'relationship' => 'Father'],
            ],
            [
                'email' => 'parent.smith@email.com',
                'first_name' => 'Mary',
                'last_name' => 'Smith',
                'phone' => '+1234567894',
                'status' => 'active',
                'source' => 'website',
                'subscribed_at' => now()->subDays(25),
                'last_email_sent_at' => now()->subDays(3),
                'email_count' => 6,
                'open_count' => 6,
                'click_count' => 3,
                'bounce_count' => 0,
                'complaint_count' => 0,
                'metadata' => ['student_name' => 'Jane Smith', 'relationship' => 'Mother'],
            ],
            [
                'email' => 'parent.johnson@email.com',
                'first_name' => 'David',
                'last_name' => 'Johnson',
                'phone' => '+1234567895',
                'status' => 'active',
                'source' => 'website',
                'subscribed_at' => now()->subDays(20),
                'last_email_sent_at' => now()->subDays(1),
                'email_count' => 4,
                'open_count' => 4,
                'click_count' => 2,
                'bounce_count' => 0,
                'complaint_count' => 0,
                'metadata' => ['student_name' => 'Mike Johnson', 'relationship' => 'Father'],
            ],

            // Teachers
            [
                'email' => 'teacher.brown@school.com',
                'first_name' => 'Sarah',
                'last_name' => 'Brown',
                'phone' => '+1234567896',
                'status' => 'active',
                'source' => 'admin',
                'subscribed_at' => now()->subDays(35),
                'last_email_sent_at' => now()->subDays(2),
                'email_count' => 10,
                'open_count' => 9,
                'click_count' => 6,
                'bounce_count' => 0,
                'complaint_count' => 0,
                'metadata' => ['subject' => 'Mathematics', 'department' => 'Science', 'experience' => '5 years'],
            ],
            [
                'email' => 'teacher.wilson@school.com',
                'first_name' => 'Michael',
                'last_name' => 'Wilson',
                'phone' => '+1234567897',
                'status' => 'active',
                'source' => 'admin',
                'subscribed_at' => now()->subDays(32),
                'last_email_sent_at' => now()->subDays(4),
                'email_count' => 9,
                'open_count' => 8,
                'click_count' => 5,
                'bounce_count' => 0,
                'complaint_count' => 0,
                'metadata' => ['subject' => 'English', 'department' => 'Languages', 'experience' => '3 years'],
            ],
            [
                'email' => 'teacher.garcia@school.com',
                'first_name' => 'Maria',
                'last_name' => 'Garcia',
                'phone' => '+1234567898',
                'status' => 'active',
                'source' => 'admin',
                'subscribed_at' => now()->subDays(28),
                'last_email_sent_at' => now()->subDays(6),
                'email_count' => 7,
                'open_count' => 6,
                'click_count' => 4,
                'bounce_count' => 0,
                'complaint_count' => 0,
                'metadata' => ['subject' => 'History', 'department' => 'Social Studies', 'experience' => '7 years'],
            ],

            // Alumni
            [
                'email' => 'alumni.chen@email.com',
                'first_name' => 'Jennifer',
                'last_name' => 'Chen',
                'phone' => '+1234567899',
                'status' => 'active',
                'source' => 'website',
                'subscribed_at' => now()->subDays(40),
                'last_email_sent_at' => now()->subDays(7),
                'email_count' => 12,
                'open_count' => 10,
                'click_count' => 7,
                'bounce_count' => 0,
                'complaint_count' => 0,
                'metadata' => ['graduation_year' => '2020', 'current_job' => 'Software Engineer', 'company' => 'Tech Corp'],
            ],
            [
                'email' => 'alumni.rodriguez@email.com',
                'first_name' => 'Carlos',
                'last_name' => 'Rodriguez',
                'phone' => '+1234567900',
                'status' => 'active',
                'source' => 'website',
                'subscribed_at' => now()->subDays(38),
                'last_email_sent_at' => now()->subDays(8),
                'email_count' => 11,
                'open_count' => 9,
                'click_count' => 6,
                'bounce_count' => 0,
                'complaint_count' => 0,
                'metadata' => ['graduation_year' => '2019', 'current_job' => 'Marketing Manager', 'company' => 'Marketing Inc'],
            ],

            // Community Members
            [
                'email' => 'community.martin@email.com',
                'first_name' => 'Lisa',
                'last_name' => 'Martin',
                'phone' => '+1234567901',
                'status' => 'active',
                'source' => 'website',
                'subscribed_at' => now()->subDays(15),
                'last_email_sent_at' => now()->subDays(2),
                'email_count' => 3,
                'open_count' => 2,
                'click_count' => 1,
                'bounce_count' => 0,
                'complaint_count' => 0,
                'metadata' => ['interest' => 'School Events', 'location' => 'Local Community'],
            ],
            [
                'email' => 'community.thompson@email.com',
                'first_name' => 'James',
                'last_name' => 'Thompson',
                'phone' => '+1234567902',
                'status' => 'active',
                'source' => 'website',
                'subscribed_at' => now()->subDays(12),
                'last_email_sent_at' => now()->subDays(1),
                'email_count' => 2,
                'open_count' => 2,
                'click_count' => 1,
                'bounce_count' => 0,
                'complaint_count' => 0,
                'metadata' => ['interest' => 'Educational Programs', 'location' => 'Local Community'],
            ],

            // Unsubscribed subscribers (for testing)
            [
                'email' => 'unsubscribed.user@email.com',
                'first_name' => 'Alex',
                'last_name' => 'Turner',
                'phone' => '+1234567903',
                'status' => 'unsubscribed',
                'source' => 'website',
                'subscribed_at' => now()->subDays(50),
                'unsubscribed_at' => now()->subDays(10),
                'last_email_sent_at' => now()->subDays(15),
                'email_count' => 15,
                'open_count' => 12,
                'click_count' => 8,
                'bounce_count' => 0,
                'complaint_count' => 0,
                'metadata' => ['unsubscribe_reason' => 'Too many emails', 'feedback' => 'Reduce frequency'],
            ],
        ];

        foreach ($subscribers as $subscriberData) {
            NewsletterSubscriber::create([
                'school_id' => $school->id,
                'email' => $subscriberData['email'],
                'first_name' => $subscriberData['first_name'],
                'last_name' => $subscriberData['last_name'],
                'phone' => $subscriberData['phone'],
                'status' => $subscriberData['status'],
                'source' => $subscriberData['source'],
                'subscribed_at' => $subscriberData['subscribed_at'],
                'unsubscribed_at' => $subscriberData['unsubscribed_at'] ?? null,
                'last_email_sent_at' => $subscriberData['last_email_sent_at'],
                'email_count' => $subscriberData['email_count'],
                'open_count' => $subscriberData['open_count'],
                'click_count' => $subscriberData['click_count'],
                'bounce_count' => $subscriberData['bounce_count'],
                'complaint_count' => $subscriberData['complaint_count'],
                'metadata' => $subscriberData['metadata'],
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);
        }

        $this->command->info('Newsletter subscribers seeded successfully!');
    }
}
