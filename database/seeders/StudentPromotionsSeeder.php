<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentPromotion;
use App\Models\StudentDetail;
use App\Models\SchoolClass;
use App\Models\Section;
use Carbon\Carbon;

class StudentPromotionsSeeder extends Seeder
{
    public function run(): void
    {
        $students = StudentDetail::all();
        if ($students->isEmpty()) {
            return;
        }

        foreach ($students as $student) {
            if (empty($student->class_id)) {
                continue;
            }

            // Get classes & sections only for this student's school
            $classes = SchoolClass::where('school_id', $student->school_id)->pluck('id')->all();
            $sections = Section::where('school_id', $student->school_id)->pluck('id')->all();

            // From IDs
            $fromClassId = $student->class_id;
            $fromSectionId = null;

            if (!empty($student->section_id) && in_array($student->section_id, $sections)) {
                $fromSectionId = $student->section_id;
            } elseif (count($sections)) {
                $fromSectionId = $sections[array_rand($sections)];
            }

            // To IDs
            $toClassId = $fromClassId;
            if (count($classes) > 1) {
                do {
                    $toClassId = $classes[array_rand($classes)];
                } while ($toClassId === $fromClassId);
            }

            $toSectionId = count($sections) ? $sections[array_rand($sections)] : null;

            // Insert only if foreign keys are valid
            StudentPromotion::create([
                'student_id'      => $student->id,
                'from_class_id'   => $fromClassId,
                'from_section_id' => $fromSectionId,
                'to_class_id'     => $toClassId,
                'to_section_id'   => $toSectionId,
                'promoted_at'     => Carbon::now()->subDays(rand(10, 200))->format('Y-m-d'),
                'status'          => 'promoted',
                'remarks'         => 'Seeded promotion record',
            ]);
        }
    }
}
