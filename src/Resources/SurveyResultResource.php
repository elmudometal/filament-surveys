<?php

namespace ElmudoDev\FilamentSurveys\Resources;

use ElmudoDev\FilamentSurveys\Models\Survey;
use ElmudoDev\FilamentSurveys\Models\SurveyResponse;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
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
                \Filament\Tables\Filters\SelectFilter::make('survey_id')
                    ->label('Filtrar por Encuesta')
                    ->options(Survey::pluck('title', 'id'))
                    ->query(function (Builder $query, array $data) {
                        if (! empty($data['value'])) {
                            return $query->whereHas('participant.survey', function ($q) use ($data) {
                                $q->where('surveys.id', $data['value']);
                            });
                        }

                        return $query;
                    }),

                \Filament\Tables\Filters\Filter::make('created_at')
                    ->form([
                        DatePicker::make('from_date')->label('Desde'),
                        DatePicker::make('to_date')->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from_date'] ?? null, fn ($q) => $q->whereDate('created_at', '>=', $data['from_date']))
                            ->when($data['to_date'] ?? null, fn ($q) => $q->whereDate('created_at', '<=', $data['to_date']));
                    }),
            ])
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
                \Filament\Tables\Actions\BulkAction::make('Exportar Resultados')
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
            'index' => \ElmudoDev\FilamentSurveys\Resources\SurveyResultResource\Pages\ManageSurveyResults::route('/'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament-surveys::filament-surveys.nav.group');
    }
}
