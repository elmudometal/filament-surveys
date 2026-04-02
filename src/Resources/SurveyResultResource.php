<?php

namespace ElmudoDev\FilamentSurveys\Resources;

use ElmudoDev\FilamentSurveys\Models\Survey;
use ElmudoDev\FilamentSurveys\Models\SurveyResponse;
use ElmudoDev\FilamentSurveys\Resources\SurveyResultResource\Pages\ManageSurveyResults;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class SurveyResultResource extends Resource
{
    protected static ?string $model = SurveyResponse::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Resultados de Encuestas';

    protected static ?string $modelLabel = 'Resultado de Encuesta';

    protected static ?string $pluralModelLabel = 'Resultados de Encuestas';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'resultados-encuestas';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question.survey.title')
                    ->label('Encuesta')
                    ->searchable(),

                TextColumn::make('question.question_text')
                    ->label('Pregunta')
                    ->wrap(),

                TextColumn::make('option.option_text')
                    ->label('Respuesta')
                    ->wrap(),

                TextColumn::make('participant.email')
                    ->label('Participante')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Fecha de Respuesta')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('survey_id')
                    ->label('Filtrar por Encuesta')
                    ->columnSpan(2)
                    ->options(Survey::pluck('title', 'id'))
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['value'], fn ($q, string $survey_id) => $q->where('survey_id', $survey_id));
                    }),
                Filter::make('created_at')
                    ->columns(2)
                    ->columnSpan(2)
                    ->form([
                        DatePicker::make('from_date')->label('Desde'),
                        DatePicker::make('to_date')->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from_date'] ?? null, fn ($q) => $q->whereDate('survey_responses.created_at', '>=', $data['from_date']))
                            ->when($data['to_date'] ?? null, fn ($q) => $q->whereDate('survey_responses.created_at', '<=', $data['to_date']));
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Action::make('Exportar Detalle')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function ($record) {
                        $survey = $record->participant->survey;

                        return Excel::download(
                            new SurveyResultsExport($survey->id),
                            "resultados_encuesta_{$survey->id}.xlsx"
                        );
                    }),
            ])
            ->bulkActions([
                BulkAction::make('Exportar Resultados')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function ($records) {
                        // Si hay registros seleccionados, tomar la primera encuesta
                        $surveyId = $records->first()->participant->survey_id;

                        return Excel::download(
                            new SurveyResultsExport($surveyId),
                            "resultados_encuesta_{$surveyId}.xlsx"
                        );
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSurveyResults::route('/'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament-surveys::filament-surveys.nav.group');
    }

    /**
     * @return Builder<SurveyResponse>
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->select('survey_responses.*', 'survey_questions.survey_id as survey_id')
            ->join('survey_questions', 'survey_responses.question_id', '=', 'survey_questions.id');
    }
}
