<?php

namespace Database\Factories;

use App\Models\DocumentBonafideCertificate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocumentBonafideCertificate>
 */
class DocumentBonafideCertificateFactory extends Factory
{
    protected $model = DocumentBonafideCertificate::class;

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
            'purpose' => $this->faker->randomElement(['Scholarship','Passport','Bank account','Other']),
            'bc_number' => strtoupper($this->faker->unique()->bothify('BC-####-??')),
            'issue_date' => $this->faker->date(),
            'remarks' => $this->faker->optional()->sentence(),
            'status' => $this->faker->randomElement($statuses),
        ];
    }
}


