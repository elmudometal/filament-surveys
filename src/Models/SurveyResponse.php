<?php

namespace ElmudoDev\FilamentSurveys\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property int $question_id
 * @property int $option_id
 * @property string|null $justify
 * @property int $survey_participant_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model $model
 * @property-read SurveyParticipant $participant
 * @property-read SurveyQuestion $question
 * @property-read SurveyOption $option
 */
class SurveyResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_type',
        'model_id',
        'question_id',
        'option_id',
        'justify',
    ];

    /**
     * @return BelongsTo<SurveyParticipant, SurveyResponse>
     */
    public function participant(): BelongsTo
    {
        return $this->belongsTo(SurveyParticipant::class, 'survey_participant_id');
    }

    /**
     * @return BelongsTo<SurveyQuestion, SurveyResponse>
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class);
    }

    /**
     * @return BelongsTo<SurveyOption, SurveyResponse>
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(SurveyOption::class);
    }
}
