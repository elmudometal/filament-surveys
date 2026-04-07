<?php

namespace ElmudoDev\FilamentSurveys\Models;

use ElmudoDev\FilamentSurveys\Database\Factories\SurveyQuestionFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $survey_id
 * @property string $question_text
 * @property string|null $question_section
 * @property string $question_type
 * @property bool $is_required
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Survey $survey
 * @property-read Collection<int, SurveyOption> $options
 */
class SurveyQuestion extends Model
{
    /** @use HasFactory<SurveyQuestionFactory> */
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'question_text',
        'question_type',
        'is_required',
        'question_section',
    ];

    /**
     * @return BelongsTo<Survey, $this>
     */
    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    /**
     * @return HasMany<SurveyOption, $this>
     */
    public function options(): HasMany
    {
        return $this->hasMany(SurveyOption::class, 'question_id');
    }

    protected static function newFactory(): SurveyQuestionFactory
    {
        return SurveyQuestionFactory::new();
    }
}
