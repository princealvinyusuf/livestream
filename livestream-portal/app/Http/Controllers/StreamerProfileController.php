<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\StreamerProfile;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StreamerProfileController extends Controller
{
    public function show(StreamerProfile $streamerProfile): Response
    {
        $streamerProfile->load([
            'user',
            'reviews' => fn ($query) => $query->with(['employer:id,name', 'job:id,title,slug'])->latest()->limit(6),
        ]);

        $jobs = Job::query()
            ->where('status', 'open')
            ->where('category', $streamerProfile->category)
            ->orderByDesc('published_at')
            ->limit(4)
            ->get(['id', 'title', 'slug', 'budget', 'currency', 'duration_hours', 'start_date']);

        return Inertia::render('Streamers/Show', [
            'profile' => [
                'id' => $streamerProfile->user_id,
                'name' => $streamerProfile->user?->name,
                'headline' => $streamerProfile->headline,
                'category' => $streamerProfile->category,
                'bio' => $streamerProfile->bio,
                'location' => trim($streamerProfile->city.' '.$streamerProfile->country),
                'experience_years' => $streamerProfile->experience_years,
                'hours_streamed' => $streamerProfile->hours_streamed,
                'hourly_rate' => $streamerProfile->hourly_rate,
                'rating' => $streamerProfile->rating,
                'review_count' => $streamerProfile->review_count,
                'is_available' => $streamerProfile->is_available,
                'available_from' => $streamerProfile->available_from,
                'available_to' => $streamerProfile->available_to,
                'social_links' => $streamerProfile->social_links,
                'portfolio_media' => $streamerProfile->portfolio_media,
                'verified_at' => $streamerProfile->verified_at?->toDateString(),
            ],
            'reviews' => $streamerProfile->reviews->map(fn ($review) => [
                'id' => $review->id,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'created_at' => optional($review->created_at)->toDateString(),
                'employer' => [
                    'id' => $review->employer?->id,
                    'name' => $review->employer?->name,
                ],
                'job' => [
                    'title' => $review->job?->title,
                    'slug' => $review->job?->slug,
                ],
            ]),
            'recommendedJobs' => $jobs->map(fn (Job $job) => [
                'id' => $job->id,
                'title' => $job->title,
                'slug' => $job->slug,
                'budget' => (float) $job->budget,
                'currency' => $job->currency,
                'duration_hours' => $job->duration_hours,
                'start_date' => optional($job->start_date)->toDateString(),
            ]),
        ]);
    }
}
