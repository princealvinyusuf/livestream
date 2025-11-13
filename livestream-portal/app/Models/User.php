<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'verified' => 'boolean',
        ];
    }

    public function streamerProfile(): HasOne
    {
        return $this->hasOne(StreamerProfile::class);
    }

    public function employerProfile(): HasOne
    {
        return $this->hasOne(EmployerProfile::class);
    }

    public function postedJobs(): HasMany
    {
        return $this->hasMany(Job::class, 'employer_id');
    }

    public function jobApplications(): HasMany
    {
        return $this->hasMany(Application::class, 'streamer_id');
    }

    public function receivedApplications(): HasMany
    {
        return $this->hasMany(Application::class, 'employer_id');
    }

    public function reviewsGiven(): HasMany
    {
        return $this->hasMany(Review::class, 'employer_id');
    }

    public function reviewsReceived(): HasMany
    {
        return $this->hasMany(Review::class, 'streamer_id');
    }

    public function jobsAsStreamer(): BelongsToMany
    {
        return $this->belongsToMany(Job::class, 'applications', 'streamer_id', 'job_id')
            ->withPivot(['status', 'proposed_rate', 'proposed_duration_hours', 'is_invited'])
            ->withTimestamps();
    }

    public function isStreamer(): bool
    {
        return $this->role === 'streamer' || $this->hasRole('streamer');
    }

    public function isEmployer(): bool
    {
        return $this->role === 'employer' || $this->hasRole('employer');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->hasRole('admin');
    }
}
