<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AiQuestionGeneration;

class AiQuestionGenerationSeeder extends Seeder
{
	public function run(): void
	{
		AiQuestionGeneration::create([
			'school_id' => 1,
			'title' => 'Demo Generation',
			'prompt_text' => 'Generate 5 MCQs on Photosynthesis for grade 8.',
			'num_questions' => 5,
			'model_name' => 'gpt-4o-mini',
			'temperature' => 0.6,
			'status' => 'completed',
			'generated_questions' => [
				['type' => 'mcq', 'question' => 'What is the main product of photosynthesis?', 'options' => ['O2','CO2','H2O','Glucose'], 'answer' => 'Glucose'],
				['type' => 'mcq', 'question' => 'Where does photosynthesis occur?', 'options' => ['Mitochondria','Chloroplast','Nucleus','Ribosome'], 'answer' => 'Chloroplast'],
			],
		]);
	}
}



