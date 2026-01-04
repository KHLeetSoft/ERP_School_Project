<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\ResultPublication;
use App\Models\ResultAnnouncement;
use App\Models\School;
use App\Models\User;

class ResultPublicationsSeeder extends Seeder
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

        $types = ['merit_list', 'rank_card', 'grade_sheet', 'performance_report', 'certificate'];

        foreach ($announcements as $idx => $announcement) {
            foreach (range(1, 2) as $n) {
                $type = $types[($idx + $n) % count($types)];
                $isPublished = (($idx + $n) % 2) === 0;

                ResultPublication::create([
                    'school_id' => $schoolId,
                    'result_announcement_id' => $announcement->id,
                    'publication_title' => $announcement->title . ' - ' . Str::title(str_replace('_', ' ', $type)),
                    'publication_content' => 'Auto-generated sample ' . $type . ' for testing.',
                    'publication_type' => $type,
                    'status' => $isPublished ? 'published' : 'draft',
                    'published_at' => $isPublished ? now()->subDays(rand(0, 15)) : null,
                    'expires_at' => $isPublished ? now()->addMonths(3) : null,
                    'publication_data' => [
                        'note' => 'Dummy content for development',
                        'items' => [
                            ['label' => 'Total Students', 'value' => rand(30, 100)],
                            ['label' => 'Top Score', 'value' => rand(80, 100)],
                        ],
                    ],
                    'template_settings' => [
                        'theme' => ['default', 'modern', 'classic', 'elegant'][rand(0, 3)],
                        'primary_color' => '#007bff',
                        'include_logo' => true,
                    ],
                    'pdf_file_path' => null,
                    'is_featured' => rand(0, 1) === 1,
                    'allow_download' => true,
                    'require_authentication' => true,
                    'access_permissions' => ['students', 'parents', 'teachers', 'admin'],
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]);
            }
        }
    }
}
