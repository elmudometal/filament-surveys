<?php

namespace ElmudoDev\FilamentSurveys\Commands;

use Illuminate\Console\Command;

class FilamentSurveysCommand extends Command
{
    public $signature = 'filament-surveys';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
