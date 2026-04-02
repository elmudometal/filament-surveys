<?php

namespace ElmudoDev\FilamentSurveys\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $survey_id
 * @property string $question_text
 * @property string|null $question_section
 * @property string $question_type
 * @property bool $is_required
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Survey $survey
 * @property-read \Illuminate\Database\Eloquent\Collection<int, SurveyOption> $options
 */
class SurveyQuestion extends Model
{
    protected $fillable = [
        'survey_id',
        'question_text',
        'question_type',
        'is_required',
        'question_section',
    ];

    /**
     * @return BelongsTo<Survey, SurveyQuestion>
     */
    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    /**
     * @return HasMany<SurveyOption>
     */
    public function options(): HasMany
    {
        return $this->hasMany(SurveyOption::class, 'question_id');
    }
}
