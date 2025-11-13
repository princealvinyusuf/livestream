<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'brand_name',
        'website',
        'contact_email',
        'contact_phone',
        'city',
        'country',
        'about',
        'team_size',
        'social_links',
        'preferred_categories',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'social_links' => 'array',
        'preferred_categories' => 'array',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'employer_id', 'user_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'employer_id', 'user_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'employer_id', 'user_id');
    }
}
