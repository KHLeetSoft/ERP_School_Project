<?php

namespace Database\Factories;

use App\Models\DocumentStudyCertificate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocumentStudyCertificate>
 */
class DocumentStudyCertificateFactory extends Factory
{
    protected $model = DocumentStudyCertificate::class;

    public function definition(): array
    {
        $statuses = ['issued','cancelled','draft'];
        return [
            'student_id' => null,
            'school_id' => 1,
            'student_name' => $this->faker->name(),
            'admission_no' => (string) $this->faker->unique()->numerify('ADM####'),
            'roll_no' => (string) $this->faker->numerify('##'),
            'class_name' => 'Class '.$this->faker->numberBetween(1, 12),
            'section_name' => $this->faker->randomElement(['A','B','C','D']),
            'date_of_birth' => $this->faker->date(),
            'father_name' => $this->faker->name('male'),
            'mother_name' => $this->faker->name('female'),
            'academic_year' => now()->format('Y') . '-' . now()->addYear()->format('Y'),
            'sc_number' => strtoupper($this->faker->unique()->bothify('SC-####-??')),
            'issue_date' => $this->faker->date(),
            'remarks' => $this->faker->optional()->sentence(),
            'status' => $this->faker->randomElement($statuses),
        ];
    }
}


