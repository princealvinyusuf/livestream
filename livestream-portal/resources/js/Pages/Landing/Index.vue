<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    categories: {
        type: Array,
        default: () => [],
    },
    featuredStreamers: {
        type: Array,
        default: () => [],
    },
    latestJobs: {
        type: Array,
        default: () => [],
    },
    metrics: {
        type: Object,
        default: () => ({
            streamers: 0,
            employers: 0,
            jobs: 0,
        }),
    },
});

const search = ref('');

const performSearch = () => {
    router.visit(route('jobs.index'), {
        data: {
            search: search.value,
        },
    });
};
</script>

<template>
    <PublicLayout>
        <header class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-amber-500">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_var(--tw-gradient-stops))] from-white/10 via-transparent to-transparent"></div>
            <div class="mx-auto flex max-w-7xl flex-col gap-16 px-6 pb-24 pt-24 sm:pt-32 lg:flex-row lg:items-center lg:gap-20 lg:px-8">
                <div class="lg:w-1/2">
                    <div class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-white/80">
                        <span class="inline-flex h-8 items-center rounded-full bg-white/20 px-3">Marketplace Livestream</span>
                        <span>Pertemukan Brand & Streamer</span>
                    </div>
                    <h1 class="mt-6 text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">
                        Cari Live Streamer Profesional untuk Toko Online Anda
                    </h1>
                    <p class="mt-6 text-lg text-white/80">
                        Livestream adalah platform marketplace yang menghubungkan brand dengan host live streaming terbaik. Temukan streamer berpengalaman, kelola job, dan tingkatkan penjualan live Anda.
                    </p>
                    <div class="mt-8 flex flex-col gap-4 sm:flex-row">
                        <div class="relative flex-1">
                            <input
                                v-model="search"
                                type="search"
                                placeholder="Cari streamer atau job"
                                class="block w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/60 shadow-lg backdrop-blur transition focus:border-white/40 focus:outline-none focus:ring-2 focus:ring-white/50"
                            />
                            <button
                                @click="performSearch"
                                class="absolute inset-y-1 right-1 rounded-lg bg-white px-4 text-sm font-semibold text-indigo-600 transition hover:bg-indigo-100"
                            >
                                Cari
                            </button>
                        </div>
                        <Link
                            :href="route('register')"
                            class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 text-sm font-semibold text-indigo-600 shadow-lg shadow-indigo-900/30 transition hover:-translate-y-0.5 hover:shadow-xl"
                        >
                            Daftar sebagai Streamer
                        </Link>
                    </div>
                </div>
                <div class="relative lg:w-1/2">
                    <div class="aspect-square w-full max-w-xl rounded-3xl bg-white/10 p-8 backdrop-blur">
                        <div class="grid h-full grid-cols-2 gap-4">
                            <div class="flex flex-col justify-between rounded-2xl bg-white/10 p-6">
                                <p class="text-sm text-white/70">Streamer Aktif</p>
                                <p class="text-4xl font-semibold">{{ metrics.streamers }}</p>
                                <p class="mt-2 text-xs text-white/60">Diverifikasi oleh tim kami</p>
                            </div>
                            <div class="rounded-2xl bg-white/10 p-6">
                                <p class="text-sm text-white/70">Job Posting</p>
                                <p class="text-4xl font-semibold">{{ metrics.jobs }}</p>
                                <p class="mt-2 text-xs text-white/60">Siap di-apply hari ini</p>
                            </div>
                            <div class="rounded-2xl bg-white/10 p-6">
                                <p class="text-sm text-white/70">Employer Aktif</p>
                                <p class="text-4xl font-semibold">{{ metrics.employers }}</p>
                                <p class="mt-2 text-xs text-white/60">Brand dari berbagai industri</p>
                            </div>
                            <div class="rounded-2xl bg-white/10 p-6">
                                <p class="text-sm text-white/70">Rating Rata-rata</p>
                                <p class="text-4xl font-semibold">
                                    {{
                                        featuredStreamers.length
                                            ? (
                                                  featuredStreamers.reduce((acc, item) => acc + (Number(item.rating) || 0), 0) /
                                                  featuredStreamers.length
                                              ).toFixed(1)
                                            : '5.0'
                                    }}
                                </p>
                                <p class="mt-2 text-xs text-white/60">Kinerja streamer terbaik</p>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -bottom-6 -left-6 hidden rounded-2xl bg-white/20 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-900/30 sm:block">
                        Live Commerce • Fashion • Beauty • Electronics
                    </div>
                </div>
            </div>
        </header>

        <main class="-mt-16 space-y-24 bg-slate-950 pb-24 pt-24">
            <section class="mx-auto max-w-6xl px-6 lg:px-8">
                <h2 class="text-2xl font-semibold text-white">Kategori Populer</h2>
                <p class="mt-2 text-sm text-white/60">Temukan streamer ahli di kategori favorit brand</p>
                <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="category in categories"
                        :key="category.name"
                        class="group rounded-2xl border border-white/10 bg-white/5 p-6 transition hover:border-indigo-400/70 hover:bg-indigo-500/10"
                    >
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-white">{{ category.name }}</h3>
                            <span class="rounded-full bg-white/10 px-3 py-1 text-xs text-white/70"> {{ category.total }} job </span>
                        </div>
                        <p class="mt-3 text-sm text-white/60">
                            Streamer dengan pengalaman memajukan penjualan kategori {{ category.name.toLowerCase() }}.
                        </p>
                        <Link
                            :href="route('jobs.index', { category: category.name })"
                            class="mt-4 inline-flex items-center text-sm font-semibold text-indigo-300 transition hover:text-white"
                        >
                            Lihat job terkait
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="ms-2 h-4 w-4"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12l-3.75 3.75m-11.5 0L3 12l3.75-3.75M21 12H3" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </section>

            <section class="mx-auto max-w-6xl px-6 lg:px-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-white">Streamer Unggulan</h2>
                        <p class="mt-2 text-sm text-white/60">Dipilih berdasarkan rating dan performa job terakhir</p>
                    </div>
                    <Link
                        :href="route('jobs.index')"
                        class="inline-flex items-center text-sm font-semibold text-indigo-300 transition hover:text-white"
                    >
                        Jelajahi job terbaru
                    </Link>
                </div>
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <article
                        v-for="streamer in featuredStreamers"
                        :key="streamer.id"
                        class="group relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 p-6 transition hover:border-indigo-400/70 hover:bg-indigo-500/10"
                    >
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 text-lg font-semibold text-white">
                                {{ streamer.name?.slice(0, 2)?.toUpperCase() }}
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white">
                                    <Link :href="route('streamers.show', streamer.id)" class="hover:text-indigo-200">
                                        {{ streamer.name }}
                                    </Link>
                                </h3>
                                <p class="text-sm text-indigo-200/80">{{ streamer.category }}</p>
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-white/70">
                            {{ streamer.headline }}
                        </p>
                        <div class="mt-6 flex items-center justify-between text-xs text-white/60">
                            <span class="inline-flex items-center gap-1 rounded-full bg-white/10 px-3 py-1">
                                ⭐ {{ Number(streamer.rating || 0).toFixed(1) }} ({{ streamer.review_count }} ulasan)
                            </span>
                            <span>{{ streamer.city }}</span>
                        </div>
                        <div class="mt-6 flex flex-wrap gap-2">
                            <span
                                v-for="(item, index) in streamer.portfolio?.slice(0, 2) || []"
                                :key="index"
                                class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-xs text-white/70"
                            >
                                {{ item.title }}
                            </span>
                        </div>
                        <Link
                            :href="route('streamers.show', streamer.id)"
                            class="mt-6 inline-flex items-center text-sm font-semibold text-indigo-300 transition hover:text-white"
                        >
                            Lihat profil
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="ms-2 h-4 w-4"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </Link>
                    </article>
                </div>
            </section>

            <section class="mx-auto max-w-6xl px-6 lg:px-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-white">Job Terbaru</h2>
                        <p class="mt-2 text-sm text-white/60">Posting terbaru dari brand yang siap go-live</p>
                    </div>
                    <Link
                        :href="route('jobs.index')"
                        class="inline-flex items-center text-sm font-semibold text-indigo-300 transition hover:text-white"
                    >
                        Lihat semua job
                    </Link>
                </div>
                <div class="mt-8 grid gap-6 lg:grid-cols-2">
                    <article
                        v-for="job in latestJobs"
                        :key="job.id"
                        class="flex flex-col gap-4 rounded-3xl border border-white/10 bg-white/5 p-6 transition hover:border-indigo-400/70 hover:bg-indigo-500/10"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-semibold text-white">
                                    <Link :href="route('jobs.show', job.slug)" class="hover:text-indigo-200">
                                        {{ job.title }}
                                    </Link>
                                </h3>
                                <p class="mt-1 text-sm text-white/60">{{ job.employer?.name }} • {{ job.category }}</p>
                            </div>
                            <span class="rounded-full bg-white/10 px-3 py-1 text-xs text-white/70">
                                {{ job.duration_hours }} jam
                            </span>
                        </div>
                        <p class="text-sm text-white/70">
                            Jadwal mulai: {{ job.start_date || 'TBD' }} • Budget:
                            <span class="font-semibold text-indigo-200">
                                {{ job.currency }} {{ Number(job.budget)?.toLocaleString('id-ID') }}
                            </span>
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-white/60">Platform yang diharapkan: {{ job.livestream_platform || 'Fleksibel' }}</span>
                            <Link
                                :href="route('jobs.show', job.slug)"
                                class="inline-flex items-center text-sm font-semibold text-indigo-300 transition hover:text-white"
                            >
                                Detail job
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="ms-2 h-4 w-4"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </Link>
                        </div>
                    </article>
                </div>
            </section>
        </main>

    </PublicLayout>
</template>

