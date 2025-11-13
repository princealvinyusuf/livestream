<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'streamer_id',
        'employer_id',
        'status',
        'cover_letter',
        'proposed_rate',
        'proposed_duration_hours',
        'is_invited',
        'invited_at',
        'responded_at',
        'accepted_at',
        'declined_at',
        'attachments',
        'meta',
    ];

    protected $casts = [
        'proposed_rate' => 'decimal:2',
        'proposed_duration_hours' => 'integer',
        'is_invited' => 'boolean',
        'invited_at' => 'datetime',
        'responded_at' => 'datetime',
        'accepted_at' => 'datetime',
        'declined_at' => 'datetime',
        'attachments' => 'array',
        'meta' => 'array',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function streamer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'streamer_id');
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }
}
