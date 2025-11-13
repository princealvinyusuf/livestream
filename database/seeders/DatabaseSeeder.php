<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\EmployerProfile;
use App\Models\Job;
use App\Models\Review;
use App\Models\StreamerProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Throwable;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $roles = $this->seedRoles();

            $admin = User::factory()
                ->admin()
                ->create([
                    'name' => 'Admin Livestream',
                    'email' => 'admin@livestream.test',
                    'password' => bcrypt('password'),
                    'verified' => true,
                ]);
            $admin->assignRole($roles['admin']);

            $employers = $this->createEmployers($roles['employer']);
            $streamers = $this->createStreamers($roles['streamer']);

            $jobs = $this->createJobs($employers);

            $this->seedApplications($jobs, $streamers);
            $this->seedReviews($jobs, $streamers);

            try {
                Job::query()->searchable();
                StreamerProfile::query()->searchable();
            } catch (Throwable $exception) {
                report($exception);
            }
        });
    }

    /**
     * @return array<string, Role>
     */
    protected function seedRoles(): array
    {
        return collect(['streamer', 'employer', 'admin'])
            ->mapWithKeys(function (string $role) {
                $model = Role::firstOrCreate(
                    ['name' => $role, 'guard_name' => 'web'],
                    ['guard_name' => 'web'],
                );

                return [$role => $model];
            })
            ->all();
    }

    /**
     * @param  Role  $role
     * @return \Illuminate\Support\Collection<int, User>
     */
    protected function createEmployers(Role $role): Collection
    {
        return User::factory(5)
            ->employer()
            ->create()
            ->each(function (User $employer) use ($role) {
                $employer->assignRole($role);
                $employer->update([
                    'verified' => fake()->boolean(80),
                ]);

                $profile = EmployerProfile::factory()->make();
                $employer->employerProfile()->save($profile);
            });
    }

    /**
     * @param  Role  $role
     * @return \Illuminate\Support\Collection<int, User>
     */
    protected function createStreamers(Role $role): Collection
    {
        return User::factory(10)
            ->streamer()
            ->create()
            ->each(function (User $streamer) use ($role) {
                $streamer->assignRole($role);
                $streamer->update([
                    'verified' => fake()->boolean(50),
                ]);

                $profile = StreamerProfile::factory()->make();
                $streamer->streamerProfile()->save($profile);
            });
    }

    /**
     * @param  \Illuminate\Support\Collection<int, User>  $employers
     * @return \Illuminate\Support\Collection<int, Job>
     */
    protected function createJobs(Collection $employers): Collection
    {
        return $employers->flatMap(function (User $employer) {
            return Job::factory(2)->state([
                'employer_id' => $employer->id,
                'status' => 'open',
            ])->create();
        });
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Job>  $jobs
     * @param  \Illuminate\Support\Collection<int, User>  $streamers
     */
    protected function seedApplications(Collection $jobs, Collection $streamers): void
    {
        $jobs->each(function (Job $job) use ($streamers) {
            $applicants = $streamers->random(fake()->numberBetween(3, 5));

            $applicants->each(function (User $streamer, int $index) use ($job) {
                $status = match (true) {
                    $index === 0 => 'accepted',
                    $index === 1 => 'reviewed',
                    default => fake()->randomElement(['pending', 'rejected']),
                };

                Application::factory()->create([
                    'job_id' => $job->id,
                    'streamer_id' => $streamer->id,
                    'employer_id' => $job->employer_id,
                    'status' => $status,
                    'accepted_at' => $status === 'accepted' ? now()->subDays(fake()->numberBetween(1, 14)) : null,
                    'declined_at' => $status === 'rejected' ? now()->subDays(fake()->numberBetween(1, 14)) : null,
                ]);
            });
        });
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Job>  $jobs
     * @param  \Illuminate\Support\Collection<int, User>  $streamers
     */
    protected function seedReviews(Collection $jobs, Collection $streamers): void
    {
        $completedJobs = $jobs->take(4);

        $completedJobs->each(function (Job $job) use ($streamers) {
            $streamer = $streamers->random();

            Review::factory()->create([
                'job_id' => $job->id,
                'employer_id' => $job->employer_id,
                'streamer_id' => $streamer->id,
                'rating' => fake()->numberBetween(4, 5),
                'published_at' => now()->subDays(fake()->numberBetween(1, 20)),
                'is_public' => true,
            ]);

            $job->update(['status' => 'completed']);
        });

        // Refresh average rating for streamers
        $streamers->each(function (User $streamer) {
            $reviews = $streamer->reviewsReceived;

            if ($reviews->isNotEmpty()) {
                $streamer->streamerProfile?->update([
                    'rating' => round($reviews->avg('rating'), 2),
                    'review_count' => $reviews->count(),
                    'verified_at' => $streamer->streamerProfile?->verified_at ?? now(),
                ]);
            }
        });
    }
}
