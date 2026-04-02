<?php

namespace ElmudoDev\FilamentSurveys\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $survey_id
 * @property string $email
 * @property string $unique_link
 * @property bool $completed
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Survey $survey
 * @property-read \Illuminate\Database\Eloquent\Collection<int, SurveyResponse> $responses
 */
class SurveyParticipant extends Model
{
    protected $fillable = [
        'survey_id',
        'email',
        'unique_link',
        'completed',
        'completed_at',
    ];

    public static function generateUniqueLink(): string
    {
        $len = Config::int('filament-surveys.link_length', 32);
        do {
            $link = Str::random($len);
        } while (self::where('unique_link', $link)->exists());

        return $link;
    }

    /**
     * @return BelongsTo<Survey, SurveyParticipant>
     */
    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    /**
     * @return HasMany<SurveyResponse>
     */
    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }
}
