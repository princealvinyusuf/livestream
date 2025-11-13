<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user ?? null);
</script>

<template>
    <div class="min-h-screen bg-slate-950 text-white">
        <header class="fixed inset-x-0 top-0 z-50 border-b border-white/10 bg-slate-950/80 backdrop-blur">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
                <Link href="/" class="flex items-center gap-3">
                    <ApplicationLogo class="h-8 w-8 fill-current text-white" />
                    <span class="text-lg font-semibold text-white">LiveHire</span>
                </Link>
                <nav class="hidden items-center gap-6 text-sm font-medium text-white/80 sm:flex">
                    <Link :href="route('jobs.index')" class="transition hover:text-white">Cari Job</Link>
                    <Link :href="route('register')" class="transition hover:text-white">Daftar Streamer</Link>
                    <Link :href="route('register')" class="transition hover:text-white">Daftar Employer</Link>
                    <Link :href="route('login')" v-if="!user" class="transition hover:text-white">Masuk</Link>
                    <Link
                        v-else
                        :href="route('dashboard')"
                        class="inline-flex items-center rounded-full bg-indigo-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-900/30 transition hover:-translate-y-0.5 hover:bg-indigo-400"
                    >
                        Dashboard
                    </Link>
                </nav>
                <div class="flex sm:hidden">
                    <Link
                        :href="user ? route('dashboard') : route('login')"
                        class="inline-flex items-center rounded-full bg-indigo-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-900/30 transition hover:bg-indigo-400"
                    >
                        {{ user ? 'Dashboard' : 'Masuk' }}
                    </Link>
                </div>
            </div>
        </header>

        <main class="pt-24">
            <slot />
        </main>

        <footer class="border-t border-white/10 bg-black/40 py-10">
            <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-6 px-6 text-sm text-white/50 sm:flex-row lg:px-8">
                <p>© {{ new Date().getFullYear() }} LiveHire Marketplace.</p>
                <div class="flex items-center gap-6">
                    <Link :href="route('jobs.index')" class="transition hover:text-white">Job</Link>
                    <Link :href="route('register')" class="transition hover:text-white">Daftar</Link>
                    <Link :href="route('login')" class="transition hover:text-white">Masuk</Link>
                </div>
            </div>
        </footer>
    </div>
</template>

