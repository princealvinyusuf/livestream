<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps({
    jobs: {
        type: Object,
        default: () => ({ data: [] }),
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
    meta: {
        type: Object,
        default: () => ({
            categories: [],
            platforms: [],
        }),
    },
});

const filterForm = reactive({
    search: props.filters.search || '',
    category: props.filters.category || '',
    platform: props.filters.platform || '',
    location: props.filters.location || '',
    min_budget: props.filters.min_budget || '',
    max_budget: props.filters.max_budget || '',
    status: props.filters.status || '',
});

const submitFilter = () => {
    router.get(route('jobs.index'), filterForm, {
        preserveScroll: true,
        preserveState: true,
    });
};

const resetFilter = () => {
    Object.keys(filterForm).forEach((key) => {
        filterForm[key] = '';
    });
    submitFilter();
};
</script>

<template>
    <PublicLayout>
        <Head title="Daftar Job Livestream" />

        <div class="space-y-16 px-4 py-16 sm:px-6 lg:px-8">
            <header class="mx-auto max-w-5xl text-center">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    Temukan Job Live Streaming Terbaru
                </h1>
                <p class="mt-4 text-lg text-gray-500">
                    Filter job sesuai kategori, budget, dan jadwal live untuk menemukan job yang paling cocok.
                </p>
            </header>

            <section class="mx-auto max-w-6xl rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">
                <form
                    @submit.prevent="submitFilter"
                    class="grid gap-6 lg:grid-cols-6"
                >
                    <div class="lg:col-span-2">
                        <label class="text-sm font-medium text-gray-700">Kata kunci</label>
                        <input
                            v-model="filterForm.search"
                            type="search"
                            placeholder="Cari judul job"
                            class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                        />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Kategori</label>
                        <select
                            v-model="filterForm.category"
                            class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                        >
                            <option value="">Semua</option>
                            <option v-for="category in meta.categories" :key="category" :value="category">
                                {{ category }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Platform</label>
                        <select
                            v-model="filterForm.platform"
                            class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                        >
                            <option value="">Semua</option>
                            <option v-for="platform in meta.platforms" :key="platform" :value="platform">
                                {{ platform }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Lokasi</label>
                        <input
                            v-model="filterForm.location"
                            type="text"
                            placeholder="Contoh: Jakarta / Remote"
                            class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                        />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Budget Minimum</label>
                        <input
                            v-model="filterForm.min_budget"
                            type="number"
                            min="0"
                            step="10000"
                            placeholder="Rp"
                            class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                        />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Budget Maksimum</label>
                        <input
                            v-model="filterForm.max_budget"
                            type="number"
                            min="0"
                            step="10000"
                            placeholder="Rp"
                            class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                        />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Status Job</label>
                        <select
                            v-model="filterForm.status"
                            class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                        >
                            <option value="">Semua</option>
                            <option value="open">Open</option>
                            <option value="ongoing">On Going</option>
                            <option value="completed">Selesai</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-3 lg:col-span-2">
                        <button
                            type="submit"
                            class="inline-flex flex-1 items-center justify-center rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500"
                        >
                            Terapkan Filter
                        </button>
                        <button
                            type="button"
                            @click="resetFilter"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-100"
                        >
                            Reset
                        </button>
                    </div>
                </form>
            </section>

            <section class="mx-auto max-w-6xl">
                <div class="grid gap-6 lg:grid-cols-2">
                    <article
                        v-for="job in jobs.data"
                        :key="job.id"
                        class="flex flex-col gap-4 rounded-3xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-lg"
                    >
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">
                                    <Link :href="route('jobs.show', job.slug)" class="hover:text-indigo-600">
                                        {{ job.title }}
                                    </Link>
                                </h2>
                                <p class="text-sm text-gray-500">
                                    {{ job.employer?.name || 'Brand' }} • {{ job.category }}
                                </p>
                            </div>
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold"
                                :class="{
                                    'bg-emerald-100 text-emerald-700': job.status === 'open',
                                    'bg-amber-100 text-amber-700': job.status === 'ongoing',
                                    'bg-gray-200 text-gray-700': job.status === 'completed',
                                }"
                            >
                                {{ job.status }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600">
                            Budget:
                            <span class="font-semibold text-indigo-600">
                                {{ job.currency }} {{ Number(job.budget || 0).toLocaleString('id-ID') }}
                            </span>
                            • Durasi {{ job.duration_hours || '-' }} jam • Mulai {{ job.start_date || 'Fleksibel' }}
                        </p>
                        <div class="flex flex-wrap items-center justify-between gap-3 text-xs text-gray-500">
                            <span>Lokasi: {{ job.location || 'Remote' }}</span>
                            <span>Platform: {{ job.livestream_platform || 'Flexible' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <Link
                                :href="route('jobs.show', job.slug)"
                                class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-500"
                            >
                                Detail job
                            </Link>
                            <div class="text-xs text-gray-400">
                                ID Job: {{ job.id }}
                            </div>
                        </div>
                    </article>
                </div>

                <div
                    v-if="jobs?.links?.length > 1"
                    class="mt-10 flex flex-wrap items-center justify-center gap-2 text-sm"
                >
                    <template v-for="link in jobs.links" :key="link.url || link.label">
                        <button
                            v-if="link.url"
                            v-html="link.label"
                            @click.prevent="router.visit(link.url)"
                            class="min-w-[40px] rounded-full px-3 py-2 transition"
                            :class="[
                                link.active
                                    ? 'bg-indigo-600 text-white shadow-lg'
                                    : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200',
                            ]"
                        />
                        <span
                            v-else
                            v-html="link.label"
                            class="rounded-full px-3 py-2 text-gray-400"
                        />
                    </template>
                </div>

                <div v-if="!jobs.data.length" class="mt-12 rounded-3xl border border-dashed border-gray-200 p-10 text-center">
                    <h3 class="text-lg font-semibold text-gray-900">Tidak ada job yang cocok</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Coba ubah filter atau kembali lagi nanti untuk melihat job terbaru.
                    </p>
                </div>
            </section>
        </div>
    </PublicLayout>
</template>

