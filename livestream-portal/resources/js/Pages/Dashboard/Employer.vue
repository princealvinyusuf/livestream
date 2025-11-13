<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    jobs: {
        type: Array,
        default: () => [],
    },
    applications: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({
            total_jobs: 0,
            active_jobs: 0,
            pending_applications: 0,
            completed_jobs: 0,
        }),
    },
    profile: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Dashboard Employer" />

        <template #header>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold leading-tight text-gray-900">
                        Dashboard Brand
                    </h2>
                    <p class="text-sm text-gray-500">
                        Kelola job posting dan undang streamer terbaik untuk brand Anda.
                    </p>
                </div>
                <div class="flex gap-3">
                    <Link
                        href="#"
                        class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-100"
                    >
                        Undang Streamer
                    </Link>
                    <Link
                        :href="route('jobs.create')"
                        class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500"
                    >
                        Post Job Baru
                    </Link>
                </div>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-10 px-4 py-6 sm:px-6 lg:px-8">
            <section>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-6">
                        <p class="text-sm font-medium text-indigo-600">Total Job Posting</p>
                        <p class="mt-2 text-3xl font-semibold text-indigo-900">{{ stats.total_jobs }}</p>
                        <p class="mt-1 text-xs text-indigo-500">Termasuk job aktif & arsip</p>
                    </div>
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-6">
                        <p class="text-sm font-medium text-emerald-600">Job Aktif</p>
                        <p class="mt-2 text-3xl font-semibold text-emerald-900">{{ stats.active_jobs }}</p>
                        <p class="mt-1 text-xs text-emerald-500">Job yang terbuka untuk lamaran</p>
                    </div>
                    <div class="rounded-2xl border border-amber-100 bg-amber-50 p-6">
                        <p class="text-sm font-medium text-amber-600">Lamaran Pending</p>
                        <p class="mt-2 text-3xl font-semibold text-amber-900">{{ stats.pending_applications }}</p>
                        <p class="mt-1 text-xs text-amber-500">Perlu review dari tim Anda</p>
                    </div>
                    <div class="rounded-2xl border border-purple-100 bg-purple-50 p-6">
                        <p class="text-sm font-medium text-purple-600">Job Selesai</p>
                        <p class="mt-2 text-3xl font-semibold text-purple-900">{{ stats.completed_jobs }}</p>
                        <p class="mt-1 text-xs text-purple-500">Siap untuk beri ulasan</p>
                    </div>
                </div>
            </section>

            <section class="grid gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-8">
                    <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Job Terbaru</h3>
                                <p class="text-sm text-gray-500">Pantau performa job Anda.</p>
                            </div>
                            <Link
                                href="#"
                                class="text-sm font-semibold text-indigo-600 hover:text-indigo-500"
                            >
                                Kelola job
                            </Link>
                        </div>

                        <div class="mt-6 space-y-4">
                            <div
                                v-if="!jobs.length"
                                class="rounded-2xl border border-dashed border-gray-200 p-8 text-center"
                            >
                                <p class="text-sm font-medium text-gray-700">Belum ada job posting</p>
                                <p class="mt-2 text-sm text-gray-500">Mulai posting job untuk menemukan streamer.</p>
                                <Link
                                    href="#"
                                    class="mt-4 inline-flex items-center rounded-full bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                                >
                                    Post job pertama
                                </Link>
                            </div>
                            <article
                                v-for="job in jobs"
                                :key="job.id"
                                class="flex flex-col gap-4 rounded-2xl border border-gray-100 bg-gray-50/60 p-5 transition hover:border-indigo-200 hover:bg-white"
                            >
                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <div>
                                        <h4 class="text-base font-semibold text-gray-900">
                                            <Link :href="route('jobs.show', job.slug)" class="hover:text-indigo-600">
                                                {{ job.title }}
                                            </Link>
                                        </h4>
                                        <p class="text-xs uppercase tracking-wide text-gray-500">
                                            Status: {{ job.status }} • Diposting {{
                                                job.published_at || 'TBA'
                                            }}
                                        </p>
                                    </div>
                                    <div class="flex flex-col items-end text-xs text-gray-500">
                                        <span class="font-semibold text-indigo-600">
                                            {{ job.applications_count }} lamaran
                                        </span>
                                        <span>
                                            {{ job.accepted_applications_count }} disetujui
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between text-sm text-gray-600">
                                    <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-700">
                                        ID Job: {{ job.id }}
                                    </span>
                                    <Link
                                        :href="route('jobs.show', job.slug)"
                                        class="text-sm font-semibold text-indigo-600 hover:text-indigo-500"
                                    >
                                        Lihat detail
                                    </Link>
                                </div>
                            </article>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Lamaran Masuk</h3>
                                <p class="text-sm text-gray-500">Tinjau kandidat terbaru.</p>
                            </div>
                            <Link
                                href="#"
                                class="text-sm font-semibold text-indigo-600 hover:text-indigo-500"
                            >
                                Lihat semua
                            </Link>
                        </div>
                        <div class="mt-6 space-y-4">
                            <div
                                v-if="!applications.length"
                                class="rounded-2xl border border-dashed border-gray-200 p-6 text-center text-sm text-gray-500"
                            >
                                Belum ada lamaran baru.
                            </div>
                            <article
                                v-for="application in applications"
                                :key="application.id"
                                class="rounded-2xl border border-gray-100 bg-gray-50/80 p-4 transition hover:border-indigo-200 hover:bg-white"
                            >
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ application.streamer?.name }}
                                        </p>
                                        <p class="text-xs uppercase tracking-wide text-gray-500">
                                            Status: {{ application.status }}
                                        </p>
                                    </div>
                                    <span class="text-xs font-medium text-indigo-600">
                                        Rp {{ Number(application.proposed_rate || 0).toLocaleString('id-ID') }}
                                    </span>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Job: {{ application.job?.title }}</p>
                                <p class="text-xs text-gray-400">Masuk: {{ application.created_at }}</p>
                            </article>
                        </div>
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">Profil Brand</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Lengkapi data perusahaan Anda agar streamer lebih percaya.
                        </p>
                        <dl class="mt-4 space-y-3 text-sm text-gray-600">
                            <div class="flex items-center justify-between">
                                <dt class="font-medium text-gray-900">Nama Perusahaan</dt>
                                <dd>{{ profile?.company_name || 'Belum diatur' }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="font-medium text-gray-900">Brand</dt>
                                <dd>{{ profile?.brand_name || 'Belum diatur' }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="font-medium text-gray-900">Status Verifikasi</dt>
                                <dd>
                                    <span
                                        class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold"
                                        :class="profile?.is_verified ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'"
                                    >
                                        {{ profile?.is_verified ? 'Terverifikasi' : 'Menunggu verifikasi' }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                        <Link
                            :href="route('profile.edit')"
                            class="mt-6 inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-500"
                        >
                            Update profil brand
                        </Link>
                    </div>

                    <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">Tips Cepat</h3>
                        <ul class="mt-4 space-y-3 text-sm text-gray-600">
                            <li class="flex gap-3">
                                <span class="mt-1 h-2 w-2 rounded-full bg-indigo-500"></span>
                                Tambahkan detail produk dan target audience di setiap job.
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-1 h-2 w-2 rounded-full bg-indigo-500"></span>
                                Berikan respons cepat pada lamaran terbaik.
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-1 h-2 w-2 rounded-full bg-indigo-500"></span>
                                Gunakan chat untuk diskusi jadwal briefing lebih lanjut.
                            </li>
                        </ul>
                    </div>
                </aside>
            </section>
        </div>
    </AuthenticatedLayout>
</template>

