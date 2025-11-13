<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'employer_id',
        'streamer_id',
        'rating',
        'comment',
        'tags',
        'is_public',
        'published_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'tags' => 'array',
        'is_public' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function streamer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'streamer_id');
    }
}
