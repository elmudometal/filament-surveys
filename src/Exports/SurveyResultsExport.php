<?php

namespace ElmudoDev\FilamentSurveys\Exports;

use Carbon\Carbon;
use ElmudoDev\FilamentSurveys\Models\SurveyResponse;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * @implements WithMapping<SurveyResponse>
 */
class SurveyResultsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    public function __construct(protected int $surveyId) {}

    /**
     * @return \Illuminate\Support\Collection<int, SurveyResponse>
     */
    public function collection()
    {
        return SurveyResponse::query()->with(['question', 'question.survey', 'option'])->get();
    }

    /**
     * @return array<string>
     */
    public function headings(): array
    {
        return [
            'ID Respuesta',
            'Encuesta',
            'Pregunta',
            'Tipo de Pregunta',
            'Respuesta',
            'Detalle Respuesta',
            'Fecha de Respuesta',
        ];
    }

    /**
     * @param  SurveyResponse  $response
     * @return array{int, ?string, ?string, ?string, ?string, ?string, ?Carbon}
     */
    public function map($response): array
    {
        return [
            $response->id,
            $response->question?->survey?->title,
            $response->question?->question_text,
            // TODO: cambiar a enums
            $response->question?->question_type == 'free_text' ? 'Abierta' : 'Simple',
            $response->option?->option_text,
            $response->justify,
            $response->created_at,
        ];
    }
}
