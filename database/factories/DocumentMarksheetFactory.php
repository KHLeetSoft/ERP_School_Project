<?php

namespace Database\Factories;

use App\Models\DocumentMarksheet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocumentMarksheet>
 */
class DocumentMarksheetFactory extends Factory
{
    protected $model = DocumentMarksheet::class;

    public function definition(): array
    {
        $total = 500;
        $obtained = $this->faker->numberBetween(250, 500);
        $percentage = round(($obtained / $total) * 100, 2);
        $grade = $percentage >= 90 ? 'A+' : ($percentage >= 75 ? 'A' : ($percentage >= 60 ? 'B' : 'C'));
        $statuses = ['issued','cancelled','draft'];
        return [
            'student_id' => null,
            'student_name' => $this->faker->name(),
            'admission_no' => (string) $this->faker->unique()->numerify('ADM####'),
            'roll_no' => (string) $this->faker->numerify('##'),
            'class_name' => 'Class '.$this->faker->numberBetween(1, 12),
            'section_name' => $this->faker->randomElement(['A','B','C','D']),
            'exam_name' => $this->faker->randomElement(['Term Examination','Final Examination','Unit Test']),
            'term' => $this->faker->randomElement(['Term 1','Term 2','Annual']),
            'academic_year' => now()->format('Y') . '-' . now()->addYear()->format('Y'),
            'ms_number' => strtoupper($this->faker->unique()->bothify('MS-####-??')),
            'issue_date' => $this->faker->date(),
            'total_marks' => $total,
            'obtained_marks' => $obtained,
            'percentage' => $percentage,
            'grade' => $grade,
            'result_status' => $this->faker->randomElement(['pass','fail']),
            'remarks' => $this->faker->optional()->sentence(),
            'marks_json' => json_encode([
                ['subject' => 'Math', 'marks' => $this->faker->numberBetween(50, 100)],
                ['subject' => 'Science', 'marks' => $this->faker->numberBetween(50, 100)],
                ['subject' => 'English', 'marks' => $this->faker->numberBetween(50, 100)],
            ]),
            'status' => $this->faker->randomElement($statuses),
        ];
    }
}


