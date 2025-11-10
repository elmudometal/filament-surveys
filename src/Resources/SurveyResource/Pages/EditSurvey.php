<?php

namespace ElmudoDev\FilamentSurveys\Resources\SurveyResource\Pages;

use ElmudoDev\FilamentSurveys\Resources\SurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSurvey extends EditRecord
{
    protected static string $resource = SurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
