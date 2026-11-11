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

it('can duplicate a survey with its questions and options', function () {
    $survey = Survey::factory()->create(['title' => 'Original']);
    $question = SurveyQuestion::factory()->create(['survey_id' => $survey->id]);
    SurveyOption::factory()->count(2)->create(['question_id' => $question->id]);

    $copy = $survey->duplicate([
        'title' => 'Copia',
        'start_date' => '2027-01-01',
        'end_date' => '2027-01-31',
    ]);

    expect($copy->id)->not->toBe($survey->id)
        ->and($copy->title)->toBe('Copia')
        ->and($copy->slug)->toBe('copia')
        ->and($copy->start_date->toDateString())->toBe('2027-01-01')
        ->and($copy->end_date->toDateString())->toBe('2027-01-31')
        ->and($copy->model_type)->toBe($survey->model_type)
        ->and($copy->questions)->toHaveCount(1)
        ->and($copy->questions->first()->id)->not->toBe($question->id)
        ->and($copy->questions->first()->question_text)->toBe($question->question_text)
        ->and($copy->questions->first()->options)->toHaveCount(2)
        ->and($survey->questions()->count())->toBe(1)
        ->and(SurveyOption::count())->toBe(4);
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
