<?php

namespace Database\Factories;

use App\Models\DocumentLeavingCertificate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocumentLeavingCertificate>
 */
class DocumentLeavingCertificateFactory extends Factory
{
    protected $model = DocumentLeavingCertificate::class;

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
            'reason_for_leaving' => $this->faker->randomElement(['Family relocation','Completed term','Other']),
            'conduct' => $this->faker->randomElement(['Good','Very Good','Satisfactory']),
            'lc_number' => strtoupper($this->faker->unique()->bothify('LC-####-??')),
            'issue_date' => $this->faker->date(),
            'remarks' => $this->faker->optional()->sentence(),
            'status' => $this->faker->randomElement($statuses),
        ];
    }
}


