<?php

namespace Database\Factories;

use App\Models\DocumentTransferCertificate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocumentTransferCertificate>
 */
class DocumentTransferCertificateFactory extends Factory
{
    protected $model = DocumentTransferCertificate::class;

    public function definition(): array
    {
        $statuses = ['issued','cancelled','draft'];
        return [
            'student_id' => null,
            'student_name' => $this->faker->name(),
            'admission_no' => (string) $this->faker->unique()->numerify('ADM####'),
            'class_name' => 'Class '.$this->faker->numberBetween(1, 12),
            'section_name' => $this->faker->randomElement(['A','B','C','D']),
            'date_of_birth' => $this->faker->date(),
            'father_name' => $this->faker->name('male'),
            'mother_name' => $this->faker->name('female'),
            'admission_date' => $this->faker->dateTimeBetween('-8 years', '-2 years'),
            'leaving_date' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'reason_for_leaving' => $this->faker->randomElement(['Family relocation','Completed term','Other']),
            'conduct' => $this->faker->randomElement(['Good','Very Good','Satisfactory']),
            'tc_number' => strtoupper($this->faker->unique()->bothify('TC-####-??')),
            'issue_date' => $this->faker->date(),
            'remarks' => $this->faker->optional()->sentence(),
            'status' => $this->faker->randomElement($statuses),
        ];
    }
}



