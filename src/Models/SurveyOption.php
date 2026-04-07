<?php

namespace ElmudoDev\FilamentSurveys\Models;

use ElmudoDev\FilamentSurveys\Database\Factories\SurveyOptionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $question_id
 * @property string $option_text
 * @property bool $option_justify
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read SurveyQuestion $question
 */
class SurveyOption extends Model
{
    /** @use HasFactory<SurveyOptionFactory> */
    use HasFactory;

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
     * @return BelongsTo<SurveyQuestion, $this>
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class);
    }

    protected static function newFactory(): SurveyOptionFactory
    {
        return SurveyOptionFactory::new();
    }
}
