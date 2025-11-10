<?php

namespace ElmudoDev\FilamentSurveys\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * @return HasMany<SurveyQuestion, $this>
     */
    public function questions(): HasMany
    {
        return $this->hasMany(SurveyQuestion::class);
    }

    /**
     * @return HasMany<SurveyParticipant, $this>
     */
    public function participants(): HasMany
    {
        return $this->hasMany(SurveyParticipant::class);
    }
}
