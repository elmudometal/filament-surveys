<?php

namespace ElmudoDev\FilamentSurveys\Resources\SurveyResultResource\Pages;

use ElmudoDev\FilamentSurveys\Exports\SurveyResultsExport;
use ElmudoDev\FilamentSurveys\Models\Survey;
use ElmudoDev\FilamentSurveys\Resources\SurveyResultResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ManageRecords;
use Maatwebsite\Excel\Facades\Excel;

class ManageSurveyResults extends ManageRecords
{
    protected static string $resource = SurveyResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Exportar Resultados Totales')
                ->icon('heroicon-o-document-arrow-down')
                ->form([
                    \Filament\Forms\Components\Select::make('survey_id')
                        ->label('Seleccionar Encuesta')
                        ->options(Survey::pluck('title', 'id'))
                        ->required(),
                ])
                ->action(function (array $data) {
                    $surveyId = $data['survey_id'];

                    /** @var Survey $survey */
                    $survey = Survey::findOrFail($surveyId);

                    return Excel::download(
                        new SurveyResultsExport($surveyId),
                        "resultados_encuesta_{$survey->title}.xlsx"
                    );
                }),
        ];
    }
}
