<?php

namespace ElmudoDev\FilamentSurveys\Resources\SurveyParticipantResource\Pages;

use ElmudoDev\FilamentSurveys\Mail\SurveyInvitationMail;
use ElmudoDev\FilamentSurveys\Models\Survey;
use ElmudoDev\FilamentSurveys\Models\SurveyParticipant;
use ElmudoDev\FilamentSurveys\Resources\SurveyParticipantResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;

class CreateSurveyParticipant extends CreateRecord
{
    protected static string $resource = SurveyParticipantResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generar enlace único al crear
        $data['unique_link'] = SurveyParticipant::generateUniqueLink();

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var SurveyParticipant $participant */
        $participant = $this->record;

        /** @var Survey $survey */
        $survey = $participant->survey;

        Mail::to($participant->email)->send(
            new SurveyInvitationMail($survey, $participant->unique_link)
        );
    }
}
