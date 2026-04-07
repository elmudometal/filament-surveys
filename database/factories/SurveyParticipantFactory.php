<?php

namespace ElmudoDev\FilamentSurveys\Database\Factories;

use ElmudoDev\FilamentSurveys\Models\Survey;
use ElmudoDev\FilamentSurveys\Models\SurveyParticipant;
use Illuminate\Database\Eloquent\Factories\Factory;

class SurveyParticipantFactory extends Factory
{
    protected $model = SurveyParticipant::class;

    public function definition(): array
    {
        return [
            'survey_id' => Survey::factory(),
            'email' => $this->faker->safeEmail,
            'unique_link' => SurveyParticipant::generateUniqueLink(),
            'completed' => false,
        ];
    }
}
