<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SmsTemplate;
use App\Models\User;

class SmsTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('role_id', 2)->first() ?? User::first(); // role_id 2 is for admin

        $templates = [
            [
                'name' => 'Welcome Message',
                'description' => 'Welcome message for new students and parents',
                'content' => 'Welcome to {{school_name}}! We\'re excited to have {{student_name}} join our school family. Classes begin on {{start_date}}. For any queries, contact us at {{school_phone}}.',
                'variables' => ['school_name', 'student_name', 'start_date', 'school_phone'],
                'category' => 'welcome',
                'is_active' => true,
                'is_default' => true,
                'language' => 'en',
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ],
            [
                'name' => 'Attendance Reminder',
                'description' => 'Reminder for parents about student attendance',
                'content' => 'Dear {{parent_name}}, {{student_name}} was absent on {{date}}. Please provide a written explanation or medical certificate. Contact us if you have any questions.',
                'variables' => ['parent_name', 'student_name', 'date'],
                'category' => 'reminder',
                'is_active' => true,
                'is_default' => true,
                'language' => 'en',
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ],
            [
                'name' => 'Exam Result',
                'description' => 'Notification for exam results',
                'content' => 'Dear {{parent_name}}, {{student_name}} scored {{percentage}}% in {{exam_name}} ({{subject}}). Grade: {{grade}}. Result card will be sent home. Congratulations!',
                'variables' => ['parent_name', 'student_name', 'percentage', 'exam_name', 'subject', 'grade'],
                'category' => 'notification',
                'is_active' => true,
                'is_default' => true,
                'language' => 'en',
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ],
            [
                'name' => 'Fee Reminder',
                'description' => 'Reminder for fee payments',
                'content' => 'Dear {{parent_name}}, fee payment of ₹{{amount}} for {{student_name}} is due on {{due_date}}. Please pay on time to avoid late fees. Thank you.',
                'variables' => ['parent_name', 'amount', 'student_name', 'due_date'],
                'category' => 'reminder',
                'is_active' => true,
                'is_default' => true,
                'language' => 'en',
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ],
            [
                'name' => 'Emergency Alert',
                'description' => 'Emergency notifications and alerts',
                'content' => 'URGENT: {{message}}. School will {{action}}. Please {{instruction}}. For updates, check our website or contact {{contact_number}}.',
                'variables' => ['message', 'action', 'instruction', 'contact_number'],
                'category' => 'alert',
                'is_active' => true,
                'is_default' => true,
                'language' => 'en',
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ],
            [
                'name' => 'Parent-Teacher Meeting',
                'description' => 'Notification for PTM schedules',
                'content' => 'Dear {{parent_name}}, Parent-Teacher Meeting for {{student_name}} is scheduled on {{date}} at {{time}} in {{room}}. Please attend to discuss academic progress.',
                'variables' => ['parent_name', 'student_name', 'date', 'time', 'room'],
                'category' => 'reminder',
                'is_active' => true,
                'is_default' => true,
                'language' => 'en',
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ],
            [
                'name' => 'Transport Update',
                'description' => 'Transport-related notifications',
                'content' => 'Dear {{parent_name}}, {{student_name}}\'s bus {{bus_number}} will be {{delay_status}} by {{delay_time}} due to {{reason}}. Expected arrival: {{expected_time}}.',
                'variables' => ['parent_name', 'student_name', 'bus_number', 'delay_status', 'delay_time', 'reason', 'expected_time'],
                'category' => 'notification',
                'is_active' => true,
                'is_default' => true,
                'language' => 'en',
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ],
            [
                'name' => 'Library Book Due',
                'description' => 'Reminder for library book returns',
                'content' => 'Dear {{student_name}}, the book "{{book_title}}" is due for return on {{due_date}}. Please return it to the library to avoid fines.',
                'variables' => ['student_name', 'book_title', 'due_date'],
                'category' => 'reminder',
                'is_active' => true,
                'is_default' => true,
                'language' => 'en',
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ],
            [
                'name' => 'Birthday Wish',
                'description' => 'Birthday greetings for students',
                'content' => 'Happy Birthday {{student_name}}! 🎉 Wishing you a wonderful day filled with joy and success. May all your dreams come true!',
                'variables' => ['student_name'],
                'category' => 'birthday',
                'is_active' => true,
                'is_default' => true,
                'language' => 'en',
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ],
            [
                'name' => 'Holiday Announcement',
                'description' => 'School holiday notifications',
                'content' => 'Dear Parents, school will remain closed on {{holiday_date}} due to {{holiday_reason}}. Classes will resume on {{resume_date}}. Enjoy the holiday!',
                'variables' => ['holiday_date', 'holiday_reason', 'resume_date'],
                'category' => 'notification',
                'is_active' => true,
                'is_default' => true,
                'language' => 'en',
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ],
            [
                'name' => 'Sports Event',
                'description' => 'Sports and event notifications',
                'content' => 'Dear {{parent_name}}, {{student_name}} is selected for {{event_name}} on {{event_date}} at {{venue}}. Please ensure they bring {{required_items}}.',
                'variables' => ['parent_name', 'student_name', 'event_name', 'event_date', 'venue', 'required_items'],
                'category' => 'notification',
                'is_active' => true,
                'is_default' => true,
                'language' => 'en',
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ],
            [
                'name' => 'Academic Calendar',
                'description' => 'Academic calendar updates',
                'content' => 'Dear Parents, important dates: {{event1}} on {{date1}}, {{event2}} on {{date2}}, {{event3}} on {{date3}}. Mark your calendars!',
                'variables' => ['event1', 'date1', 'event2', 'date2', 'event3', 'date3'],
                'category' => 'notification',
                'is_active' => true,
                'is_default' => true,
                'language' => 'en',
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ],
            [
                'name' => 'Staff Meeting',
                'description' => 'Staff meeting notifications',
                'content' => 'Dear {{staff_name}}, staff meeting is scheduled on {{date}} at {{time}} in {{venue}}. Agenda: {{agenda}}. Attendance is mandatory.',
                'variables' => ['staff_name', 'date', 'time', 'venue', 'agenda'],
                'category' => 'reminder',
                'is_active' => true,
                'is_default' => true,
                'language' => 'en',
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ],
            [
                'name' => 'Maintenance Notice',
                'description' => 'School maintenance notifications',
                'content' => 'Dear Parents, {{facility}} will be under maintenance from {{start_date}} to {{end_date}}. Alternative arrangements: {{alternatives}}. We apologize for the inconvenience.',
                'variables' => ['facility', 'start_date', 'end_date', 'alternatives'],
                'category' => 'notification',
                'is_active' => true,
                'is_default' => true,
                'language' => 'en',
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ],
            [
                'name' => 'Achievement Congratulations',
                'description' => 'Congratulations for achievements',
                'content' => 'Congratulations {{student_name}}! 🎉 You have won {{achievement}} in {{competition}}. We are proud of your success! Keep up the excellent work!',
                'variables' => ['student_name', 'achievement', 'competition'],
                'category' => 'notification',
                'is_active' => true,
                'is_default' => true,
                'language' => 'en',
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ],
            [
                'name' => 'Health Advisory',
                'description' => 'Health and safety notifications',
                'content' => 'Dear Parents, due to {{health_condition}}, please ensure {{student_name}} {{health_instruction}}. Contact school nurse if symptoms persist.',
                'variables' => ['health_condition', 'student_name', 'health_instruction'],
                'category' => 'alert',
                'is_active' => true,
                'is_default' => true,
                'language' => 'en',
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ],
        ];

        foreach ($templates as $template) {
            SmsTemplate::updateOrCreate(
                ['name' => $template['name']],
                $template
            );
        }

        $this->command->info('SMS templates seeded successfully!');
    }
}
