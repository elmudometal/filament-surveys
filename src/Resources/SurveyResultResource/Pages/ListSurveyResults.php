<?php

namespace App\Filament\Resources\SurveyResultResource\Pages;

use App\Filament\Resources\SurveyResultResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurveyResults extends ListRecords
{
    protected static string $resource = SurveyResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
