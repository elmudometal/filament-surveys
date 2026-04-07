<?php

namespace ElmudoDev\FilamentSurveys\Database\Factories;

use ElmudoDev\FilamentSurveys\Models\SurveyOption;
use ElmudoDev\FilamentSurveys\Models\SurveyQuestion;
use ElmudoDev\FilamentSurveys\Models\SurveyResponse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SurveyResponse>
 */
class SurveyResponseFactory extends Factory
{
    protected $model = SurveyResponse::class;

    public function definition(): array
    {
        return [
            'model_type' => config('filament-surveys.model_type', 'App\\Models\\Survey'),
            'model_id' => $this->faker->randomNumber(),
            'question_id' => SurveyQuestion::factory(),
            'option_id' => SurveyOption::factory(),
            'justify' => $this->faker->sentence(),
        ];
    }
}
