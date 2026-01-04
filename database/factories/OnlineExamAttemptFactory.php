<?php

namespace Database\Factories;

use App\Models\OnlineExamAttempt;
use App\Models\OnlineExam;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OnlineExamAttempt>
 */
class OnlineExamAttemptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OnlineExamAttempt::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startedAt = $this->faker->dateTimeBetween('-30 days', 'now');
        $timeTaken = $this->faker->numberBetween(15, 120); // 15 to 120 minutes
        $submittedAt = Carbon::parse($startedAt)->addMinutes($timeTaken);

        return [
            'online_exam_id' => OnlineExam::factory(),
            'student_id' => User::factory(),
            'started_at' => $startedAt,
            'submitted_at' => $submittedAt,
            'time_taken_minutes' => $timeTaken,
            'total_marks_obtained' => $this->faker->numberBetween(20, 100),
            'percentage' => function (array $attributes) {
                return $this->faker->numberBetween(30, 100);
            },
            'status' => $this->faker->randomElement(['submitted', 'auto_submitted']),
            'answers' => function () {
                // Generate sample answers for 10 questions
                $answers = [];
                for ($i = 1; $i <= 10; $i++) {
                    $answers[$i] = $this->faker->randomElement(['A', 'B', 'C', 'D']);
                }
                return $answers;
            },
            'proctoring_data' => function () {
                return [
                    'tab_switches' => $this->faker->numberBetween(0, 5),
                    'copy_attempts' => $this->faker->numberBetween(0, 3),
                    'face_detection_violations' => $this->faker->numberBetween(0, 2),
                    'multiple_monitors_detected' => $this->faker->boolean(20),
                ];
            },
            'is_passed' => function (array $attributes) {
                return $attributes['percentage'] >= 40; // Assuming 40% is passing
            },
            'attempt_number' => 1,
        ];
    }

    /**
     * Indicate that the attempt is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
            'submitted_at' => null,
            'time_taken_minutes' => null,
            'total_marks_obtained' => 0,
            'percentage' => 0,
            'is_passed' => false,
        ]);
    }

    /**
     * Indicate that the attempt was auto-submitted.
     */
    public function autoSubmitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'auto_submitted',
        ]);
    }

    /**
     * Indicate that the attempt was abandoned.
     */
    public function abandoned(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'abandoned',
            'submitted_at' => null,
            'time_taken_minutes' => null,
            'total_marks_obtained' => 0,
            'percentage' => 0,
            'is_passed' => false,
        ]);
    }

    /**
     * Indicate that the attempt passed.
     */
    public function passed(): static
    {
        return $this->state(fn (array $attributes) => [
            'percentage' => $this->faker->numberBetween(60, 100),
            'total_marks_obtained' => function (array $attributes) {
                return (int) ($attributes['percentage'] * 100 / 100); // Assuming exam out of 100
            },
            'is_passed' => true,
        ]);
    }

    /**
     * Indicate that the attempt failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'percentage' => $this->faker->numberBetween(10, 39),
            'total_marks_obtained' => function (array $attributes) {
                return (int) ($attributes['percentage'] * 100 / 100); // Assuming exam out of 100
            },
            'is_passed' => false,
        ]);
    }

    /**
     * Indicate that the attempt has proctoring violations.
     */
    public function withViolations(): static
    {
        return $this->state(fn (array $attributes) => [
            'proctoring_data' => [
                'tab_switches' => $this->faker->numberBetween(5, 15),
                'copy_attempts' => $this->faker->numberBetween(3, 10),
                'face_detection_violations' => $this->faker->numberBetween(2, 8),
                'multiple_monitors_detected' => true,
                'suspicious_activity_detected' => true,
            ],
        ]);
    }
}
