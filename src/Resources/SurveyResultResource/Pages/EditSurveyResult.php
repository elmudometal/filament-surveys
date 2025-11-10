<?php

namespace ElmudoDev\FilamentSurveys\Resources\SurveyResultResource\Pages;

use ElmudoDev\FilamentSurveys\Resources\SurveyResultResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSurveyResult extends EditRecord
{
    protected static string $resource = SurveyResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
