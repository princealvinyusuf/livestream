<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class StreamerProfile extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'user_id',
        'headline',
        'category',
        'city',
        'country',
        'bio',
        'experience_years',
        'hours_streamed',
        'hourly_rate',
        'social_links',
        'portfolio_media',
        'rating',
        'review_count',
        'is_available',
        'primary_language',
        'secondary_languages',
        'available_from',
        'available_to',
        'verified_at',
    ];

    protected $casts = [
        'social_links' => 'array',
        'portfolio_media' => 'array',
        'rating' => 'decimal:2',
        'is_available' => 'boolean',
        'verified_at' => 'datetime',
        'hourly_rate' => 'decimal:2',
        'available_from' => 'datetime:H:i',
        'available_to' => 'datetime:H:i',
    ];

    public function getRouteKeyName(): string
    {
        return 'user_id';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'streamer_id', 'user_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'streamer_id', 'user_id');
    }

    public function searchableAs(): string
    {
        return 'streamer_profiles';
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->user_id,
            'user_id' => $this->user_id,
            'headline' => $this->headline,
            'category' => $this->category,
            'bio' => $this->bio,
            'city' => $this->city,
            'country' => $this->country,
            'experience_years' => $this->experience_years,
            'hours_streamed' => $this->hours_streamed,
            'hourly_rate' => $this->hourly_rate,
            'rating' => $this->rating,
            'review_count' => $this->review_count,
            'is_available' => (bool) $this->is_available,
            'primary_language' => $this->primary_language,
            'secondary_languages' => $this->secondary_languages,
            'verified_at' => optional($this->verified_at)->timestamp,
        ];
    }
}
