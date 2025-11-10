<?php

namespace ElmudoDev\FilamentSurveys\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResponse extends Model
{
    protected $fillable = [
        'survey_participant_id',
        'question_id',
        'option_id',
    ];

    /**
     * @return BelongsTo<SurveyParticipant, $this>
     */
    public function participant(): BelongsTo
    {
        return $this->belongsTo(SurveyParticipant::class, 'survey_participant_id');
    }

    /**
     * @return BelongsTo<SurveyQuestion, $this>
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class);
    }

    /**
     * @return BelongsTo<SurveyOption, $this>
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(SurveyOption::class);
    }
}
