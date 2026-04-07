<?php

namespace ElmudoDev\FilamentSurveys\Database\Factories;

use ElmudoDev\FilamentSurveys\Models\SurveyOption;
use ElmudoDev\FilamentSurveys\Models\SurveyQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SurveyOption>
 */
class SurveyOptionFactory extends Factory
{
    protected $model = SurveyOption::class;

    public function definition(): array
    {
        return [
            'question_id' => SurveyQuestion::factory(),
            'option_text' => $this->faker->word,
            'option_justify' => false,
        ];
    }
}
