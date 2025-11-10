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

    Route::get('{unique_link}', [SurveyController::class, 'showSurvey'])
        ->name('fill');

    Route::post('{unique_link}/submit', [SurveyController::class, 'submitSurvey'])
        ->name('submit');

    Route::view('gracias', 'filament-surveys::survey.thanks')
        ->name('thanks');
});
