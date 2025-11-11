<?php

namespace ElmudoDev\FilamentSurveys\Resources;

use ElmudoDev\FilamentSurveys\Models\Survey;
use ElmudoDev\FilamentSurveys\Resources\SurveyResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $modelLabel = 'Encuesta';

    protected static ?string $pluralModelLabel = 'Encuestas';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'encuestas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('start_date')
                    ->label('Fecha Inicio')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->label('Fecha Fin')
                    ->required(),
                Forms\Components\Repeater::make('questions')
                    ->label('Preguntas')
                    ->relationship('questions')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('question_text')
                            ->label('Pregunta')
                            ->required(),
                        Forms\Components\Select::make('question_type')
                            ->label('Tipo de Pregunta')
                            ->options([
                                'single_choice' => 'Selección única',
                                'multiple_choice' => 'Selección múltiple',
                            ]),
                        Forms\Components\Select::make('question_type2')
                            ->label('Tipo de Pregunta')
                            ->options([
                                'simple' => 'Simple',
                                'score' => 'Puntuación',
                                'boolean' => 'Si/No',
                                'free_text' => 'Campo abierto',
                            ])
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                // Si es una nueva pregunta (no editada)
                                $defaultOptions = match ($state) {
                                    'simple' => [
                                        ['option_text' => 'Bueno'],
                                        ['option_text' => 'Regular'],
                                        ['option_text' => 'Malo'],
                                    ],
                                    'score' => [
                                        ['option_text' => '1'],
                                        ['option_text' => '2'],
                                        ['option_text' => '3'],
                                        ['option_text' => '4'],
                                        ['option_text' => '5'],
                                        ['option_text' => '6'],
                                        ['option_text' => '7'],
                                    ],
                                    'boolean' => [
                                        ['option_text' => 'Si'],
                                        ['option_text' => 'No'],
                                    ],
                                    'free_text' => [
                                        ['option_text' => ''],
                                    ],
                                    default => [],
                                };

                                $set('options', $defaultOptions);
                            }),
                        Forms\Components\Repeater::make('options')
                            ->columnSpan(2)
                            ->label('Opciones')
                            ->relationship('options')
                            ->schema([
                                Forms\Components\TextInput::make('option_text')
                                    ->label('Texto de la Opción')
                                    ->required(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Título'),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Fecha Inicio'),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Fecha Fin'),
                Tables\Columns\TextColumn::make('questions_count')
                    ->label('Preguntas')
                    ->counts('questions')
                    ->label('Número de Preguntas'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveys::route('/'),
            'create' => Pages\CreateSurvey::route('/create'),
            'edit' => Pages\EditSurvey::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament-surveys::filament-surveys.nav.group');
    }
}
