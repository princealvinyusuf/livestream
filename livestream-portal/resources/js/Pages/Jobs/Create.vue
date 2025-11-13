<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    defaults: {
        type: Object,
        default: () => ({
            category: 'Fashion',
            categories: [],
            platforms: [],
        }),
    },
});

const form = useForm({
    title: '',
    category: props.defaults.category || '',
    livestream_platform: '',
    location: '',
    budget: '',
    currency: 'IDR',
    duration_hours: 2,
    start_date: '',
    application_deadline: '',
    is_remote: true,
    description: '',
    status: 'open',
});

const submit = () => {
    form.post(route('jobs.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Buat Job Baru" />

        <template #header>
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-900">
                    Buat Job Live Streaming
                </h2>
                <p class="text-sm text-gray-500">
                    Lengkapi detail job agar streamer memahami kebutuhan live Anda.
                </p>
            </div>
        </template>

        <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
            <form @submit.prevent="submit" class="space-y-8 rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">
                <section class="space-y-6">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Judul Job</label>
                        <input
                            v-model="form.title"
                            type="text"
                            placeholder="Contoh: Host Livestream Fashion Kampanye Ramadan"
                            class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                        />
                        <InputError :message="form.errors.title" class="mt-2" />
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Kategori Produk</label>
                            <select
                                v-model="form.category"
                                class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                            >
                                <option value="">Pilih kategori</option>
                                <option v-for="category in defaults.categories" :key="category" :value="category">
                                    {{ category }}
                                </option>
                                <option v-if="!defaults.categories.length" value="Fashion">Fashion</option>
                                <option v-if="!defaults.categories.length" value="Beauty">Beauty</option>
                                <option v-if="!defaults.categories.length" value="Electronics">Electronics</option>
                            </select>
                            <InputError :message="form.errors.category" class="mt-2" />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Platform</label>
                            <select
                                v-model="form.livestream_platform"
                                class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                            >
                                <option value="">Fleksibel</option>
                                <option v-for="platform in defaults.platforms" :key="platform" :value="platform">
                                    {{ platform }}
                                </option>
                            </select>
                            <InputError :message="form.errors.livestream_platform" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Lokasi</label>
                            <input
                                v-model="form.location"
                                type="text"
                                placeholder="Contoh: Jakarta / Remote"
                                class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                            />
                            <InputError :message="form.errors.location" class="mt-2" />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Status Job</label>
                            <select
                                v-model="form.status"
                                class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                            >
                                <option value="open">Publikasikan sekarang</option>
                                <option value="draft">Simpan sebagai draft</option>
                            </select>
                            <InputError :message="form.errors.status" class="mt-2" />
                        </div>
                    </div>
                </section>

                <section class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Budget</label>
                        <div class="mt-1 flex gap-3">
                            <select
                                v-model="form.currency"
                                class="w-24 rounded-xl border border-gray-200 px-3 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                            >
                                <option value="IDR">IDR</option>
                                <option value="USD">USD</option>
                            </select>
                            <input
                                v-model="form.budget"
                                type="number"
                                min="0"
                                step="10000"
                                placeholder="Contoh: 1500000"
                                class="flex-1 rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                            />
                        </div>
                        <InputError :message="form.errors.budget" class="mt-2" />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Durasi Live (jam)</label>
                        <input
                            v-model="form.duration_hours"
                            type="number"
                            min="1"
                            class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                        />
                        <InputError :message="form.errors.duration_hours" class="mt-2" />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Tanggal Mulai</label>
                        <input
                            v-model="form.start_date"
                            type="date"
                            class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                        />
                        <InputError :message="form.errors.start_date" class="mt-2" />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Deadline Lamaran</label>
                        <input
                            v-model="form.application_deadline"
                            type="date"
                            class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                        />
                        <InputError :message="form.errors.application_deadline" class="mt-2" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium text-gray-700">Deskripsi Job</label>
                        <textarea
                            v-model="form.description"
                            rows="6"
                            placeholder="Tuliskan detail produk, target audiens, tujuan live, dan KPI yang diharapkan."
                            class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                        />
                        <InputError :message="form.errors.description" class="mt-2" />
                    </div>
                    <div class="flex items-center gap-3 sm:col-span-2">
                        <input
                            id="is_remote"
                            v-model="form.is_remote"
                            type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                        />
                        <label for="is_remote" class="text-sm text-gray-600">Streamer dapat live secara remote</label>
                    </div>
                </section>

                <div class="flex justify-end gap-3">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 disabled:cursor-not-allowed disabled:opacity-70"
                    >
                        {{ form.status === 'draft' ? 'Simpan Draft' : 'Publikasikan Job' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

