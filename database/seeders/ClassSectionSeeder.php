<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassSection;

class ClassSectionSeeder extends Seeder
{
    public function run(): void
    {
        ClassSection::insert([
            [
                'class_id' => 1,
                'section_id' => 1,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_id' => 1,
                'section_id' => 2,
                'status' => 'Inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_id' => 2,
                'section_id' => 1,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
