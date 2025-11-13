<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    job: {
        type: Object,
        required: true,
    },
    application: {
        type: Object,
        default: null,
    },
    similarJobs: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const authUser = computed(() => page.props.auth?.user ?? null);
const isAuthenticated = computed(() => Boolean(authUser.value));
const roles = computed(() => authUser.value?.roles ?? []);
const isStreamer = computed(
    () => authUser.value?.role === 'streamer' || roles.value.includes('streamer'),
);
const isEmployer = computed(
    () => authUser.value?.role === 'employer' || roles.value.includes('employer'),
);

const form = useForm({
    cover_letter: props.application?.cover_letter || '',
    proposed_rate: props.application?.proposed_rate || '',
    proposed_duration_hours: props.application?.proposed_duration_hours || props.job?.duration_hours || 2,
});

const submitApplication = () => {
    form.post(route('jobs.apply', props.job.slug), {
        preserveScroll: true,
        onSuccess: () => form.clearErrors(),
    });
};
</script>

<template>
    <component :is="isAuthenticated ? AuthenticatedLayout : PublicLayout">
        <Head :title="`Job • ${job.title}`" />

        <div class="px-4 py-12 sm:px-6 lg:px-8">
            <div class="mx-auto grid max-w-6xl gap-10 lg:grid-cols-[2fr,1fr]">
                <article class="space-y-8 rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">
                                {{ job.category }}
                            </span>
                            <h1 class="mt-4 text-3xl font-bold text-gray-900">{{ job.title }}</h1>
                            <p class="mt-2 text-sm text-gray-500">
                                Diposting oleh {{ job.employer?.company || job.employer?.name }} • Status {{ job.status }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Budget</p>
                            <p class="text-2xl font-semibold text-indigo-600">
                                {{ job.currency }} {{ Number(job.budget || 0).toLocaleString('id-ID') }}
                            </p>
                            <p class="text-xs text-gray-400">
                                Durasi {{ job.duration_hours || '-' }} jam • Mulai {{ job.start_date || 'TBD' }}
                            </p>
                        </div>
                    </div>

                    <section class="rounded-2xl bg-gray-50 p-6 text-sm text-gray-600">
                        <dl class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="font-semibold text-gray-900">Platform</dt>
                                <dd>{{ job.livestream_platform || 'Fleksibel' }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-900">Lokasi</dt>
                                <dd>{{ job.location || 'Remote' }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-900">Mulai</dt>
                                <dd>{{ job.start_date || 'Fleksibel' }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-900">Deadline Lamaran</dt>
                                <dd>{{ job.application_deadline || 'Tidak ditentukan' }}</dd>
                            </div>
                        </dl>
                    </section>

                    <section>
                        <h2 class="text-xl font-semibold text-gray-900">Deskripsi Job</h2>
                        <div class="prose prose-indigo mt-4 max-w-none text-gray-700" v-html="job.description?.replace(/\\n/g, '<br/>')"></div>
                    </section>

                    <section v-if="isEmployer && job.applications">
                        <h2 class="text-xl font-semibold text-gray-900">Lamaran Masuk</h2>
                        <div class="mt-4 space-y-4">
                            <div
                                v-for="application in job.applications"
                                :key="application.id"
                                class="rounded-2xl border border-gray-100 bg-gray-50 p-4"
                            >
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ application.streamer?.name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ application.streamer?.category }} • Rating {{ Number(application.streamer?.rating || 0).toFixed(1) }}
                                        </p>
                                    </div>
                                    <span
                                        class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold"
                                        :class="{
                                            'bg-emerald-100 text-emerald-700': application.status === 'accepted',
                                            'bg-amber-100 text-amber-700': application.status === 'pending',
                                            'bg-rose-100 text-rose-700': application.status === 'rejected',
                                            'bg-gray-200 text-gray-700': !['accepted', 'pending', 'rejected'].includes(application.status),
                                        }"
                                    >
                                        {{ application.status }}
                                    </span>
                                </div>
                                <p class="mt-2 text-sm text-gray-600">
                                    Proposed rate: <span class="font-semibold text-indigo-600">Rp {{ Number(application.proposed_rate || 0).toLocaleString('id-ID') }}</span>
                                </p>
                            </div>
                        </div>
                    </section>
                </article>

                <aside class="space-y-6">
                    <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900">Employer</h2>
                        <p class="mt-2 text-sm text-gray-500">
                            {{ job.employer?.company || job.employer?.name }}
                        </p>
                        <div class="mt-4 text-sm text-gray-600">
                            <p>Status verifikasi:</p>
                            <p class="mt-1">
                                <span
                                    class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold"
                                    :class="job.employer?.is_verified ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-700'"
                                >
                                    {{ job.employer?.is_verified ? 'Terverifikasi' : 'Belum verifikasi' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div v-if="isStreamer" class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ application ? 'Status Lamaran Anda' : 'Lamar Job Ini' }}
                        </h2>
                        <p class="mt-2 text-sm text-gray-500">
                            {{ application ? 'Anda sudah mengirim lamaran. Update detail jika dibutuhkan.' : 'Sertakan highlight pengalaman live streaming Anda.' }}
                        </p>

                        <form
                            v-if="job.status === 'open'"
                            class="mt-4 space-y-4"
                            @submit.prevent="submitApplication"
                        >
                            <div>
                                <label class="text-sm font-medium text-gray-700">Cover Letter</label>
                                <textarea
                                    v-model="form.cover_letter"
                                    rows="4"
                                    class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                                    placeholder="Ceritakan pengalaman Anda dan hasil live streaming terbaik."
                                />
                                <InputError :message="form.errors.cover_letter" class="mt-2" />
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Rate yang Diajukan</label>
                                <input
                                    v-model="form.proposed_rate"
                                    type="number"
                                    min="0"
                                    step="10000"
                                    class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                                />
                                <InputError :message="form.errors.proposed_rate" class="mt-2" />
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Durasi Live (jam)</label>
                                <input
                                    v-model="form.proposed_duration_hours"
                                    type="number"
                                    min="1"
                                    class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                                />
                                <InputError :message="form.errors.proposed_duration_hours" class="mt-2" />
                            </div>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 disabled:cursor-not-allowed disabled:opacity-70"
                            >
                                {{ application ? 'Perbarui Lamaran' : 'Kirim Lamaran' }}
                            </button>
                        </form>
                        <div v-else class="mt-4 rounded-2xl bg-gray-50 p-4 text-sm text-gray-500">
                            Lamaran ditutup. Job telah berstatus {{ job.status }}.
                        </div>
                    </div>

                    <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900">Job Serupa</h2>
                        <div class="mt-4 space-y-4">
                            <div
                                v-if="!similarJobs.length"
                                class="rounded-2xl border border-dashed border-gray-200 p-6 text-sm text-gray-500"
                            >
                                Belum ada job serupa.
                            </div>
                            <article
                                v-for="item in similarJobs"
                                :key="item.id"
                                class="rounded-2xl border border-gray-100 bg-gray-50 p-4 transition hover:border-indigo-200 hover:bg-white"
                            >
                                <h3 class="text-sm font-semibold text-gray-900">
                                    <Link :href="route('jobs.show', item.slug)" class="hover:text-indigo-600">
                                        {{ item.title }}
                                    </Link>
                                </h3>
                                <p class="mt-1 text-xs text-gray-500">Durasi {{ item.duration_hours }} jam • Mulai {{ item.start_date || 'TBD' }}</p>
                                <p class="mt-2 text-sm text-gray-600">
                                    Budget:
                                    <span class="font-semibold text-indigo-600">
                                        {{ item.currency }} {{ Number(item.budget || 0).toLocaleString('id-ID') }}
                                    </span>
                                </p>
                            </article>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </component>
</template>

