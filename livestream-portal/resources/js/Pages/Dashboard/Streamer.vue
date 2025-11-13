<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    profile: {
        type: Object,
        default: () => ({}),
    },
    applications: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({
            total_applications: 0,
            pending_applications: 0,
            accepted_applications: 0,
            estimated_income: 0,
        }),
    },
    recommendedJobs: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Dashboard Streamer" />

        <template #header>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold leading-tight text-gray-900">
                        Selamat datang, {{ $page.props.auth.user.name }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        Kelola profil dan lamar job live streaming dengan mudah.
                    </p>
                </div>
                <div class="flex gap-3">
                    <Link
                        :href="route('jobs.index')"
                        class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500"
                    >
                        Cari Job
                    </Link>
                    <Link
                        :href="route('profile.edit')"
                        class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-100"
                    >
                        Edit Profil
                    </Link>
                </div>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-10 px-4 py-6 sm:px-6 lg:px-8">
            <section>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-6">
                        <p class="text-sm font-medium text-indigo-600">Total Lamaran</p>
                        <p class="mt-2 text-3xl font-semibold text-indigo-900">
                            {{ stats.total_applications }}
                        </p>
                        <p class="mt-1 text-xs text-indigo-500">Akumulasi semua lamaran job</p>
                    </div>
                    <div class="rounded-2xl border border-amber-100 bg-amber-50 p-6">
                        <p class="text-sm font-medium text-amber-600">Menunggu Konfirmasi</p>
                        <p class="mt-2 text-3xl font-semibold text-amber-900">
                            {{ stats.pending_applications }}
                        </p>
                        <p class="mt-1 text-xs text-amber-500">Lamaran dalam proses review employer</p>
                    </div>
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-6">
                        <p class="text-sm font-medium text-emerald-600">Lamaran Diterima</p>
                        <p class="mt-2 text-3xl font-semibold text-emerald-900">
                            {{ stats.accepted_applications }}
                        </p>
                        <p class="mt-1 text-xs text-emerald-500">Job aktif atau selesai</p>
                    </div>
                    <div class="rounded-2xl border border-purple-100 bg-purple-50 p-6">
                        <p class="text-sm font-medium text-purple-600">Estimasi Pendapatan</p>
                        <p class="mt-2 text-3xl font-semibold text-purple-900">
                            Rp {{ Number(stats.estimated_income || 0).toLocaleString('id-ID') }}
                        </p>
                        <p class="mt-1 text-xs text-purple-500">Dari job yang telah diterima</p>
                    </div>
                </div>
            </section>

            <section class="grid gap-8 lg:grid-cols-3">
                <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm lg:col-span-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Lamaran Terbaru</h3>
                            <p class="text-sm text-gray-500">Monitor status lamaran Anda.</p>
                        </div>
                        <Link
                            :href="route('jobs.index')"
                            class="text-sm font-semibold text-indigo-600 hover:text-indigo-500"
                        >
                            Lihat semua job
                        </Link>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div
                            v-if="!applications.length"
                            class="rounded-2xl border border-dashed border-gray-200 p-8 text-center"
                        >
                            <p class="text-sm font-medium text-gray-700">Belum ada lamaran</p>
                            <p class="mt-2 text-sm text-gray-500">
                                Mulai lamar job untuk menampilkan status di sini.
                            </p>
                            <Link
                                :href="route('jobs.index')"
                                class="mt-4 inline-flex items-center rounded-full bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                            >
                                Explore Job
                            </Link>
                        </div>
                        <article
                            v-for="application in applications"
                            :key="application.id"
                            class="flex flex-col gap-4 rounded-2xl border border-gray-100 bg-gray-50/60 p-5 transition hover:border-indigo-200 hover:bg-white"
                        >
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h4 class="text-base font-semibold text-gray-900">
                                        <Link
                                            :href="route('jobs.show', application.job?.slug)"
                                            class="hover:text-indigo-600"
                                        >
                                            {{ application.job?.title }}
                                        </Link>
                                    </h4>
                                    <p class="text-xs uppercase tracking-wide text-gray-500">
                                        {{ application.job?.category }} • {{ application.job?.start_date || 'Jadwal fleksibel' }}
                                    </p>
                                </div>
                                <span
                                    class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold"
                                    :class="{
                                        'bg-emerald-100 text-emerald-700': application.status === 'accepted',
                                        'bg-amber-100 text-amber-700': application.status === 'pending' || application.status === 'reviewed',
                                        'bg-rose-100 text-rose-700': application.status === 'rejected',
                                        'bg-gray-200 text-gray-700': !['accepted', 'pending', 'reviewed', 'rejected'].includes(application.status),
                                    }"
                                >
                                    {{ application.status }}
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center justify-between gap-2 text-sm text-gray-600">
                                <div class="flex items-center gap-2">
                                    <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-700">
                                        {{ application.employer?.name || 'Employer' }}
                                    </span>
                                    <span>Propose rate:</span>
                                    <strong>Rp {{ Number(application.proposed_rate || 0).toLocaleString('id-ID') }}</strong>
                                </div>
                                <Link
                                    :href="route('jobs.show', application.job?.slug)"
                                    class="text-sm font-semibold text-indigo-600 hover:text-indigo-500"
                                >
                                    Detail job
                                </Link>
                            </div>
                        </article>
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">Profil Singkat</h3>
                        <p class="mt-1 text-sm text-gray-500">Update profil untuk tingkatkan peluang job.</p>
                        <div class="mt-4 space-y-3 text-sm text-gray-600">
                            <p>
                                <span class="font-medium text-gray-900">Kategori:</span>
                                {{ profile?.category || 'Belum diatur' }}
                            </p>
                            <p>
                                <span class="font-medium text-gray-900">Headline:</span>
                                {{ profile?.headline || 'Tambahkan highlight kemampuan Anda' }}
                            </p>
                            <p>
                                <span class="font-medium text-gray-900">Rating:</span>
                                {{ Number(profile?.rating || 0).toFixed(1) }} ({{ profile?.review_count || 0 }} ulasan)
                            </p>
                            <p>
                                <span class="font-medium text-gray-900">Status:</span>
                                <span
                                    class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold"
                                    :class="profile?.is_available ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'"
                                >
                                    {{ profile?.is_available ? 'Tersedia' : 'Tidak tersedia' }}
                                </span>
                            </p>
                        </div>
                        <Link
                            :href="route('profile.edit')"
                            class="mt-6 inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-500"
                        >
                            Perbarui profil
                        </Link>
                    </div>

                    <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">Rekomendasi Job</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Disesuaikan dengan kategori keahlian Anda.
                        </p>
                        <div class="mt-5 space-y-4">
                            <div
                                v-if="!recommendedJobs.length"
                                class="rounded-2xl border border-dashed border-gray-200 p-6 text-center text-sm text-gray-500"
                            >
                                Job rekomendasi akan muncul setelah melengkapi profil.
                            </div>
                            <div
                                v-for="job in recommendedJobs"
                                :key="job.id"
                                class="rounded-2xl border border-gray-100 bg-gray-50/60 p-4 transition hover:border-indigo-200 hover:bg-white"
                            >
                                <h4 class="text-sm font-semibold text-gray-900">
                                    <Link :href="route('jobs.show', job.slug)" class="hover:text-indigo-600">
                                        {{ job.title }}
                                    </Link>
                                </h4>
                                <p class="mt-1 text-xs uppercase tracking-wide text-gray-500">
                                    {{ job.category }} • Durasi {{ job.duration_hours }} jam
                                </p>
                                <p class="mt-2 text-sm text-gray-600">
                                    Budget: <span class="font-semibold text-indigo-600">{{ job.currency }} {{ Number(job.budget || 0).toLocaleString('id-ID') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </aside>
            </section>
        </div>
    </AuthenticatedLayout>
</template>

