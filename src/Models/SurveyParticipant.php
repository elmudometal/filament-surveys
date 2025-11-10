<?php

namespace ElmudoDev\FilamentSurveys\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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
        $len = (int) config('filament-surveys.link_length', 32);
        do {
            $link = Str::random($len);
        } while (self::where('unique_link', $link)->exists());

        return $link;
    }

    /**
     * @return BelongsTo<Survey, $this>
     */
    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    /**
     * @return HasMany<SurveyResponse, $this>
     */
    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }
}
