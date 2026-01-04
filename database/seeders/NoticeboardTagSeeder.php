<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NoticeboardTag;

class NoticeboardTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            [
                'name' => 'Important',
                'color' => '#dc3545',
                'description' => 'High priority announcements and critical information',
            ],
            [
                'name' => 'Update',
                'color' => '#007bff',
                'description' => 'System updates and software changes',
            ],
            [
                'name' => 'Event',
                'color' => '#28a745',
                'description' => 'Company events, meetings, and gatherings',
            ],
            [
                'name' => 'Policy',
                'color' => '#6f42c1',
                'description' => 'Company policies and procedures',
            ],
            [
                'name' => 'Training',
                'color' => '#fd7e14',
                'description' => 'Training sessions and skill development',
            ],
            [
                'name' => 'Maintenance',
                'color' => '#ffc107',
                'description' => 'System maintenance and downtime notices',
            ],
            [
                'name' => 'Holiday',
                'color' => '#e83e8c',
                'description' => 'Holiday schedules and time-off information',
            ],
            [
                'name' => 'Security',
                'color' => '#343a40',
                'description' => 'Security alerts and safety information',
            ],
            [
                'name' => 'Innovation',
                'color' => '#20c997',
                'description' => 'New ideas, projects, and innovations',
            ],
            [
                'name' => 'Recognition',
                'color' => '#17a2b8',
                'description' => 'Employee recognition and achievements',
            ],
            [
                'name' => 'Health & Safety',
                'color' => '#28a745',
                'description' => 'Health guidelines and safety protocols',
            ],
            [
                'name' => 'Technology',
                'color' => '#6c757d',
                'description' => 'Technology news and IT updates',
            ],
            [
                'name' => 'Finance',
                'color' => '#fd7e14',
                'description' => 'Financial updates and budget information',
            ],
            [
                'name' => 'HR',
                'color' => '#e83e8c',
                'description' => 'Human resources announcements',
            ],
            [
                'name' => 'General',
                'color' => '#6c757d',
                'description' => 'General announcements and information',
            ],
        ];

        foreach ($tags as $tag) {
            NoticeboardTag::create($tag);
        }

        $this->command->info('Noticeboard Tags seeded successfully!');
    }
}
