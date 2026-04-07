<?php

use ElmudoDev\FilamentSurveys\Mail\SurveyInvitationMail;
use ElmudoDev\FilamentSurveys\Models\Survey;
use ElmudoDev\FilamentSurveys\Models\SurveyOption;
use ElmudoDev\FilamentSurveys\Models\SurveyParticipant;
use ElmudoDev\FilamentSurveys\Models\SurveyQuestion;
use ElmudoDev\FilamentSurveys\Models\SurveyResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

it('can invite participants and sends emails', function () {
    Mail::fake();
    $survey = Survey::factory()->create();
    $emails = ['test1@gmail.com', 'test2@gmail.com'];

    $response = $this->from(route('survey.invite', $survey))
        ->post(route('survey.invite', $survey), [
            'emails' => $emails,
        ]);

    $response->assertStatus(302);
    $this->assertDatabaseCount('survey_participants', 2);
    foreach ($emails as $email) {
        $this->assertDatabaseHas('survey_participants', [
            'survey_id' => $survey->id,
            'email' => $email,
            'completed' => false,
        ]);
        Mail::assertSent(SurveyInvitationMail::class, function ($mail) use ($email) {
            return $mail->hasTo($email);
        });
    }
});

it('shows the survey page with signed url', function () {
    $survey = Survey::factory()->create();
    $participant = SurveyParticipant::factory()->create(['survey_id' => $survey->id]);
    $url = URL::signedRoute('survey.fill', ['survey' => $survey->slug, 'model_id' => $participant->id]);

    $response = $this->get($url);

    $response->assertStatus(200);
    $response->assertViewIs('filament-surveys::survey.fill');
    $response->assertViewHas('survey', $survey);
    $response->assertViewHas('model_id', function ($model_id) use ($participant) {
        return $model_id == $participant->id;
    });
});

it('redirects to thanks page if survey already submitted', function () {
    $survey = Survey::factory()->create();
    $participant = SurveyParticipant::factory()->create(['survey_id' => $survey->id]);
    $modelType = config('filament-surveys.model_type');

    SurveyResponse::factory()->create([
        'model_type' => $modelType,
        'model_id' => $participant->unique_link,
    ]);

    $url = URL::signedRoute('survey.fill', ['survey' => $survey->slug, 'model_id' => $participant->unique_link]);
    $response = $this->get($url);

    $response->assertRedirect(route('survey.thanks'));
});

it('can submit survey responses', function () {
    $survey = Survey::factory()->create();
    $participant = SurveyParticipant::factory()->create(['survey_id' => $survey->id]);
    $question = SurveyQuestion::factory()->create(['survey_id' => $survey->id, 'question_type' => 'single_choice', 'is_required' => true]);
    $option = SurveyOption::factory()->create(['question_id' => $question->id]);

    $modelType = config('filament-surveys.model_type');

    $response = $this->post(route('survey.submit', ['survey' => $survey->slug, 'model_id' => $participant->unique_link]), [
        "question_{$question->id}" => [$option->id],
    ]);

    $response->assertRedirect(route('survey.thanks'));
    $this->assertDatabaseHas('survey_responses', [
        'model_type' => $modelType,
        'model_id' => $participant->unique_link,
        'question_id' => $question->id,
        'option_id' => $option->id,
    ]);
});

it('validates required questions on submit', function () {
    $survey = Survey::factory()->create();
    $question = SurveyQuestion::factory()->create(['survey_id' => $survey->id, 'is_required' => true]);
    $modelId = 1;

    $response = $this->post(route('survey.submit', ['survey' => $survey->slug, 'model_id' => $modelId]), []);

    $response->assertSessionHasErrors(["question_{$question->id}"]);
});
