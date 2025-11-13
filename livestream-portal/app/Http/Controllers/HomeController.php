<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\StreamerProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $categories = Job::query()
            ->select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->limit(6)
            ->get()
            ->map(fn ($row) => [
                'name' => $row->category,
                'total' => (int) $row->total,
            ]);

        $featuredStreamers = StreamerProfile::query()
            ->with('user')
            ->whereHas('user', fn (Builder $query) => $query->where('verified', true))
            ->orderByDesc('rating')
            ->limit(6)
            ->get()
            ->map(fn (StreamerProfile $profile) => [
                'id' => $profile->user_id,
                'name' => $profile->user?->name,
                'headline' => $profile->headline,
                'category' => $profile->category,
                'city' => $profile->city,
                'rating' => $profile->rating,
                'review_count' => $profile->review_count,
                'portfolio' => $profile->portfolio_media,
            ]);

        $latestJobs = Job::query()
            ->with('employer')
            ->latest('published_at')
            ->limit(6)
            ->get()
            ->map(fn (Job $job) => [
                'id' => $job->id,
                'title' => $job->title,
                'slug' => $job->slug,
                'category' => $job->category,
                'budget' => (float) $job->budget,
                'currency' => $job->currency,
                'start_date' => optional($job->start_date)->toDateString(),
                'duration_hours' => $job->duration_hours,
                'employer' => [
                    'id' => $job->employer?->id,
                    'name' => $job->employer?->name,
                ],
            ]);

        $metrics = [
            'streamers' => StreamerProfile::count(),
            'employers' => User::where('role', 'employer')->count(),
            'jobs' => Job::count(),
        ];

        return Inertia::render('Landing/Index', [
            'categories' => $categories,
            'featuredStreamers' => $featuredStreamers,
            'latestJobs' => $latestJobs,
            'metrics' => $metrics,
        ]);
    }
}
