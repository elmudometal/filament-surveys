<?php

namespace ElmudoDev\FilamentSurveys\Resources\SurveyParticipantResource\Pages;

use ElmudoDev\FilamentSurveys\Resources\SurveyParticipantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSurveyParticipant extends EditRecord
{
    protected static string $resource = SurveyParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
