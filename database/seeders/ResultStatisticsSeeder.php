<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ResultStatistic;

class ResultStatisticsSeeder extends Seeder
{
    public function run(): void
    {
        if (ResultStatistic::count() > 0) {
            return;
        }

        ResultStatistic::create([
            'school_id' => 1,
            'result_announcement_id' => null,
            'title' => 'Sample Result Statistics',
            'filters' => ['class_id' => null, 'section_id' => null],
            'metrics' => [
                'total_students' => 100,
                'appeared' => 95,
                'passed' => 80,
                'failed' => 15,
                'pass_percentage' => 84.21,
                'top_score' => 98,
                'average_score' => 72.5,
            ],
            'generated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}


