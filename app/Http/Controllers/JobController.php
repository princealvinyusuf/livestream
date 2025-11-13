<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class JobController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'platform' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'min_budget' => ['nullable', 'numeric', 'min:0'],
            'max_budget' => ['nullable', 'numeric', 'gte:min_budget'],
            'status' => ['nullable', Rule::in(['open', 'ongoing', 'completed'])],
        ]);

        $jobsQuery = Job::query()
            ->with('employer:id,name')
            ->orderByDesc('published_at')
            ->where('status', '!=', 'draft');

        if (! empty($filters['search'])) {
            $driver = config('scout.driver');

            if (in_array($driver, ['meilisearch', 'algolia', 'typesense'], true)) {
                try {
                    $jobIds = Job::search($filters['search'])->keys();

                    if ($jobIds->isEmpty()) {
                        $jobsQuery->whereRaw('0 = 1');
                    } else {
                        $jobsQuery->whereIn('id', $jobIds);
                    }
                } catch (\Throwable $exception) {
                    report($exception);
                    $jobsQuery->where(function ($query) use ($filters) {
                        $query->where('title', 'like', '%'.$filters['search'].'%')
                            ->orWhere('description', 'like', '%'.$filters['search'].'%');
                    });
                }
            } else {
                $jobsQuery->where(function ($query) use ($filters) {
                    $query->where('title', 'like', '%'.$filters['search'].'%')
                        ->orWhere('description', 'like', '%'.$filters['search'].'%');
                });
            }
        }

        if (! empty($filters['category'])) {
            $jobsQuery->where('category', $filters['category']);
        }

        if (! empty($filters['platform'])) {
            $jobsQuery->where('livestream_platform', $filters['platform']);
        }

        if (! empty($filters['location'])) {
            $jobsQuery->where(function ($query) use ($filters) {
                $query->where('location', 'like', '%'.$filters['location'].'%')
                    ->orWhere('is_remote', true);
            });
        }

        if (! empty($filters['status'])) {
            $jobsQuery->where('status', $filters['status']);
        }

        if (! empty($filters['min_budget'])) {
            $jobsQuery->where('budget', '>=', $filters['min_budget']);
        }

        if (! empty($filters['max_budget'])) {
            $jobsQuery->where('budget', '<=', $filters['max_budget']);
        }

        $jobs = $jobsQuery->paginate(12)->withQueryString();

        return Inertia::render('Jobs/Index', [
            'jobs' => $jobs->through(fn (Job $job) => [
                'id' => $job->id,
                'title' => $job->title,
                'slug' => $job->slug,
                'category' => $job->category,
                'budget' => (float) $job->budget,
                'currency' => $job->currency,
                'location' => $job->location,
                'livestream_platform' => $job->livestream_platform,
                'start_date' => optional($job->start_date)->toDateString(),
                'duration_hours' => $job->duration_hours,
                'status' => $job->status,
                'employer' => [
                    'id' => $job->employer?->id,
                    'name' => $job->employer?->name,
                ],
            ]),
            'filters' => $filters,
            'meta' => [
                'categories' => Job::query()
                    ->select('category')
                    ->whereNotNull('category')
                    ->distinct()
                    ->orderBy('category')
                    ->pluck('category'),
                'platforms' => Job::query()
                    ->select('livestream_platform')
                    ->whereNotNull('livestream_platform')
                    ->distinct()
                    ->orderBy('livestream_platform')
                    ->pluck('livestream_platform'),
            ],
        ]);
    }

    public function show(Request $request, Job $job): Response
    {
        $job->load([
            'employer.employerProfile',
            'applications.streamer.streamerProfile',
        ])->loadCount('applications');

        $similarJobs = Job::query()
            ->where('id', '!=', $job->id)
            ->where('category', $job->category)
            ->where('status', 'open')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get(['id', 'title', 'slug', 'budget', 'currency', 'duration_hours', 'start_date']);

        $user = $request->user();
        $application = null;

        if ($user && $user->isStreamer()) {
            $application = Application::query()
                ->where('job_id', $job->id)
                ->where('streamer_id', $user->id)
                ->first([
                    'id',
                    'status',
                    'proposed_rate',
                    'proposed_duration_hours',
                    'cover_letter',
                ]);
        }

        return Inertia::render('Jobs/Show', [
            'job' => [
                'id' => $job->id,
                'title' => $job->title,
                'slug' => $job->slug,
                'category' => $job->category,
                'description' => $job->description,
                'budget' => (float) $job->budget,
                'currency' => $job->currency,
                'location' => $job->location,
                'livestream_platform' => $job->livestream_platform,
                'duration_hours' => $job->duration_hours,
                'start_date' => optional($job->start_date)->toDateString(),
                'application_deadline' => optional($job->application_deadline)->toDateString(),
                'status' => $job->status,
                'applications_count' => $job->applications_count,
                'employer' => [
                    'id' => $job->employer?->id,
                    'name' => $job->employer?->name,
                    'company' => $job->employer?->employerProfile?->company_name,
                    'is_verified' => $job->employer?->employerProfile?->is_verified,
                ],
                'applications' => $user && $user->isEmployer()
                    ? $job->applications->map(fn (Application $application) => [
                        'id' => $application->id,
                        'status' => $application->status,
                        'proposed_rate' => $application->proposed_rate,
                        'streamer' => [
                            'id' => $application->streamer?->id,
                            'name' => $application->streamer?->name,
                            'category' => $application->streamer?->streamerProfile?->category,
                            'rating' => $application->streamer?->streamerProfile?->rating,
                        ],
                    ])
                    : null,
            ],
            'application' => $application,
            'similarJobs' => $similarJobs->map(fn (Job $similar) => [
                'id' => $similar->id,
                'title' => $similar->title,
                'slug' => $similar->slug,
                'budget' => (float) $similar->budget,
                'currency' => $similar->currency,
                'duration_hours' => $similar->duration_hours,
                'start_date' => optional($similar->start_date)->toDateString(),
            ]),
        ]);
    }

    public function create(Request $request): Response
    {
        $request->user()->isEmployer() || abort(403);

        $platforms = ['TikTok', 'Shopee Live', 'Lazada Live', 'Tokopedia', 'Custom Platform'];
        $categories = Job::query()->select('category')->distinct()->pluck('category')->filter()->values();

        return Inertia::render('Jobs/Create', [
            'defaults' => [
                'category' => $categories->first() ?? 'Fashion',
                'platforms' => $platforms,
                'categories' => $categories,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $user->isEmployer() || abort(403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'livestream_platform' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'budget' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:3'],
            'duration_hours' => ['nullable', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'application_deadline' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_remote' => ['nullable', 'boolean'],
            'description' => ['required', 'string'],
            'status' => ['required', Rule::in(['draft', 'open'])],
        ]);

        $slug = Str::slug($data['title']).'-'.Str::random(6);

        $job = $user->postedJobs()->create([
            ...$data,
            'slug' => $slug,
            'is_remote' => $data['is_remote'] ?? true,
            'is_featured' => false,
            'published_at' => $data['status'] === 'open' ? now() : null,
        ]);

        return redirect()
            ->route('jobs.show', $job->slug)
            ->with('success', 'Job berhasil dibuat dan dipublikasikan.');
    }

    public function apply(Request $request, Job $job): RedirectResponse
    {
        $user = $request->user();
        $user->isStreamer() || abort(403);

        if ($job->status !== 'open') {
            return back()->with('error', 'Job ini tidak lagi terbuka untuk lamaran.');
        }

        $data = $request->validate([
            'cover_letter' => ['required', 'string', 'max:2000'],
            'proposed_rate' => ['required', 'numeric', 'min:0'],
            'proposed_duration_hours' => ['required', 'integer', 'min:1'],
        ]);

        Application::updateOrCreate(
            [
                'job_id' => $job->id,
                'streamer_id' => $user->id,
            ],
            [
                'employer_id' => $job->employer_id,
                'status' => 'pending',
                'cover_letter' => $data['cover_letter'],
                'proposed_rate' => $data['proposed_rate'],
                'proposed_duration_hours' => $data['proposed_duration_hours'],
                'is_invited' => false,
                'invited_at' => null,
                'responded_at' => null,
                'accepted_at' => null,
                'declined_at' => null,
            ],
        );

        return back()->with('success', 'Lamaran berhasil dikirim.');
    }
}
