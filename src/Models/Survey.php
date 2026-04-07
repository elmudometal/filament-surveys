<?php

namespace ElmudoDev\FilamentSurveys\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property array|null $sections
 * @property string|null $description
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property string|null $model_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Survey extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'title',
        'sections',
        'description',
        'start_date',
        'end_date',
        'model_type',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'sections' => 'array',
        ];
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * @return HasMany<SurveyQuestion>
     */
    public function questions(): HasMany
    {
        return $this->hasMany(SurveyQuestion::class);
    }
}
