<?php

namespace ElmudoDev\FilamentSurveys\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use ElmudoDev\FilamentSurveys\FilamentSurveysServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'ElmudoDev\\FilamentSurveys\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ActionsServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            LivewireServiceProvider::class,
            NotificationsServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            FilamentSurveysServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('app.key', 'base64:7B5Y9W7h+Q2p8/P0XmY3z1K3eU6T8W3b7Y6Z5X4C3B2=');
        config()->set('filament-surveys.model_type', 'App\Models\User');

        $migrations = [
            'create_surveys_table.php.stub',
            'create_survey_questions_table.php.stub',
            'create_survey_options_table.php.stub',
            'create_survey_participants_table.php.stub',
            'create_survey_responses_table.php.stub',
            'add_model_type_to_surveys_table.php.stub',
        ];

        foreach ($migrations as $migrationFile) {
            $migration = include __DIR__ . '/../database/migrations/' . $migrationFile;
            $migration->up();
        }
    }
}
