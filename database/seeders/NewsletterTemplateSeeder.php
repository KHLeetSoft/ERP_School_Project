<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsletterTemplate;
use App\Models\School;
use App\Models\User;

class NewsletterTemplateSeeder extends Seeder
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
            $this->command->warn('School or Admin user not found. Skipping NewsletterTemplateSeeder.');
            return;
        }

        $templates = [
            [
                'name' => 'Default Newsletter',
                'description' => 'A clean and professional newsletter template for general communications',
                'html_content' => $this->getDefaultNewsletterHTML(),
                'css_content' => $this->getDefaultNewsletterCSS(),
                'thumbnail' => 'newsletter-default.jpg',
                'category' => 'general',
                'is_active' => true,
                'is_default' => true,
                'variables' => ['recipient_name', 'school_name', 'current_date', 'unsubscribe_link'],
            ],
            [
                'name' => 'Announcement Template',
                'description' => 'Template for important announcements and updates',
                'html_content' => $this->getAnnouncementHTML(),
                'css_content' => $this->getAnnouncementCSS(),
                'thumbnail' => 'newsletter-announcement.jpg',
                'category' => 'announcement',
                'is_active' => true,
                'is_default' => false,
                'variables' => ['recipient_name', 'announcement_title', 'announcement_content', 'announcement_date', 'school_name'],
            ],
            [
                'name' => 'Event Newsletter',
                'description' => 'Template for event-related newsletters and invitations',
                'html_content' => $this->getEventHTML(),
                'css_content' => $this->getEventCSS(),
                'thumbnail' => 'newsletter-event.jpg',
                'category' => 'event',
                'is_active' => true,
                'is_default' => false,
                'variables' => ['recipient_name', 'event_name', 'event_date', 'event_location', 'event_description', 'rsvp_link'],
            ],
            [
                'name' => 'Educational Content',
                'description' => 'Template for educational newsletters and learning materials',
                'html_content' => $this->getEducationalHTML(),
                'css_content' => $this->getEducationalCSS(),
                'thumbnail' => 'newsletter-educational.jpg',
                'category' => 'update',
                'is_active' => true,
                'is_default' => false,
                'variables' => ['recipient_name', 'topic_title', 'topic_content', 'learning_objectives', 'resources_link'],
            ],
            [
                'name' => 'Promotional Template',
                'description' => 'Template for promotional content and special offers',
                'html_content' => $this->getPromotionalHTML(),
                'css_content' => $this->getPromotionalCSS(),
                'thumbnail' => 'newsletter-promotional.jpg',
                'category' => 'news',
                'is_active' => true,
                'is_default' => false,
                'variables' => ['recipient_name', 'offer_title', 'offer_description', 'discount_code', 'expiry_date', 'cta_link'],
            ],
        ];

        foreach ($templates as $templateData) {
            NewsletterTemplate::create([
                'school_id' => $school->id,
                'name' => $templateData['name'],
                'description' => $templateData['description'],
                'html_content' => $templateData['html_content'],
                'css_content' => $templateData['css_content'],
                'thumbnail' => $templateData['thumbnail'],
                'category' => $templateData['category'],
                'is_active' => $templateData['is_active'],
                'is_default' => $templateData['is_default'],
                'variables' => $templateData['variables'],
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);
        }

        $this->command->info('Newsletter templates seeded successfully!');
    }

    private function getDefaultNewsletterHTML(): string
    {
        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff;">
        <!-- Header -->
        <div style="background-color: #2c3e50; color: white; padding: 20px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">{{ $school_name }}</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px;">Newsletter</p>
        </div>
        
        <!-- Content -->
        <div style="padding: 30px;">
            <p>Dear {{ $recipient_name }},</p>
            
            <div style="margin: 20px 0;">
                {{ $content }}
            </div>
            
            <p>Best regards,<br>{{ $school_name }} Team</p>
        </div>
        
        <!-- Footer -->
        <div style="background-color: #34495e; color: white; padding: 20px; text-align: center; font-size: 12px;">
            <p>© {{ $current_date }} {{ $school_name }}. All rights reserved.</p>
            <p><a href="{{ $unsubscribe_link }}" style="color: #3498db;">Unsubscribe</a></p>
        </div>
    </div>
</body>
</html>';
    }

    private function getDefaultNewsletterCSS(): string
    {
        return 'body { font-family: Arial, sans-serif; }
.newsletter-container { max-width: 600px; margin: 0 auto; }
.header { background-color: #2c3e50; color: white; padding: 20px; text-align: center; }
.content { padding: 30px; }
.footer { background-color: #34495e; color: white; padding: 20px; text-align: center; }';
    }

    private function getAnnouncementHTML(): string
    {
        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $announcement_title }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff;">
        <!-- Header -->
        <div style="background-color: #e74c3c; color: white; padding: 20px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">{{ $school_name }}</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px;">Important Announcement</p>
        </div>
        
        <!-- Content -->
        <div style="padding: 30px;">
            <h2 style="color: #e74c3c;">{{ $announcement_title }}</h2>
            <p style="color: #7f8c8d; font-size: 14px;">{{ $announcement_date }}</p>
            
            <div style="margin: 20px 0; padding: 20px; background-color: #f8f9fa; border-left: 4px solid #e74c3c;">
                {{ $announcement_content }}
            </div>
            
            <p>Best regards,<br>{{ $school_name }} Administration</p>
        </div>
        
        <!-- Footer -->
        <div style="background-color: #34495e; color: white; padding: 20px; text-align: center; font-size: 12px;">
            <p>© {{ $current_date }} {{ $school_name }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>';
    }

    private function getAnnouncementCSS(): string
    {
        return 'body { font-family: Arial, sans-serif; }
.announcement-container { max-width: 600px; margin: 0 auto; }
.header { background-color: #e74c3c; color: white; padding: 20px; text-align: center; }
.content { padding: 30px; }
.announcement-box { margin: 20px 0; padding: 20px; background-color: #f8f9fa; border-left: 4px solid #e74c3c; }
.footer { background-color: #34495e; color: white; padding: 20px; text-align: center; }';
    }

    private function getEventHTML(): string
    {
        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event_name }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff;">
        <!-- Header -->
        <div style="background-color: #3498db; color: white; padding: 20px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">{{ $school_name }}</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px;">Event Invitation</p>
        </div>
        
        <!-- Content -->
        <div style="padding: 30px;">
            <h2 style="color: #3498db;">{{ $event_name }}</h2>
            
            <div style="margin: 20px 0; padding: 20px; background-color: #ecf0f1; border-radius: 8px;">
                <p><strong>Date:</strong> {{ $event_date }}</p>
                <p><strong>Location:</strong> {{ $event_location }}</p>
                <p><strong>Description:</strong></p>
                <p>{{ $event_description }}</p>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $rsvp_link }}" style="background-color: #3498db; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">RSVP Now</a>
            </div>
            
            <p>Best regards,<br>{{ $school_name }} Events Team</p>
        </div>
        
        <!-- Footer -->
        <div style="background-color: #34495e; color: white; padding: 20px; text-align: center; font-size: 12px;">
            <p>© {{ $current_date }} {{ $school_name }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>';
    }

    private function getEventCSS(): string
    {
        return 'body { font-family: Arial, sans-serif; }
.event-container { max-width: 600px; margin: 0 auto; }
.header { background-color: #3498db; color: white; padding: 20px; text-align: center; }
.content { padding: 30px; }
.event-details { margin: 20px 0; padding: 20px; background-color: #ecf0f1; border-radius: 8px; }
.rsvp-button { background-color: #3498db; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; }
.footer { background-color: #34495e; color: white; padding: 20px; text-align: center; }';
    }

    private function getEducationalHTML(): string
    {
        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $topic_title }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff;">
        <!-- Header -->
        <div style="background-color: #27ae60; color: white; padding: 20px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">{{ $school_name }}</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px;">Learning Resources</p>
        </div>
        
        <!-- Content -->
        <div style="padding: 30px;">
            <h2 style="color: #27ae60;">{{ $topic_title }}</h2>
            
            <div style="margin: 20px 0;">
                <h3>Learning Objectives:</h3>
                <ul style="color: #2c3e50;">
                    {{ $learning_objectives }}
                </ul>
            </div>
            
            <div style="margin: 20px 0; padding: 20px; background-color: #e8f5e8; border-left: 4px solid #27ae60;">
                <h3>Content:</h3>
                {{ $topic_content }}
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $resources_link }}" style="background-color: #27ae60; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Access Resources</a>
            </div>
            
            <p>Happy Learning!<br>{{ $school_name }} Academic Team</p>
        </div>
        
        <!-- Footer -->
        <div style="background-color: #34495e; color: white; padding: 20px; text-align: center; font-size: 12px;">
            <p>© {{ $current_date }} {{ $school_name }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>';
    }

    private function getEducationalCSS(): string
    {
        return 'body { font-family: Arial, sans-serif; }
.educational-container { max-width: 600px; margin: 0 auto; }
.header { background-color: #27ae60; color: white; padding: 20px; text-align: center; }
.content { padding: 30px; }
.learning-objectives { margin: 20px 0; }
.content-box { margin: 20px 0; padding: 20px; background-color: #e8f5e8; border-left: 4px solid #27ae60; }
.resources-button { background-color: #27ae60; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; }
.footer { background-color: #34495e; color: white; padding: 20px; text-align: center; }';
    }

    private function getPromotionalHTML(): string
    {
        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $offer_title }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff;">
        <!-- Header -->
        <div style="background-color: #f39c12; color: white; padding: 20px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">{{ $school_name }}</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px;">Special Offer</p>
        </div>
        
        <!-- Content -->
        <div style="padding: 30px;">
            <h2 style="color: #f39c12;">{{ $offer_title }}</h2>
            
            <div style="margin: 20px 0; padding: 20px; background-color: #fef9e7; border: 2px dashed #f39c12; border-radius: 8px; text-align: center;">
                <h3 style="color: #e67e22; margin: 0;">Special Offer!</h3>
                <p style="margin: 10px 0; font-size: 18px;">{{ $offer_description }}</p>
                <p style="margin: 10px 0; font-size: 16px;"><strong>Use Code: {{ $discount_code }}</strong></p>
                <p style="margin: 10px 0; color: #e74c3c;"><strong>Expires: {{ $expiry_date }}</strong></p>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $cta_link }}" style="background-color: #f39c12; color: white; padding: 15px 40px; text-decoration: none; border-radius: 5px; display: inline-block; font-size: 18px; font-weight: bold;">Claim Offer Now!</a>
            </div>
            
            <p>Don\'t miss out on this amazing opportunity!<br>{{ $school_name }} Team</p>
        </div>
        
        <!-- Footer -->
        <div style="background-color: #34495e; color: white; padding: 20px; text-align: center; font-size: 12px;">
            <p>© {{ $current_date }} {{ $school_name }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>';
    }

    private function getPromotionalCSS(): string
    {
        return 'body { font-family: Arial, sans-serif; }
.promotional-container { max-width: 600px; margin: 0 auto; }
.header { background-color: #f39c12; color: white; padding: 20px; text-align: center; }
.content { padding: 30px; }
.offer-box { margin: 20px 0; padding: 20px; background-color: #fef9e7; border: 2px dashed #f39c12; border-radius: 8px; text-align: center; }
.cta-button { background-color: #f39c12; color: white; padding: 15px 40px; text-decoration: none; border-radius: 5px; display: inline-block; font-size: 18px; font-weight: bold; }
.footer { background-color: #34495e; color: white; padding: 20px; text-align: center; }';
    }
}
