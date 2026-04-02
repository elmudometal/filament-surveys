<?php

namespace ElmudoDev\FilamentSurveys\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $question_id
 * @property string $option_text
 * @property bool $option_justify
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read SurveyQuestion $question
 */
class SurveyOption extends Model
{
    protected $fillable = [
        'question_id',
        'option_text',
        'option_justify',
    ];

    protected function casts(): array
    {
        return [
            'option_justify' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<SurveyQuestion, SurveyOption>
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class);
    }
}
