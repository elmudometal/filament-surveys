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
        if (SurveyResponse::query()
            ->where('model_type', config('filament-surveys.model_type'))
            ->where('model_id', $model_id)->exists()) {
            return redirect()->route('survey.thanks');
        }

        return view('filament-surveys::survey.fill', ['survey' => $survey, 'model_id' => $model_id]);
    }

    public function submitSurvey(SubmitSurveyResponseRequest $request, Survey $survey, $model_id): RedirectResponse
    {
        foreach ($survey->questions as $question) {
            $rules = $question->is_required ? 'required' : 'nullable';
            $rules .= $question->question_type == 'single_choice' ? '|array|size:1' : '|array';

            $request->validate([
                "question_{$question->id}" => $rules,
            ]);

            foreach ($request->input("question_{$question->id}") as $optionId) {
                $justify = match ($question->question_type) {
                    'simple' => $request->input("question_{$question->id}_justify")[$question->id] ?? null,
                    default => $request->input("question_{$question->id}_justify")[$optionId] ?? null,
                };

                SurveyResponse::create([
                    'model_type' => config('filament-surveys.model_type'),
                    'model_id' => $model_id,
                    'question_id' => $question->id,
                    'justify' => $justify,
                    'option_id' => $optionId,
                ]);
            }
        }

        return redirect()->route('survey.thanks');
    }
}
