<?php

namespace ElmudoDev\FilamentSurveys\Database\Factories;

use ElmudoDev\FilamentSurveys\Models\Survey;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Survey>
 */
class SurveyFactory extends Factory
{
    protected $model = Survey::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'start_date' => now(),
            'end_date' => now()->addDays(7),
            'sections' => ['General'],
            'model_type' => 'App\Models\User',
        ];
    }
}
