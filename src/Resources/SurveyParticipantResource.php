<?php

namespace ElmudoDev\FilamentSurveys\Resources;

use ElmudoDev\FilamentSurveys\Mail\SurveyInvitationMail;
use ElmudoDev\FilamentSurveys\Models\Survey;
use ElmudoDev\FilamentSurveys\Models\SurveyParticipant;
use ElmudoDev\FilamentSurveys\Resources\SurveyParticipantResource\Pages;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;

class SurveyParticipantResource extends Resource
{
    protected static ?string $model = SurveyParticipant::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Participantes';

    protected static ?string $modelLabel = 'Participante';

    protected static ?string $pluralModelLabel = 'Participantes';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'participantes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('survey_id')
                    ->relationship('survey', 'title')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Encuesta'),

                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('Correo Electrónico'),

                TextInput::make('unique_link')
                    ->readOnly()
                    ->label('Enlace Único')
                    ->hiddenOn(['create']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('survey.title')
                    ->label('Encuesta')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable(),

                BadgeColumn::make('completed')
                    ->label('Estado')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Completada' : 'Pendiente'),

                TextColumn::make('completed_at')
                    ->label('Fecha de Completación')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Fecha de Invitación')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('survey_id')
                    ->relationship('survey', 'title')
                    ->label('Filtrar por Encuesta'),

                Tables\Filters\SelectFilter::make('completed')
                    ->options([
                        true => 'Completadas',
                        false => 'Pendientes',
                    ])
                    ->label('Estado de Participación'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Action::make('Reenviar Invitación')
                    ->icon('heroicon-o-paper-airplane')
                    ->action(function (SurveyParticipant $record) {
                        // Lógica para reenviar invitación

                        /** @var Survey $survey */
                        $survey = $record->survey;

                        Mail::to($record->email)->send(
                            new SurveyInvitationMail($survey, $record->unique_link)
                        );

                        // Notificación de reenvío
                        return redirect()->back()->with('success', 'Invitación reenviada');
                    })
                    ->visible(fn (SurveyParticipant $record) => ! $record->completed),

                Tables\Actions\DeleteAction::make(),
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
            // Relaciones si son necesarias
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveyParticipants::route('/'),
            'create' => Pages\CreateSurveyParticipant::route('/create'),
            'edit' => Pages\EditSurveyParticipant::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament-surveys::filament-surveys.nav.group');
    }
}
