<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    profile: {
        type: Object,
        required: true,
    },
    reviews: {
        type: Array,
        default: () => [],
    },
    recommendedJobs: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const isAuthenticated = computed(() => Boolean(page.props.auth?.user));
</script>

<template>
    <component :is="isAuthenticated ? AuthenticatedLayout : PublicLayout">
        <Head :title="`Streamer • ${profile.name}`" />

        <div class="bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950">
            <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
                <div class="grid gap-10 lg:grid-cols-[2fr,1fr]">
                    <section class="space-y-8">
                        <header class="overflow-hidden rounded-3xl border border-white/10 bg-white/5 p-8 text-white shadow-lg shadow-indigo-900/20">
                            <div class="flex flex-wrap items-start justify-between gap-6">
                                <div>
                                    <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-white/70">
                                        {{ profile.category }}
                                    </span>
                                    <h1 class="mt-4 text-3xl font-bold">
                                        {{ profile.name }}
                                    </h1>
                                    <p class="mt-2 text-sm text-white/70">
                                        {{ profile.headline || 'Streamer profesional siap meningkatkan penjualan live Anda.' }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-white/70">Rating</p>
                                    <p class="text-3xl font-semibold text-amber-300">
                                        {{ Number(profile.rating || 0).toFixed(1) }}
                                    </p>
                                    <p class="text-xs text-white/60">{{ profile.review_count }} ulasan</p>
                                </div>
                            </div>
                            <div class="mt-6 flex flex-wrap gap-3 text-xs text-white/60">
                                <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1">
                                    Lokasi: {{ profile.location || 'Global / Remote' }}
                                </span>
                                <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1">
                                    Pengalaman: {{ profile.experience_years || 0 }} tahun
                                </span>
                                <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1">
                                    Jam tayang: {{ profile.hours_streamed || 0 }} jam
                                </span>
                                <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1">
                                    Tarif: Rp {{ Number(profile.hourly_rate || 0).toLocaleString('id-ID') }}/jam
                                </span>
                            </div>
                        </header>

                        <article class="rounded-3xl border border-white/10 bg-white/5 p-8 text-white shadow-lg shadow-indigo-900/20">
                            <h2 class="text-xl font-semibold">Tentang Streamer</h2>
                            <p class="mt-4 text-sm leading-relaxed text-white/70">
                                {{ profile.bio || 'Streamer berpengalaman dalam berbagai kampanye live commerce dengan fokus pada storytelling produk, interaksi dengan audiens, dan konversi penjualan.' }}
                            </p>
                            <div class="mt-6 flex flex-wrap gap-3 text-xs text-white/60">
                                <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1">
                                    Bahasa utama: {{ profile.primary_language || 'Indonesia' }}
                                </span>
                                <span
                                    v-if="profile.secondary_languages"
                                    class="inline-flex items-center rounded-full bg-white/10 px-3 py-1"
                                >
                                    Bahasa tambahan: {{ profile.secondary_languages }}
                                </span>
                                <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1">
                                    Ketersediaan: {{ profile.is_available ? 'Tersedia' : 'Tidak tersedia' }}
                                </span>
                            </div>
                        </article>

                        <section class="rounded-3xl border border-white/10 bg-white/5 p-8 text-white shadow-lg shadow-indigo-900/20">
                            <h2 class="text-xl font-semibold">Portofolio</h2>
                            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                <div
                                    v-if="!profile.portfolio_media?.length"
                                    class="rounded-2xl border border-dashed border-white/20 p-6 text-sm text-white/60"
                                >
                                    Streamer belum menambahkan portofolio.
                                </div>
                                <article
                                    v-for="(item, index) in profile.portfolio_media"
                                    :key="index"
                                    class="group relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-4 transition hover:border-indigo-300 hover:bg-indigo-500/20"
                                >
                                    <h3 class="text-sm font-semibold text-white">{{ item.title }}</h3>
                                    <p class="mt-2 text-xs text-white/60">
                                        {{ item.type === 'video' ? 'Video Live' : 'Visual Campaign' }}
                                    </p>
                                    <Link
                                        v-if="item.url"
                                        :href="item.url"
                                        class="mt-3 inline-flex items-center text-xs font-semibold text-indigo-200 hover:text-white"
                                    >
                                        Lihat contoh
                                    </Link>
                                </article>
                            </div>
                        </section>

                        <section class="rounded-3xl border border-white/10 bg-white/5 p-8 text-white shadow-lg shadow-indigo-900/20">
                            <h2 class="text-xl font-semibold">Ulasan Employer</h2>
                            <div class="mt-6 space-y-4">
                                <div
                                    v-if="!reviews.length"
                                    class="rounded-2xl border border-dashed border-white/20 p-6 text-sm text-white/60"
                                >
                                    Belum ada ulasan dari employer.
                                </div>
                                <article
                                    v-for="review in reviews"
                                    :key="review.id"
                                    class="rounded-2xl border border-white/10 bg-white/5 p-5 transition hover:border-indigo-300 hover:bg-indigo-500/20"
                                >
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-white">
                                                {{ review.employer?.name }}
                                            </p>
                                            <p class="text-xs text-white/60">
                                                {{ review.job?.title }} • {{ review.created_at }}
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center rounded-full bg-amber-300/20 px-3 py-1 text-xs font-semibold text-amber-200">
                                            ⭐ {{ review.rating }}
                                        </span>
                                    </div>
                                    <p class="mt-3 text-sm text-white/70">
                                        {{ review.comment }}
                                    </p>
                                </article>
                            </div>
                        </section>
                    </section>

                    <aside class="space-y-6">
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 text-white shadow-lg shadow-indigo-900/20">
                            <h2 class="text-lg font-semibold">Ajukan Kerjasama</h2>
                            <p class="mt-2 text-sm text-white/70">
                                Brand tertarik bisa mengirim undangan langsung melalui dashboard employer.
                            </p>
                            <Link
                                :href="route('login')"
                                class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-white px-4 py-3 text-sm font-semibold text-indigo-600 transition hover:-translate-y-0.5 hover:bg-indigo-50"
                            >
                                Masuk sebagai Employer
                            </Link>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 text-white shadow-lg shadow-indigo-900/20">
                            <h2 class="text-lg font-semibold">Job Rekomendasi</h2>
                            <div class="mt-4 space-y-4">
                                <div
                                    v-if="!recommendedJobs.length"
                                    class="rounded-2xl border border-dashed border-white/20 p-4 text-sm text-white/60"
                                >
                                    Job serupa akan muncul di sini.
                                </div>
                                <article
                                    v-for="job in recommendedJobs"
                                    :key="job.id"
                                    class="rounded-2xl border border-white/10 bg-white/5 p-4 transition hover:border-indigo-300 hover:bg-indigo-500/20"
                                >
                                    <h3 class="text-sm font-semibold text-white">
                                        <Link :href="route('jobs.show', job.slug)" class="hover:text-indigo-200">
                                            {{ job.title }}
                                        </Link>
                                    </h3>
                                    <p class="mt-2 text-xs text-white/60">
                                        Durasi {{ job.duration_hours }} jam • Budget {{ job.currency }} {{ Number(job.budget || 0).toLocaleString('id-ID') }}
                                    </p>
                                </article>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </component>
</template>

