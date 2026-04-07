<?php

use ElmudoDev\FilamentSurveys\Models\Survey;
use ElmudoDev\FilamentSurveys\Models\SurveyOption;
use ElmudoDev\FilamentSurveys\Models\SurveyQuestion;

it('can list surveys', function () {
    Survey::factory()->count(5)->create();

    expect(Survey::count())->toBe(5);
});

it('can create a survey', function () {
    $survey = Survey::factory()->create([
        'title' => 'Test Survey',
    ]);

    expect($survey->title)->toBe('Test Survey')
        ->and($survey->slug)->toBe('test-survey');
});

it('can add questions and options to a survey', function () {
    $survey = Survey::factory()->create();

    $question = SurveyQuestion::factory()->create([
        'survey_id' => $survey->id,
        'question_text' => '¿Cómo califica el servicio?',
    ]);

    $option = SurveyOption::factory()->create([
        'question_id' => $question->id,
        'option_text' => 'Excelente',
    ]);

    expect($survey->questions)->toHaveCount(1)
        ->and($survey->questions->first()->question_text)->toBe('¿Cómo califica el servicio?')
        ->and($question->options)->toHaveCount(1)
        ->and($question->options->first()->option_text)->toBe('Excelente');
});
