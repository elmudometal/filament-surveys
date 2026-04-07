<?php

namespace ElmudoDev\FilamentSurveys;

use ElmudoDev\FilamentSurveys\Resources\SurveyParticipantResource;
use ElmudoDev\FilamentSurveys\Resources\SurveyResource;
use ElmudoDev\FilamentSurveys\Resources\SurveyResultResource;
use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentSurveysPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-surveys';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            SurveyResource::class,
            SurveyResultResource::class,
            SurveyParticipantResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
