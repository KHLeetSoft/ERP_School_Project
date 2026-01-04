<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Noticeboard;
use App\Models\Department;
use App\Models\User;
use App\Models\NoticeboardTag;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NoticeboardSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all();
        $users = User::all();
        $tags = NoticeboardTag::all();

        if ($users->isEmpty()) {
            $user = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]);
            $users = collect([$user]);
        }

        $noticeboards = [
            [
                'title' => 'Company Policy Update - Remote Work Guidelines',
                'content' => 'We are updating our remote work policy to provide more flexibility for our employees.',
                'type' => 'policy',
                'priority' => 'high',
                'status' => 'published',
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(25),
                'is_featured' => true,
                'is_pinned' => true,
                'is_public' => true,
                'author_id' => $users->first()->id,
                'department_id' => $departments->first()->id,
                'target_audience' => 'all',
                'views_count' => 156,
                'published_at' => Carbon::now()->subDays(5),
                'expires_at' => Carbon::now()->addDays(25),
            ],
            [
                'title' => 'Annual Company Meeting - Save the Date',
                'content' => 'Mark your calendars! Our annual company meeting will be held on December 15th, 2024.',
                'type' => 'event',
                'priority' => 'medium',
                'status' => 'published',
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::parse('2024-12-15'),
                'is_featured' => true,
                'is_pinned' => false,
                'is_public' => true,
                'author_id' => $users->first()->id,
                'department_id' => $departments->first()->id,
                'target_audience' => 'all',
                'views_count' => 89,
                'published_at' => Carbon::now()->subDays(10),
                'expires_at' => Carbon::parse('2024-12-15'),
            ],
            [
                'title' => 'System Maintenance - Planned Downtime',
                'content' => 'We will be performing system maintenance on Saturday, August 31st, from 2:00 AM to 6:00 AM EST.',
                'type' => 'announcement',
                'priority' => 'high',
                'status' => 'published',
                'start_date' => Carbon::now()->subDays(3),
                'end_date' => Carbon::parse('2024-08-31'),
                'is_featured' => false,
                'is_pinned' => true,
                'is_public' => true,
                'author_id' => $users->first()->id,
                'department_id' => $departments->first()->id,
                'target_audience' => 'all',
                'views_count' => 234,
                'published_at' => Carbon::now()->subDays(3),
                'expires_at' => Carbon::parse('2024-08-31'),
            ],
        ];

        foreach ($noticeboards as $noticeboardData) {
            $noticeboardData['slug'] = Str::slug($noticeboardData['title']);
            $noticeboard = Noticeboard::create($noticeboardData);
            
            $randomTags = $tags->random(rand(2, 4));
            $noticeboard->tags()->attach($randomTags->pluck('id')->toArray());
        }

        $this->command->info('Noticeboards seeded successfully!');
    }
}
