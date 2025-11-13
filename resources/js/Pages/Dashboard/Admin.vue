<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    user: {
        type: Object,
        default: () => ({}),
    },
    metrics: {
        type: Object,
        default: () => ({
            total_users: 0,
            streamers: 0,
            employers: 0,
            jobs: 0,
            applications: 0,
        }),
    },
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Dashboard Admin" />

        <template #header>
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-900">
                    Admin Overview
                </h2>
                <p class="text-sm text-gray-500">
                    Pantau statistik utama ekosistem marketplace livestream.
                </p>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-10 px-4 py-6 sm:px-6 lg:px-8">
            <section>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                        <p class="text-sm font-medium text-gray-500">Total Pengguna</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">
                            {{ metrics.total_users }}
                        </p>
                    </div>
                    <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-6 shadow-sm">
                        <p class="text-sm font-medium text-indigo-600">Streamer</p>
                        <p class="mt-2 text-3xl font-semibold text-indigo-900">
                            {{ metrics.streamers }}
                        </p>
                    </div>
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6 shadow-sm">
                        <p class="text-sm font-medium text-emerald-600">Employer</p>
                        <p class="mt-2 text-3xl font-semibold text-emerald-900">
                            {{ metrics.employers }}
                        </p>
                    </div>
                    <div class="rounded-2xl border border-purple-200 bg-purple-50 p-6 shadow-sm">
                        <p class="text-sm font-medium text-purple-600">Job Posting</p>
                        <p class="mt-2 text-3xl font-semibold text-purple-900">
                            {{ metrics.jobs }}
                        </p>
                    </div>
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 shadow-sm">
                        <p class="text-sm font-medium text-amber-600">Total Lamaran</p>
                        <p class="mt-2 text-3xl font-semibold text-amber-900">
                            {{ metrics.applications }}
                        </p>
                    </div>
                </div>
            </section>

            <section class="grid gap-8 lg:grid-cols-2">
                <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                    <p class="mt-2 text-sm text-gray-500">Langkah cepat menangani kebutuhan harian admin.</p>
                    <ul class="mt-6 space-y-4 text-sm text-gray-600">
                        <li class="flex items-start gap-3">
                            <span class="mt-1 h-2 w-2 rounded-full bg-indigo-500"></span>
                            Tinjau akun streamer baru dan verifikasi portofolio mereka.
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 h-2 w-2 rounded-full bg-indigo-500"></span>
                            Moderasi ulasan employer dan tangani laporan penyalahgunaan.
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 h-2 w-2 rounded-full bg-indigo-500"></span>
                            Monitor volume transaksi dan pastikan job escrow berjalan lancar.
                        </li>
                    </ul>
                </div>

                <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900">Catatan Tim Operasional</h3>
                    <p class="mt-2 text-sm text-gray-500">Koordinasikan tindak lanjut dengan tim customer success.</p>
                    <div class="mt-6 space-y-4 text-sm text-gray-600">
                        <div class="rounded-2xl border border-dashed border-gray-200 px-5 py-4">
                            <p class="font-medium text-gray-900">Prioritas Minggu Ini</p>
                            <p class="mt-1 text-sm text-gray-500">
                                Fitur chat realtime sedang tahap QA. Pastikan dokumentasi siap sebelum rilis.
                            </p>
                        </div>
                        <div class="rounded-2xl border border-dashed border-gray-200 px-5 py-4">
                            <p class="font-medium text-gray-900">Permintaan Pengguna</p>
                            <p class="mt-1 text-sm text-gray-500">
                                Banyak permintaan untuk menambahkan jadwal ketersediaan streamer per jam.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>

