<?php

namespace ElmudoDev\FilamentSurveys\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;

/**
 * @property int $id
 * @property int $survey_id
 * @property string $email
 * @property string $unique_link
 * @property bool $completed
 * @property Carbon|null $completed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Survey $survey
 * @property-read Collection<int, SurveyResponse> $responses
 */
class SurveyParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'email',
        'unique_link',
        'completed',
        'completed_at',
    ];

    public static function generateUniqueLink(): string
    {
        do {
            $link = Uuid::uuid4();
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
