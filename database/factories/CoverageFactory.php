<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Coverage;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\User;
use App\Models\School;

class CoverageFactory extends Factory
{
    protected $model = Coverage::class;

    public function definition(): array
    {
        $school = School::inRandomOrder()->first();
        $class = SchoolClass::inRandomOrder()->first();
        $section = Section::inRandomOrder()->first();
        $subject = Subject::inRandomOrder()->first();
        $teacher = User::where('role_id', 3)->inRandomOrder()->first();

        $assignedDate = $this->faker->dateTimeBetween('-2 months', 'now'); // correct
$coverageDate = $this->faker->dateTimeBetween($assignedDate, (clone $assignedDate)->modify('+10 days')); 
$completedDate = $this->faker->boolean(70) ? $this->faker->dateTimeBetween($coverageDate, 'now') : null;


        $status = $completedDate ? 'completed' : ($this->faker->boolean(20) ? 'delayed' : 'pending');
        $priority = $this->faker->randomElement(['normal','important','urgent']);

        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'assigned_date' => $assignedDate,
            'date' => $coverageDate,
            'completed_date' => $completedDate,
            'school_id' => $school?->id,
            'class_id' => $class?->id,
            'section_id' => $section?->id,
            'subject_id' => $subject?->id,
            'teacher_id' => $teacher?->id,
            'status' => $status,
            'priority' => $priority,
            'remarks' => $this->faker->sentence(),
            'attachments' => null, // optional, can add fake file paths
            'is_active' => true,
        ];
    }
}
