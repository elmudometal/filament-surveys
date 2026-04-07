<?php

namespace ElmudoDev\FilamentSurveys\Resources;

use BackedEnum;
use ElmudoDev\FilamentSurveys\Models\Survey;
use ElmudoDev\FilamentSurveys\Resources\SurveyResource\Pages;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $modelLabel = 'Encuesta';

    protected static ?string $pluralModelLabel = 'Encuestas';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'encuestas';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label('identificador (slug)')
                    ->required()
                    ->columnSpanFull()
                    ->hiddenOn('create')
                    ->maxLength(255),
                RichEditor::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
                DatePicker::make('start_date')
                    ->label('Fecha Inicio')
                    ->required(),
                DatePicker::make('end_date')
                    ->label('Fecha Fin')
                    ->required(),
                TagsInput::make('sections')
                    ->label('Secciones')
                    ->reorderable()
                    ->live()
                    ->required(),
                Select::make('model_type')
                    ->label('Audiencia')
                    ->options(config('filament-surveys.models_enum'))
                    ->searchable()
                    ->required()
                    ->placeholder('Seleccione una audiencia'),
                Repeater::make('questions')
                    ->label('Preguntas')
                    ->relationship('questions')
                    ->columnSpanFull()
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        TextInput::make('question_text')
                            ->label('Pregunta')
                            ->required(),
                        Select::make('question_section')
                            ->label('Sección')
                            ->live()
                            ->options(fn (Get $get, $state) => $state ? collect($get('../../sections'))->add($state)->mapWithKeys(fn ($v) => [$v => $v]) : collect($get('../../sections'))->mapWithKeys(fn ($v) => [$v => $v])),
                        Select::make('question_type')
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
                        Toggle::make('is_required')
                            ->label('Requerido')
                            ->onColor('success')
                            ->offColor('danger')
                            ->inline(false)
                            ->default(true)
                            ->required(),
                        Repeater::make('options')
                            ->columns(3)
                            ->columnSpan(2)
                            ->label('Opciones')
                            ->relationship('options')
                            ->schema([
                                TextInput::make('option_text')
                                    ->label('Texto de la Opción')
                                    ->columnSpan(2)
                                    ->required(),
                                Toggle::make('option_justify')
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
                TextColumn::make('title')
                    ->label('Título'),
                TextColumn::make('model_type')
                    ->formatStateUsing(fn (string $state) => config('filament-surveys.models_enum')::from($state))
                    ->badge()
                    ->label('Audiencia'),
                TextColumn::make('start_date')
                    ->label('Fecha Inicio'),
                TextColumn::make('end_date')
                    ->label('Fecha Fin'),
                TextColumn::make('questions_count')
                    ->label('Preguntas')
                    ->counts('questions')
                    ->label('Número de Preguntas'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
