<?php

namespace ElmudoDev\FilamentSurveys\Resources\SurveyParticipantResource\Pages;

use ElmudoDev\FilamentSurveys\Resources\SurveyParticipantResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurveyParticipants extends ListRecords
{
    protected static string $resource = SurveyParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
