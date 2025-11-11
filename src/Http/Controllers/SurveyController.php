<?php

namespace ElmudoDev\FilamentSurveys\Http\Controllers;

use ElmudoDev\FilamentSurveys\Http\Requests\InviteParticipantsRequest;
use ElmudoDev\FilamentSurveys\Http\Requests\SubmitSurveyResponseRequest;
use ElmudoDev\FilamentSurveys\Mail\SurveyInvitationMail;
use ElmudoDev\FilamentSurveys\Models\Survey;
use ElmudoDev\FilamentSurveys\Models\SurveyParticipant;
use ElmudoDev\FilamentSurveys\Models\SurveyResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SurveyController
{
    public function inviteParticipants(InviteParticipantsRequest $request, Survey $survey): RedirectResponse
    {
        $validatedEmails = $request->validated()['emails'];

        foreach ($validatedEmails as $email) {
            $participant = SurveyParticipant::create([
                'survey_id' => $survey->id,
                'email' => $email,
                'unique_link' => SurveyParticipant::generateUniqueLink(),
                'completed' => false,
            ]);

            $mailable = new SurveyInvitationMail($survey, $participant->unique_link);

            if ($from = config('filament-surveys.invite_mail_from')) {
                $mailable->from($from);
            }

            $mailer = Mail::to($participant->email);
            if ($queue = config('filament-surveys.invite_queue')) {
                $mailer->queue($mailable->onQueue($queue));
            } else {
                $mailer->send($mailable);
            }
        }

        return redirect()->back()->with('success', 'Invitaciones enviadas');
    }

    public function showSurvey(Survey $survey, $model_id): View | RedirectResponse
    {

        if (SurveyResponse::where('model_id', $model_id)->exists()) {
            return redirect()->route('survey.thanks');
        }
        if ($survey) {
            return redirect()->route('survey.not_available')->with('error', 'Enlace inválido o encuesta ya completada');
        }

        return view('filament-surveys::survey.fill', ['survey' => $survey, 'model_id' => $model_id]);
    }

    public function submitSurvey(SubmitSurveyResponseRequest $request, string $unique_link): RedirectResponse
    {
        /** @var SurveyParticipant $participant */
        $participant = $request->getParticipant();

        /** @var Survey $survey */
        $survey = $participant->survey;

        foreach ($survey->questions as $question) {
            $rules = $question->is_required ? 'required' : 'nullable';
            $rules .= $question->question_type == 'single_choice' ? '|array|size:1' : '|array';

            $request->validate([
                "question_{$question->id}" => $rules,
            ]);

            foreach ($request->input("question_{$question->id}") as $optionId) {
                SurveyResponse::create([
                    'survey_participant_id' => $participant->id,
                    'question_id' => $question->id,
                    'option_id' => $optionId,
                ]);
            }
        }

        // Marcar encuesta como completada
        $participant->update([
            'completed' => true,
            'completed_at' => now(),
        ]);

        return redirect()->route('survey.thanks');
    }
}
