<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\ResultNotification;
use App\Models\ResultAnnouncement;
use App\Models\School;
use App\Models\User;

class ResultNotificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schoolId = School::value('id');
        $userId = User::value('id');

        if (!$schoolId || !$userId) {
            return;
        }

        $announcements = ResultAnnouncement::where('school_id', $schoolId)
            ->inRandomOrder()
            ->take(5)
            ->get();

        if ($announcements->isEmpty()) {
            return;
        }

        $statuses = ['draft', 'scheduled', 'sent'];

        foreach (range(1, 6) as $i) {
            $announcement = $announcements->random();
            $status = $statuses[array_rand($statuses)];

            ResultNotification::create([
                'school_id' => $schoolId,
                'result_announcement_id' => $announcement->id,
                'title' => $announcement->title . ' - ' . Str::title(['update','reminder','notice'][array_rand(['update','reminder','notice'])]),
                'message' => 'This is a sample notification regarding the result announcement: ' . $announcement->title,
                'status' => $status,
                'scheduled_at' => $status === 'scheduled' ? now()->addDays(rand(1, 5)) : null,
                'sent_at' => $status === 'sent' ? now()->subDays(rand(0, 5)) : null,
                'target_audience' => ['students', 'parents'],
                'channels' => ['database'],
                'stats' => $status === 'sent' ? ['database_sent' => rand(20, 100)] : [],
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
        }
    }
}
