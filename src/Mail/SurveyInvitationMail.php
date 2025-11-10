<?php

namespace ElmudoDev\FilamentSurveys\Mail;

use ElmudoDev\FilamentSurveys\Models\Survey;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SurveyInvitationMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Survey $survey, public string $uniqueLink) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitación a Encuesta: ' . $this->survey->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'filament-surveys::emails.survey_invite',
            with: [
                'survey' => $this->survey,
                'unique_link' => $this->uniqueLink,
            ]
        );
    }
}
