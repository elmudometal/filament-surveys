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
                Forms\Components\TextInput::make('slug')
                    ->label('identificador (slug)')
                    ->required()
                    ->columnSpanFull()
                    ->hiddenOn('create')
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
                Forms\Components\TagsInput::make('sections')
                    ->label('Secciones')
                    ->reorderable()
                    ->live()
                    ->required(),
                Forms\Components\Select::make('model_type')
                    ->label('Audiencia')
                    ->options(config('filament-surveys.models_enum'))
                    ->searchable()
                    ->required()
                    ->placeholder('Seleccione una audiencia'),
                Forms\Components\Repeater::make('questions')
                    ->label('Preguntas')
                    ->relationship('questions')
                    ->columnSpanFull()
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('question_text')
                            ->label('Pregunta')
                            ->required(),
                        Forms\Components\Select::make('question_section')
                            ->label('Sección')
                            ->live()
                            ->options(fn (Forms\Get $get, $state) => $state ? collect($get('../../sections'))->add($state)->mapWithKeys(fn ($v) => [$v => $v]) : collect($get('../../sections'))->mapWithKeys(fn ($v) => [$v => $v])),
                        Forms\Components\Select::make('question_type')
                            ->label('Tipo de Pregunta')
                            ->options([
                                'single_choice' => 'Opción Única',
                                'simple' => 'Simple',
                                'score' => 'Puntuación',
                                'boolean' => 'Si/No',
                                'multiple_choice' => 'Opción Múltiple (múltiples respuestas)',
                                'free_text' => 'Campo abierto',
                            ])
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                // Si es una nueva pregunta (no editada)
                                $defaultOptions = match ($state) {
                                    'simple' => [
                                        ['option_text' => 'Bueno', 'option_justify' => false],
                                        ['option_text' => 'Regular', 'option_justify' => true],
                                        ['option_text' => 'Malo', 'option_justify' => true],
                                    ],
                                    'score' => [
                                        ['option_text' => '1', 'option_justify' => false],
                                        ['option_text' => '2', 'option_justify' => false],
                                        ['option_text' => '3', 'option_justify' => false],
                                        ['option_text' => '4', 'option_justify' => false],
                                        ['option_text' => '5', 'option_justify' => false],
                                        ['option_text' => '6', 'option_justify' => false],
                                        ['option_text' => '7', 'option_justify' => false],
                                    ],
                                    'boolean' => [
                                        ['option_text' => 'Si', 'option_justify' => false],
                                        ['option_text' => 'No', 'option_justify' => false],
                                    ],
                                    'single_choice', 'multiple_choice', 'free_text' => [
                                        ['option_text' => '', 'option_justify' => false],
                                    ],
                                    default => [],
                                };

                                $set('options', $defaultOptions);
                            }),
                        Forms\Components\Toggle::make('is_required')
                            ->label('Requerido')
                            ->onColor('success')
                            ->offColor('danger')
                            ->inline(false)
                            ->default(true)
                            ->required(),
                        Forms\Components\Repeater::make('options')
                            ->columns(3)
                            ->columnSpan(2)
                            ->label('Opciones')
                            ->relationship('options')
                            ->schema([
                                Forms\Components\TextInput::make('option_text')
                                    ->label('Texto de la Opción')
                                    ->columnSpan(2)
                                    ->required(),
                                Forms\Components\Toggle::make('option_justify')
                                    ->label('Justificar')
                                    ->inline(false)
                                    ->columnSpan(1)
                                    ->default(false)
                                    ->onColor('success')
                                    ->offColor('danger'),
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
                Tables\Columns\TextColumn::make('model_type')
                    ->formatStateUsing(fn (string $state) => config('filament-surveys.models_enum')::from($state))
                    ->badge()
                    ->label('Audiencia'),
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
