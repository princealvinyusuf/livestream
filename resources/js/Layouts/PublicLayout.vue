<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { usePreferences } from '@/Composables/usePreferences';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const user = computed(() => page.props.auth?.user ?? null);
const { isDark, toggleTheme, locale, toggleLocale, isIndonesian } =
    usePreferences();

const themeLabel = computed(() =>
    isDark.value ? '🌙' : '☀️',
);

const languageLabel = computed(() =>
    locale.value === 'id' ? 'ID' : 'EN',
);
</script>

<template>
    <div class="min-h-screen bg-gray-50 text-slate-900 transition-colors duration-200 dark:bg-slate-950 dark:text-slate-100">
        <header class="fixed inset-x-0 top-0 z-50 border-b border-slate-200/70 bg-white/90 backdrop-blur transition-colors duration-200 dark:border-white/10 dark:bg-slate-950/80">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
                <Link href="/" class="flex items-center gap-3">
                    <ApplicationLogo class="h-8 w-8 fill-current text-indigo-600 dark:text-white" />
                    <span class="text-lg font-semibold text-slate-900 dark:text-white">LiveHire</span>
                </Link>
                <nav class="hidden items-center gap-4 text-sm font-medium text-slate-600 transition-colors duration-200 sm:flex dark:text-slate-200">
                    <Link :href="route('jobs.index')" class="transition hover:text-indigo-600 dark:hover:text-white">
                        {{ isIndonesian ? 'Cari Job' : 'Browse Jobs' }}
                    </Link>
                    <Link :href="route('register')" class="transition hover:text-indigo-600 dark:hover:text-white">
                        {{ isIndonesian ? 'Daftar Streamer' : 'Become a Streamer' }}
                    </Link>
                    <Link :href="route('register')" class="transition hover:text-indigo-600 dark:hover:text-white">
                        {{ isIndonesian ? 'Daftar Employer' : 'For Employers' }}
                    </Link>
                    <Link
                        v-if="!user"
                        :href="route('login')"
                        class="transition hover:text-indigo-600 dark:hover:text-white"
                    >
                        {{ isIndonesian ? 'Masuk' : 'Login' }}
                    </Link>
                    <Link
                        v-else
                        :href="route('dashboard')"
                        class="inline-flex items-center rounded-full bg-indigo-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-900/30 transition hover:-translate-y-0.5 hover:bg-indigo-400"
                    >
                        {{ isIndonesian ? 'Dashboard' : 'Dashboard' }}
                    </Link>
                    <button
                        type="button"
                        class="inline-flex items-center rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 transition hover:border-indigo-400 hover:text-indigo-600 dark:border-white/20 dark:text-slate-200 dark:hover:border-indigo-300 dark:hover:text-indigo-200"
                        @click="toggleLocale"
                    >
                        {{ languageLabel }}
                    </button>
                    <button
                        type="button"
                        class="inline-flex items-center rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 transition hover:border-indigo-400 hover:text-indigo-600 dark:border-white/20 dark:text-slate-200 dark:hover:border-indigo-300 dark:hover:text-indigo-200"
                        @click="toggleTheme"
                        :title="isDark ? (isIndonesian ? 'Ubah ke mode terang' : 'Switch to light mode') : (isIndonesian ? 'Ubah ke mode gelap' : 'Switch to dark mode')"
                    >
                        {{ themeLabel }}
                    </button>
                </nav>
                <div class="flex sm:hidden">
                    <Link
                        v-if="!user"
                        :href="route('login')"
                        class="inline-flex items-center rounded-full bg-indigo-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-900/30 transition hover:bg-indigo-400"
                    >
                        {{ isIndonesian ? 'Masuk' : 'Login' }}
                    </Link>
                    <Link
                        v-else
                        :href="route('dashboard')"
                        class="inline-flex items-center rounded-full bg-indigo-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-900/30 transition hover:bg-indigo-400"
                    >
                        Dashboard
                    </Link>
                </div>
            </div>
        </header>

        <main class="pt-24">
            <slot />
        </main>

        <footer class="border-t border-slate-200/70 bg-white/90 py-10 transition-colors duration-200 dark:border-white/10 dark:bg-slate-900/60">
            <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-6 px-6 text-sm text-slate-500 sm:flex-row lg:px-8 dark:text-slate-300">
                <p>© {{ new Date().getFullYear() }} LiveHire Marketplace.</p>
                <div class="flex items-center gap-6">
                    <Link :href="route('jobs.index')" class="transition hover:text-indigo-600 dark:hover:text-white">
                        {{ isIndonesian ? 'Job' : 'Jobs' }}
                    </Link>
                    <Link :href="route('register')" class="transition hover:text-indigo-600 dark:hover:text-white">
                        {{ isIndonesian ? 'Daftar' : 'Sign Up' }}
                    </Link>
                    <Link :href="route('login')" class="transition hover:text-indigo-600 dark:hover:text-white">
                        {{ isIndonesian ? 'Masuk' : 'Login' }}
                    </Link>
                </div>
            </div>
        </footer>
    </div>
</template>

