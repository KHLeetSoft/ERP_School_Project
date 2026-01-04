<?php

namespace Database\Factories;

use App\Models\OnlineExam;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OnlineExam>
 */
class OnlineExamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OnlineExam::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+30 days');
        $endDate = Carbon::parse($startDate)->addMinutes($this->faker->numberBetween(30, 180));

        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'class_id' => SchoolClass::factory(),
            'section_id' => Section::factory(),
            'subject_id' => Subject::factory(),
            'duration_minutes' => $this->faker->randomElement([30, 45, 60, 90, 120]),
            'total_marks' => $this->faker->randomElement([50, 75, 100, 150, 200]),
            'passing_marks' => function (array $attributes) {
                return (int) ($attributes['total_marks'] * 0.4); // 40% passing marks
            },
            'start_datetime' => $startDate,
            'end_datetime' => $endDate,
            'negative_marking' => $this->faker->boolean(30), // 30% chance of negative marking
            'negative_marks' => function (array $attributes) {
                return $attributes['negative_marking'] ? $this->faker->randomFloat(2, 0.25, 1.0) : 0;
            },
            'randomize_questions' => $this->faker->boolean(70), // 70% chance of randomization
            'show_result_immediately' => $this->faker->boolean(50),
            'status' => $this->faker->randomElement(['draft', 'published']),
            'instructions' => $this->faker->paragraph(3),
            'allow_calculator' => $this->faker->boolean(30),
            'allow_notes' => $this->faker->boolean(20),
            'max_attempts' => $this->faker->randomElement([1, 2, 3]),
            'enable_proctoring' => $this->faker->boolean(40),
            'proctoring_settings' => function (array $attributes) {
                if ($attributes['enable_proctoring']) {
                    return [
                        'face_detection' => $this->faker->boolean(80),
                        'tab_switching_detection' => $this->faker->boolean(90),
                        'copy_paste_detection' => $this->faker->boolean(85),
                        'multiple_monitor_detection' => $this->faker->boolean(70),
                        'microphone_detection' => $this->faker->boolean(60),
                    ];
                }
                return null;
            },
        ];
    }

    /**
     * Indicate that the exam is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    /**
     * Indicate that the exam is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
        ]);
    }

    /**
     * Indicate that the exam is upcoming.
     */
    public function upcoming(): static
    {
        $startDate = $this->faker->dateTimeBetween('+1 day', '+30 days');
        
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'start_datetime' => $startDate,
            'end_datetime' => Carbon::parse($startDate)->addMinutes($attributes['duration_minutes']),
        ]);
    }

    /**
     * Indicate that the exam is currently active.
     */
    public function active(): static
    {
        $startDate = $this->faker->dateTimeBetween('-2 hours', 'now');
        
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'start_datetime' => $startDate,
            'end_datetime' => Carbon::parse($startDate)->addMinutes($attributes['duration_minutes']),
        ]);
    }

    /**
     * Indicate that the exam is completed.
     */
    public function completed(): static
    {
        $startDate = $this->faker->dateTimeBetween('-30 days', '-1 day');
        
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'start_datetime' => $startDate,
            'end_datetime' => Carbon::parse($startDate)->addMinutes($attributes['duration_minutes']),
        ]);
    }

    /**
     * Indicate that the exam has negative marking enabled.
     */
    public function withNegativeMarking(): static
    {
        return $this->state(fn (array $attributes) => [
            'negative_marking' => true,
            'negative_marks' => $this->faker->randomFloat(2, 0.25, 1.0),
        ]);
    }

    /**
     * Indicate that the exam has proctoring enabled.
     */
    public function withProctoring(): static
    {
        return $this->state(fn (array $attributes) => [
            'enable_proctoring' => true,
            'proctoring_settings' => [
                'face_detection' => true,
                'tab_switching_detection' => true,
                'copy_paste_detection' => true,
                'multiple_monitor_detection' => true,
                'microphone_detection' => true,
            ],
        ]);
    }
}
