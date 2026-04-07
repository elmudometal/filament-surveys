<?php

namespace ElmudoDev\FilamentSurveys\Database\Factories;

use ElmudoDev\FilamentSurveys\Models\Survey;
use ElmudoDev\FilamentSurveys\Models\SurveyQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SurveyQuestion>
 */
class SurveyQuestionFactory extends Factory
{
    protected $model = SurveyQuestion::class;

    public function definition(): array
    {
        return [
            'survey_id' => Survey::factory(),
            'question_text' => $this->faker->sentence . '?',
            'question_type' => 'single_choice',
            'is_required' => true,
            'question_section' => 'General',
        ];
    }
}
