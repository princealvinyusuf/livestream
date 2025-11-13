<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->isStreamer()) {
            return $this->streamerDashboard($user);
        }

        if ($user->isEmployer()) {
            return $this->employerDashboard($user);
        }

        return $this->adminDashboard($user);
    }

    protected function streamerDashboard(User $user): Response
    {
        $profile = $user->streamerProfile()
            ->select(['user_id', 'headline', 'category', 'rating', 'review_count', 'is_available'])
            ->first();

        $applications = $user->jobApplications()
            ->with(['job:id,title,slug,category,status,start_date', 'employer:id,name'])
            ->latest()
            ->limit(6)
            ->get()
            ->map(fn (Application $application) => [
                'id' => $application->id,
                'status' => $application->status,
                'proposed_rate' => $application->proposed_rate,
                'job' => [
                    'title' => $application->job?->title,
                    'slug' => $application->job?->slug,
                    'category' => $application->job?->category,
                    'status' => $application->job?->status,
                    'start_date' => optional($application->job?->start_date)->toDateString(),
                ],
                'employer' => [
                    'name' => $application->employer?->name,
                ],
            ]);

        $stats = [
            'total_applications' => $user->jobApplications()->count(),
            'pending_applications' => $user->jobApplications()->where('status', 'pending')->count(),
            'accepted_applications' => $user->jobApplications()->where('status', 'accepted')->count(),
            'estimated_income' => $user->jobApplications()
                ->where('status', 'accepted')
                ->sum('proposed_rate'),
        ];

        $recommendedJobs = Job::query()
            ->when($profile?->category, function ($query, $category) {
                $query->where('category', $category);
            })
            ->where('employer_id', '!=', $user->id)
            ->where('status', 'open')
            ->orderByDesc('published_at')
            ->limit(5)
            ->get(['id', 'title', 'slug', 'category', 'budget', 'currency', 'duration_hours', 'start_date'])
            ->map(fn (Job $job) => [
                'id' => $job->id,
                'title' => $job->title,
                'slug' => $job->slug,
                'category' => $job->category,
                'budget' => (float) $job->budget,
                'currency' => $job->currency,
                'duration_hours' => $job->duration_hours,
                'start_date' => optional($job->start_date)->toDateString(),
            ]);

        return Inertia::render('Dashboard/Streamer', [
            'profile' => $profile,
            'applications' => $applications,
            'stats' => $stats,
            'recommendedJobs' => $recommendedJobs,
        ]);
    }

    protected function employerDashboard(User $user): Response
    {
        $jobs = $user->postedJobs()
            ->withCount(['applications', 'applications as accepted_applications_count' => function ($query) {
                $query->where('status', 'accepted');
            }])
            ->latest('published_at')
            ->limit(6)
            ->get()
            ->map(fn (Job $job) => [
                'id' => $job->id,
                'title' => $job->title,
                'slug' => $job->slug,
                'status' => $job->status,
                'published_at' => optional($job->published_at)->toDateString(),
                'applications_count' => $job->applications_count,
                'accepted_applications_count' => $job->accepted_applications_count,
            ]);

        $recentApplications = Application::query()
            ->where('employer_id', $user->id)
            ->with(['streamer:id,name', 'job:id,title,slug'])
            ->latest()
            ->limit(6)
            ->get()
            ->map(fn (Application $application) => [
                'id' => $application->id,
                'status' => $application->status,
                'streamer' => [
                    'id' => $application->streamer?->id,
                    'name' => $application->streamer?->name,
                ],
                'job' => [
                    'title' => $application->job?->title,
                    'slug' => $application->job?->slug,
                ],
                'proposed_rate' => $application->proposed_rate,
                'created_at' => optional($application->created_at)->toDateTimeString(),
            ]);

        $stats = [
            'total_jobs' => $user->postedJobs()->count(),
            'active_jobs' => $user->postedJobs()->whereIn('status', ['open', 'ongoing'])->count(),
            'pending_applications' => Application::where('employer_id', $user->id)->where('status', 'pending')->count(),
            'completed_jobs' => $user->postedJobs()->where('status', 'completed')->count(),
        ];

        return Inertia::render('Dashboard/Employer', [
            'jobs' => $jobs,
            'applications' => $recentApplications,
            'stats' => $stats,
            'profile' => $user->employerProfile()
                ->select(['user_id', 'company_name', 'brand_name', 'is_verified'])
                ->first(),
        ]);
    }

    protected function adminDashboard(User $user): Response
    {
        $metrics = [
            'total_users' => User::count(),
            'streamers' => User::where('role', 'streamer')->count(),
            'employers' => User::where('role', 'employer')->count(),
            'jobs' => Job::count(),
            'applications' => Application::count(),
        ];

        return Inertia::render('Dashboard/Admin', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'metrics' => $metrics,
        ]);
    }
}
