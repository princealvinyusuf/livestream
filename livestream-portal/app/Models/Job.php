<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Job extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'employer_id',
        'title',
        'slug',
        'category',
        'livestream_platform',
        'location',
        'budget',
        'currency',
        'duration_hours',
        'start_date',
        'application_deadline',
        'is_remote',
        'is_featured',
        'status',
        'published_at',
        'description',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'duration_hours' => 'integer',
        'start_date' => 'date',
        'application_deadline' => 'date',
        'published_at' => 'datetime',
        'is_remote' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function streamers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'applications', 'job_id', 'streamer_id')
            ->withPivot(['status', 'proposed_rate', 'proposed_duration_hours', 'is_invited'])
            ->withTimestamps();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function searchableAs(): string
    {
        return 'jobs';
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'category' => $this->category,
            'description' => strip_tags($this->description),
            'budget' => (float) $this->budget,
            'currency' => $this->currency,
            'duration_hours' => $this->duration_hours,
            'start_date' => optional($this->start_date)->toDateString(),
            'published_at' => optional($this->published_at)->timestamp,
            'location' => $this->location,
            'livestream_platform' => $this->livestream_platform,
            'status' => $this->status,
            'is_remote' => (bool) $this->is_remote,
            'employer_name' => $this->employer?->name,
        ];
    }
}
