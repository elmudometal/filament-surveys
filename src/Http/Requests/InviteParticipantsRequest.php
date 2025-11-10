<?php

namespace ElmudoDev\FilamentSurveys\Http\Requests;

use Closure;
use ElmudoDev\FilamentSurveys\Models\Survey;
use ElmudoDev\FilamentSurveys\Models\SurveyParticipant;
use Illuminate\Foundation\Http\FormRequest;

class InviteParticipantsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<(Closure)|string>>
     **/
    public function rules(): array
    {
        return [
            'emails' => ['required', 'array', 'min:1', 'max:100'],
            'emails.*' => [
                'required',
                'email:rfc,dns',
                'distinct',
                function ($attribute, $value, $fail) {
                    /** @var Survey $survey */
                    $survey = $this->route('survey');

                    if ($survey instanceof Survey) {
                        $existingParticipant = SurveyParticipant::where('survey_id', $survey->id)
                            ->where('email', $value)
                            ->first();

                        if ($existingParticipant) {
                            $fail("El correo $value ya ha sido invitado a esta encuesta.");
                        }
                    }
                },
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'emails.required' => 'Debe proporcionar al menos un correo electrónico.',
            'emails.max' => 'No puede invitar a más de 100 participantes a la vez.',
            'emails.*.email' => 'Algunos de los correos electrónicos no son válidos.',
            'emails.*.distinct' => 'No puede invitar correos electrónicos duplicados.',
        ];
    }
}
