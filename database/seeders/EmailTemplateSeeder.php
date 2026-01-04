<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Models\School;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin user and school
        $admin = User::where('role_id', 2)->first(); // Assuming role_id 2 is admin
        $school = School::first();

        if (!$admin || !$school) {
            $this->command->warn('Admin user or school not found. Skipping email template seeding.');
            return;
        }

        $templates = [
            [
                'name' => 'Student Welcome Email',
                'subject' => 'Welcome to {{school_name}} - Academic Year {{year}}',
                'content' => '<h2>Welcome to {{school_name}}!</h2>
<p>Dear {{student_name}},</p>
<p>We are delighted to welcome you to {{school_name}} for the academic year {{year}}.</p>
<p><strong>Student Details:</strong></p>
<ul>
    <li><strong>Student ID:</strong> {{student_id}}</li>
    <li><strong>Class:</strong> {{student_class}}</li>
    <li><strong>Section:</strong> {{student_section}}</li>
    <li><strong>Roll Number:</strong> {{student_roll_no}}</li>
</ul>
<p>We look forward to supporting your academic journey and helping you achieve your goals.</p>
<p>Best regards,<br>
{{admin_name}}<br>
{{school_name}}</p>',
                'variables' => ['student_name', 'student_id', 'student_class', 'student_section', 'student_roll_no', 'school_name', 'year', 'admin_name'],
                'category' => 'welcome',
                'is_active' => true
            ],
            [
                'name' => 'Parent Notification - Attendance',
                'subject' => 'Attendance Update for {{student_name}} - {{current_date}}',
                'content' => '<h2>Attendance Notification</h2>
<p>Dear {{parent_name}},</p>
<p>This is to inform you about the attendance status of your child <strong>{{student_name}}</strong> for {{current_date}}.</p>
<p><strong>Student Information:</strong></p>
<ul>
    <li><strong>Name:</strong> {{student_name}}</li>
    <li><strong>Class:</strong> {{student_class}}</li>
    <li><strong>Section:</strong> {{student_section}}</li>
    <li><strong>Date:</strong> {{current_date}}</li>
</ul>
<p>If you have any questions or concerns, please don\'t hesitate to contact us.</p>
<p>Best regards,<br>
{{admin_name}}<br>
{{school_name}}<br>
Phone: {{school_phone}}<br>
Email: {{school_email}}</p>',
                'variables' => ['parent_name', 'student_name', 'student_class', 'student_section', 'current_date', 'admin_name', 'school_name', 'school_phone', 'school_email'],
                'category' => 'notification',
                'is_active' => true
            ],
            [
                'name' => 'Exam Schedule Reminder',
                'subject' => 'Upcoming Exam Schedule - {{student_class}} {{student_section}}',
                'content' => '<h2>Exam Schedule Reminder</h2>
<p>Dear {{student_name}},</p>
<p>This is a reminder about your upcoming examinations.</p>
<p><strong>Exam Details:</strong></p>
<ul>
    <li><strong>Class:</strong> {{student_class}}</li>
    <li><strong>Section:</strong> {{student_section}}</li>
    <li><strong>Date:</strong> {{current_date}}</li>
</ul>
<p>Please ensure you:</p>
<ul>
    <li>Bring all necessary stationery</li>
    <li>Arrive at school 30 minutes before exam time</li>
    <li>Wear proper school uniform</li>
    <li>Bring your student ID card</li>
</ul>
<p>Good luck with your exams!</p>
<p>Best regards,<br>
{{admin_name}}<br>
{{school_name}}</p>',
                'variables' => ['student_name', 'student_class', 'student_section', 'current_date', 'admin_name', 'school_name'],
                'category' => 'reminder',
                'is_active' => true
            ],
            [
                'name' => 'Fee Payment Reminder',
                'subject' => 'Fee Payment Reminder - {{student_name}}',
                'content' => '<h2>Fee Payment Reminder</h2>
<p>Dear {{parent_name}},</p>
<p>This is a friendly reminder that the fee payment for <strong>{{student_name}}</strong> is due.</p>
<p><strong>Student Details:</strong></p>
<ul>
    <li><strong>Name:</strong> {{student_name}}</li>
    <li><strong>Class:</strong> {{student_class}}</li>
    <li><strong>Section:</strong> {{student_section}}</li>
    <li><strong>Due Date:</strong> {{current_date}}</li>
</ul>
<p>Please ensure timely payment to avoid any late fees or service disruptions.</p>
<p>For payment queries, please contact our accounts department.</p>
<p>Best regards,<br>
{{admin_name}}<br>
{{school_name}}<br>
Phone: {{school_phone}}<br>
Email: {{school_email}}</p>',
                'variables' => ['parent_name', 'student_name', 'student_class', 'student_section', 'current_date', 'admin_name', 'school_name', 'school_phone', 'school_email'],
                'category' => 'reminder',
                'is_active' => true
            ],
            [
                'name' => 'Staff Meeting Alert',
                'subject' => 'Staff Meeting Alert - {{current_date}}',
                'content' => '<h2>Staff Meeting Alert</h2>
<p>Dear {{staff_name}},</p>
<p>This is to inform you about an important staff meeting scheduled for today.</p>
<p><strong>Meeting Details:</strong></p>
<ul>
    <li><strong>Date:</strong> {{current_date}}</li>
    <li><strong>Time:</strong> {{current_time}}</li>
    <li><strong>Venue:</strong> Conference Room</li>
    <li><strong>Agenda:</strong> Academic Planning & Updates</li>
</ul>
<p>Your attendance is mandatory. Please bring your notepad and any relevant documents.</p>
<p>Best regards,<br>
{{admin_name}}<br>
{{school_name}}</p>',
                'variables' => ['staff_name', 'current_date', 'current_time', 'admin_name', 'school_name'],
                'category' => 'alert',
                'is_active' => true
            ],
            [
                'name' => 'School Event Marketing',
                'subject' => 'Join Us for Annual Sports Day - {{school_name}}',
                'content' => '<h2>Annual Sports Day Celebration</h2>
<p>Dear Parents and Students,</p>
<p>We are excited to invite you to our Annual Sports Day celebration!</p>
<p><strong>Event Details:</strong></p>
<ul>
    <li><strong>Date:</strong> {{current_date}}</li>
    <li><strong>Time:</strong> 9:00 AM - 4:00 PM</li>
    <li><strong>Venue:</strong> School Ground</li>
    <li><strong>Highlights:</strong> Athletics, Team Sports, Cultural Performances</li>
</ul>
<p>This is a wonderful opportunity for students to showcase their talents and for families to come together.</p>
<p>We look forward to your participation!</p>
<p>Best regards,<br>
{{admin_name}}<br>
{{school_name}}<br>
{{school_address}}</p>',
                'variables' => ['current_date', 'admin_name', 'school_name', 'school_address'],
                'category' => 'marketing',
                'is_active' => true
            ],
            [
                'name' => 'General Announcement',
                'subject' => 'Important Announcement - {{school_name}}',
                'content' => '<h2>General Announcement</h2>
<p>Dear Students, Parents, and Staff,</p>
<p>This is a general announcement from {{school_name}}.</p>
<p><strong>Important Information:</strong></p>
<ul>
    <li>School will remain closed on upcoming holidays</li>
    <li>New academic calendar will be shared soon</li>
    <li>Parent-teacher meetings scheduled for next month</li>
</ul>
<p>Please stay updated with our school app and website for the latest information.</p>
<p>Best regards,<br>
{{admin_name}}<br>
{{school_name}}<br>
{{school_phone}}<br>
{{school_email}}</p>',
                'variables' => ['school_name', 'admin_name', 'school_phone', 'school_email'],
                'category' => 'general',
                'is_active' => true
            ],
            [
                'name' => 'Emergency Alert',
                'subject' => 'URGENT: Emergency Alert - {{school_name}}',
                'content' => '<h2 style="color: red;">EMERGENCY ALERT</h2>
<p>Dear Parents and Guardians,</p>
<p>This is an emergency alert from {{school_name}}.</p>
<p><strong>Emergency Information:</strong></p>
<ul>
    <li>School will be closed today due to emergency</li>
    <li>All students are safe and accounted for</li>
    <li>Further updates will be provided</li>
</ul>
<p>Please do not send students to school until further notice.</p>
<p>For emergency contact:<br>
{{school_phone}}<br>
{{school_email}}</p>
<p>Best regards,<br>
{{admin_name}}<br>
{{school_name}}</p>',
                'variables' => ['school_name', 'school_phone', 'school_email', 'admin_name'],
                'category' => 'alert',
                'is_active' => true
            ]
        ];

        foreach ($templates as $templateData) {
            EmailTemplate::create([
                'school_id' => $school->id,
                'name' => $templateData['name'],
                'subject' => $templateData['subject'],
                'content' => $templateData['content'],
                'variables' => $templateData['variables'],
                'category' => $templateData['category'],
                'is_active' => $templateData['is_active'],
                'created_by' => $admin->id,
                'updated_by' => $admin->id
            ]);
        }

        $this->command->info('Email templates seeded successfully!');
        $this->command->info('Created ' . count($templates) . ' email templates.');
    }
}
