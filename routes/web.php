<?php

use ElmudoDev\FilamentSurveys\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('filament-surveys.public_prefix', 'survey'),
    'as' => 'survey.',
    'middleware' => ['web'],
], function () {
    Route::view('no-disponible', 'filament-surveys::survey.not_available')
        ->name('not_available');

    Route::get('{survey:slug}/{model_id}', [SurveyController::class, 'showSurvey'])
        ->name('fill')
        ->missing(function () {
            return Redirect::route('survey.not_available');
        });

    Route::post('{survey:slug}/{model_id}/submit', [SurveyController::class, 'submitSurvey'])
        ->name('submit');

    Route::view('gracias', 'filament-surveys::survey.thanks')
        ->name('thanks');
});
