<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Newsletter;
use App\Models\NewsletterTemplate;
use App\Models\School;
use App\Models\User;

class NewsletterSeeder extends Seeder
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
            $this->command->warn('School or Admin user not found. Skipping NewsletterSeeder.');
            return;
        }

        // Get newsletter templates
        $defaultTemplate = NewsletterTemplate::where('is_default', true)->first();
        $announcementTemplate = NewsletterTemplate::where('category', 'announcement')->first();
        $eventTemplate = NewsletterTemplate::where('category', 'event')->first();
        $educationalTemplate = NewsletterTemplate::where('category', 'update')->first();
        $promotionalTemplate = NewsletterTemplate::where('category', 'news')->first();

        $newsletters = [
            [
                'title' => 'Welcome Back to School - Fall 2024',
                'subject' => 'Welcome Back to School - Fall 2024',
                'content' => $this->getWelcomeBackContent(),
                'template_id' => $defaultTemplate ? $defaultTemplate->id : null,
                'status' => 'sent',
                'sent_at' => now()->subDays(5),
                'total_subscribers' => 150,
                'sent_count' => 150,
                'opened_count' => 120,
                'clicked_count' => 45,
                'bounced_count' => 2,
                'unsubscribed_count' => 1,
                'is_draft' => false,
                'is_featured' => true,
                'category' => 'general',
                'tags' => ['welcome', 'fall', '2024', 'back-to-school'],
                'metadata' => ['season' => 'fall', 'academic_year' => '2024-2025'],
            ],
            [
                'title' => 'Important Announcement: Parent-Teacher Conference',
                'subject' => 'Parent-Teacher Conference - Important Information',
                'content' => $this->getParentTeacherConferenceContent(),
                'template_id' => $announcementTemplate ? $announcementTemplate->id : null,
                'status' => 'sent',
                'sent_at' => now()->subDays(3),
                'total_subscribers' => 150,
                'sent_count' => 150,
                'opened_count' => 135,
                'clicked_count' => 78,
                'bounced_count' => 1,
                'unsubscribed_count' => 0,
                'is_draft' => false,
                'is_featured' => false,
                'category' => 'announcement',
                'tags' => ['parent-teacher', 'conference', 'important'],
                'metadata' => ['event_type' => 'conference', 'target_audience' => 'parents'],
            ],
            [
                'title' => 'Annual Science Fair 2024',
                'subject' => 'Annual Science Fair 2024 - Registration Open',
                'content' => $this->getScienceFairContent(),
                'template_id' => $eventTemplate ? $eventTemplate->id : null,
                'status' => 'scheduled',
                'scheduled_at' => now()->addDays(2),
                'total_subscribers' => 150,
                'sent_count' => 0,
                'opened_count' => 0,
                'clicked_count' => 0,
                'bounced_count' => 0,
                'unsubscribed_count' => 0,
                'is_draft' => false,
                'is_featured' => true,
                'category' => 'event',
                'tags' => ['science-fair', 'annual', 'registration', 'students'],
                'metadata' => ['event_date' => now()->addDays(30), 'registration_deadline' => now()->addDays(15)],
            ],
            [
                'title' => 'Study Tips for Final Exams',
                'subject' => 'Study Tips for Final Exams - Academic Success Guide',
                'content' => $this->getStudyTipsContent(),
                'template_id' => $educationalTemplate ? $educationalTemplate->id : null,
                'status' => 'sent',
                'sent_at' => now()->subDays(7),
                'total_subscribers' => 150,
                'sent_count' => 150,
                'opened_count' => 110,
                'clicked_count' => 65,
                'bounced_count' => 3,
                'unsubscribed_count' => 2,
                'is_draft' => false,
                'is_featured' => false,
                'category' => 'update',
                'tags' => ['study-tips', 'exams', 'academic', 'success'],
                'metadata' => ['content_type' => 'study_guide', 'target_grade' => 'all'],
            ],
            [
                'title' => 'Summer Camp Registration - Early Bird Discount',
                'subject' => 'Summer Camp Registration - 20% Early Bird Discount',
                'content' => $this->getSummerCampContent(),
                'template_id' => $promotionalTemplate ? $promotionalTemplate->id : null,
                'status' => 'draft',
                'scheduled_at' => null,
                'total_subscribers' => 0,
                'sent_count' => 0,
                'opened_count' => 0,
                'clicked_count' => 0,
                'bounced_count' => 0,
                'unsubscribed_count' => 0,
                'is_draft' => true,
                'is_featured' => false,
                'category' => 'news',
                'tags' => ['summer-camp', 'discount', 'early-bird', 'registration'],
                'metadata' => ['discount_percentage' => 20, 'valid_until' => now()->addDays(30)],
            ],
            [
                'title' => 'Monthly Newsletter - December 2024',
                'subject' => 'Monthly Newsletter - December 2024',
                'content' => $this->getMonthlyNewsletterContent(),
                'template_id' => $defaultTemplate ? $defaultTemplate->id : null,
                'status' => 'draft',
                'scheduled_at' => null,
                'total_subscribers' => 0,
                'sent_count' => 0,
                'opened_count' => 0,
                'clicked_count' => 0,
                'bounced_count' => 0,
                'unsubscribed_count' => 0,
                'is_draft' => true,
                'is_featured' => false,
                'category' => 'general',
                'tags' => ['monthly', 'december', '2024', 'updates'],
                'metadata' => ['month' => 'december', 'year' => '2024'],
            ],
            [
                'title' => 'Holiday Break Schedule',
                'subject' => 'Holiday Break Schedule - Important Dates',
                'content' => $this->getHolidayBreakContent(),
                'template_id' => $announcementTemplate ? $announcementTemplate->id : null,
                'status' => 'scheduled',
                'scheduled_at' => now()->addDays(5),
                'total_subscribers' => 150,
                'sent_count' => 0,
                'opened_count' => 0,
                'clicked_count' => 0,
                'bounced_count' => 0,
                'unsubscribed_count' => 0,
                'is_draft' => false,
                'is_featured' => false,
                'category' => 'announcement',
                'tags' => ['holiday', 'break', 'schedule', 'dates'],
                'metadata' => ['break_start' => now()->addDays(20), 'break_end' => now()->addDays(35)],
            ],
        ];

        foreach ($newsletters as $newsletterData) {
            Newsletter::create([
                'school_id' => $school->id,
                'title' => $newsletterData['title'],
                'subject' => $newsletterData['subject'],
                'content' => $newsletterData['content'],
                'template_id' => $newsletterData['template_id'],
                'status' => $newsletterData['status'],
                'scheduled_at' => $newsletterData['scheduled_at'] ?? null,
                'sent_at' => $newsletterData['sent_at'] ?? null,
                'total_subscribers' => $newsletterData['total_subscribers'],
                'sent_count' => $newsletterData['sent_count'],
                'opened_count' => $newsletterData['opened_count'],
                'clicked_count' => $newsletterData['clicked_count'],
                'bounced_count' => $newsletterData['bounced_count'],
                'unsubscribed_count' => $newsletterData['unsubscribed_count'],
                'is_draft' => $newsletterData['is_draft'],
                'is_featured' => $newsletterData['is_featured'],
                'category' => $newsletterData['category'],
                'tags' => $newsletterData['tags'],
                'metadata' => $newsletterData['metadata'],
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
                'sent_by' => $newsletterData['status'] === 'sent' ? $admin->id : null,
            ]);
        }

        $this->command->info('Newsletters seeded successfully!');
    }

    private function getWelcomeBackContent(): string
    {
        return '<h2>Welcome Back to School!</h2>
<p>Dear Students, Parents, and Staff,</p>
<p>We are excited to welcome everyone back for the 2024-2025 academic year! This year promises to be filled with learning, growth, and exciting opportunities.</p>

<h3>Important Dates:</h3>
<ul>
    <li><strong>First Day of Classes:</strong> September 3, 2024</li>
    <li><strong>Back-to-School Night:</strong> September 10, 2024</li>
    <li><strong>Parent-Teacher Conference:</strong> October 15, 2024</li>
</ul>

<h3>What\'s New This Year:</h3>
<ul>
    <li>New STEM Lab facilities</li>
    <li>Enhanced library resources</li>
    <li>Updated sports equipment</li>
    <li>New extracurricular programs</li>
</ul>

<p>We look forward to another successful year of academic excellence!</p>';
    }

    private function getParentTeacherConferenceContent(): string
    {
        return '<h2>Parent-Teacher Conference Announcement</h2>
<p>Dear Parents and Guardians,</p>
<p>We are pleased to announce our annual Parent-Teacher Conference scheduled for next week.</p>

<h3>Conference Details:</h3>
<ul>
    <li><strong>Date:</strong> October 15-17, 2024</li>
    <li><strong>Time:</strong> 3:00 PM - 7:00 PM each day</li>
    <li><strong>Location:</strong> School Gymnasium</li>
</ul>

<h3>How to Schedule:</h3>
<p>Please use our online scheduling system to book your preferred time slot. Each conference session is 15 minutes long.</p>

<h3>What to Expect:</h3>
<ul>
    <li>Academic progress review</li>
    <li>Behavior and attendance discussion</li>
    <li>Goal setting for the next quarter</li>
    <li>Questions and concerns addressed</li>
</ul>

<p>We look forward to meeting with you!</p>';
    }

    private function getScienceFairContent(): string
    {
        return '<h2>Annual Science Fair 2024</h2>
<p>Dear Students and Parents,</p>
<p>Get ready for our most exciting Science Fair yet! This year\'s theme is "Innovation for a Sustainable Future."</p>

<h3>Event Details:</h3>
<ul>
    <li><strong>Date:</strong> November 20, 2024</li>
    <li><strong>Time:</strong> 9:00 AM - 3:00 PM</li>
    <li><strong>Location:</strong> School Auditorium and Gymnasium</li>
</ul>

<h3>Registration Information:</h3>
<ul>
    <li><strong>Registration Deadline:</strong> November 5, 2024</li>
    <li><strong>Project Categories:</strong> Physical Science, Life Science, Earth Science, Engineering</li>
    <li><strong>Team Size:</strong> Individual or groups of 2-3 students</li>
</ul>

<h3>Prizes and Recognition:</h3>
<p>Winners will receive certificates, medals, and special recognition. Top projects may advance to regional competitions.</p>

<p>Don\'t miss this opportunity to showcase your scientific creativity!</p>';
    }

    private function getStudyTipsContent(): string
    {
        return '<h2>Study Tips for Final Exams</h2>
<p>Dear Students,</p>
<p>As we approach final exams, here are some proven study strategies to help you succeed:</p>

<h3>Effective Study Techniques:</h3>
<ul>
    <li><strong>Pomodoro Technique:</strong> Study for 25 minutes, then take a 5-minute break</li>
    <li><strong>Active Recall:</strong> Test yourself instead of just re-reading</li>
    <li><strong>Spaced Repetition:</strong> Review material over multiple days</li>
    <li><strong>Mind Mapping:</strong> Create visual connections between concepts</li>
</ul>

<h3>Study Environment:</h3>
<ul>
    <li>Find a quiet, well-lit space</li>
    <li>Eliminate distractions (phone, TV)</li>
    <li>Have all materials ready</li>
    <li>Take regular breaks</li>
</ul>

<h3>Time Management:</h3>
<p>Create a study schedule that allocates more time to challenging subjects and includes regular review sessions.</p>

<p>Remember: Consistent effort over time is more effective than cramming!</p>';
    }

    private function getSummerCampContent(): string
    {
        return '<h2>Summer Camp Registration - Early Bird Special!</h2>
<p>Dear Parents and Students,</p>
<p>Beat the rush and secure your spot in our exciting summer camp programs with our exclusive early bird discount!</p>

<h3>Early Bird Offer:</h3>
<ul>
    <li><strong>Discount:</strong> 20% off all camp registrations</li>
    <li><strong>Valid Until:</strong> January 31, 2025</li>
    <li><strong>Use Code:</strong> SUMMER2025</li>
</ul>

<h3>Camp Programs Available:</h3>
<ul>
    <li><strong>STEM Camp:</strong> Science, Technology, Engineering, Math</li>
    <li><strong>Arts & Crafts Camp:</strong> Creative expression and artistic skills</li>
    <li><strong>Sports Camp:</strong> Various athletic activities and team building</li>
    <li><strong>Academic Enrichment:</strong> Math, reading, and writing skills</li>
</ul>

<h3>Camp Details:</h3>
<ul>
    <li><strong>Duration:</strong> 8 weeks (June-August)</li>
    <li><strong>Hours:</strong> 9:00 AM - 3:00 PM daily</li>
    <li><strong>Age Groups:</strong> 6-14 years old</li>
    <li><strong>Location:</strong> School campus</li>
</ul>

<p>Don\'t wait - secure your spot today and save big!</p>';
    }

    private function getMonthlyNewsletterContent(): string
    {
        return '<h2>Monthly Newsletter - December 2024</h2>
<p>Dear School Community,</p>
<p>As we wrap up another successful month, here\'s what\'s been happening at our school:</p>

<h3>Academic Highlights:</h3>
<ul>
    <li>Science Olympiad team won 2nd place at regional competition</li>
    <li>Math club achieved perfect scores in state assessment</li>
    <li>Debate team qualified for national tournament</li>
</ul>

<h3>Sports Updates:</h3>
<ul>
    <li>Basketball team reached district finals</li>
    <li>Swimming team broke 3 school records</li>
    <li>Track and field athletes qualified for state meet</li>
</ul>

<h3>Upcoming Events:</h3>
<ul>
    <li>Winter Concert - December 15</li>
    <li>Holiday Assembly - December 20</li>
    <li>Winter Break - December 23 - January 3</li>
</ul>

<h3>Community Spotlight:</h3>
<p>Congratulations to our parent volunteers who organized the successful fall fundraiser, raising over $15,000 for school programs!</p>

<p>Happy Holidays to all!</p>';
    }

    private function getHolidayBreakContent(): string
    {
        return '<h2>Holiday Break Schedule</h2>
<p>Dear Students, Parents, and Staff,</p>
<p>As the holiday season approaches, here are the important dates for our winter break:</p>

<h3>Break Schedule:</h3>
<ul>
    <li><strong>Last Day of Classes:</strong> December 20, 2024</li>
    <li><strong>Holiday Break Begins:</strong> December 23, 2024</li>
    <li><strong>Classes Resume:</strong> January 6, 2025</li>
</ul>

<h3>Important Reminders:</h3>
<ul>
    <li>Complete all assignments before break</li>
    <li>Return library books and materials</li>
    <li>Check school website for updates</li>
    <li>Emergency contact information available on school website</li>
</ul>

<h3>Office Hours During Break:</h3>
<p>The school office will be closed during the break. For emergencies, please contact the district office.</p>

<h3>Happy Holidays!</h3>
<p>We wish everyone a safe, joyful, and restful holiday season. See you in the new year!</p>';
    }
}
