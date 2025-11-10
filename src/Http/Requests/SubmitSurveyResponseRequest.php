<?php

namespace ElmudoDev\FilamentSurveys\Http\Requests;

use Closure;
use ElmudoDev\FilamentSurveys\Models\Survey;
use ElmudoDev\FilamentSurveys\Models\SurveyParticipant;
use Illuminate\Foundation\Http\FormRequest;

class SubmitSurveyResponseRequest extends FormRequest
{
    private ?SurveyParticipant $participant = null;

    private ?Survey $survey = null;

    public function authorize(): bool
    {
        // Validar que el participante exista y no haya completado la encuesta
        $this->participant = SurveyParticipant::where('unique_link', $this->route('unique_link'))
            ->where('completed', false)
            ->first();

        return $this->participant !== null;
    }

    /**
     * @return array<string, array<(Closure)|string>>
     **/
    public function rules(): array
    {
        $this->survey = $this->participant?->survey;
        $rules = [];

        if (is_iterable($this->survey?->questions)) {
            foreach ($this->survey->questions as $question) {
                $questionRules = [
                    $question->is_required ? 'required' : 'nullable',
                    function ($attribute, $value, $fail) use ($question) {
                        // Validar que las opciones existan para la pregunta
                        $validOptionIds = $question->options->pluck('id')->toArray();

                        foreach ((array) $value as $optionId) {
                            if (! in_array($optionId, $validOptionIds)) {
                                $fail('Opción inválida para la pregunta.');
                            }
                        }

                        // Validar número de opciones según tipo de pregunta
                        if ($question->question_type == 'single_choice' && is_array($value) && count($value) > 1) {
                            $fail('Solo puede seleccionar una opción para esta pregunta.');
                        }
                    },
                ];

                $rules["question_{$question->id}"] = $questionRules;
            }
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        $messages = [];

        if (is_iterable($this->survey?->questions)) {
            foreach ($this->survey->questions as $question) {
                if ($question->is_required) {
                    $messages["question_{$question->id}.required"] =
                        "La pregunta '{$question->question_text}' es obligatoria.";
                }
            }
        }

        return $messages;
    }

    public function getParticipant(): ?SurveyParticipant
    {
        return $this->participant;
    }
}
