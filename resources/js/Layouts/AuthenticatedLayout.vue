<script setup>
import { usePreferences } from '@/Composables/usePreferences';
import { computed, ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const page = usePage();
const user = computed(() => page.props.auth?.user ?? null);
const roles = computed(() => user.value?.roles ?? []);
const isStreamer = computed(
    () => user.value?.role === 'streamer' || roles.value.includes('streamer'),
);
const isEmployer = computed(
    () => user.value?.role === 'employer' || roles.value.includes('employer'),
);

const {
    isDark,
    toggleTheme,
    locale,
    toggleLocale,
    isIndonesian,
} = usePreferences();

const themeLabel = computed(() => (isDark.value ? '🌙' : '☀️'));
const languageLabel = computed(() => (locale.value === 'id' ? 'ID' : 'EN'));
</script>

<template>
    <div class="min-h-screen bg-gray-50 text-slate-900 transition-colors duration-200 dark:bg-slate-950 dark:text-slate-100">
        <nav class="border-b border-slate-200/80 bg-white shadow-sm transition-colors duration-200 dark:border-slate-800 dark:bg-slate-900 dark:shadow-none">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        <div class="flex shrink-0 items-center">
                            <Link :href="route('dashboard')">
                                <ApplicationLogo class="block h-9 w-auto fill-current text-indigo-600 dark:text-white" />
                            </Link>
                        </div>

                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                {{ isIndonesian ? 'Dashboard' : 'Dashboard' }}
                            </NavLink>
                            <NavLink
                                :href="route('jobs.index')"
                                :active="route().current('jobs.index') || route().current('jobs.show')"
                            >
                                {{ isIndonesian ? 'Daftar Job' : 'Jobs' }}
                            </NavLink>
                            <NavLink
                                v-if="isEmployer"
                                :href="route('jobs.create')"
                                :active="route().current('jobs.create')"
                            >
                                {{ isIndonesian ? 'Post Job' : 'Post a Job' }}
                            </NavLink>
                        </div>
                    </div>

                    <div class="hidden items-center gap-3 sm:ms-6 sm:flex">
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
                            :title="
                                isDark
                                    ? isIndonesian
                                        ? 'Ubah ke mode terang'
                                        : 'Switch to light mode'
                                    : isIndonesian
                                    ? 'Ubah ke mode gelap'
                                    : 'Switch to dark mode'
                            "
                        >
                            {{ themeLabel }}
                        </button>

                        <div class="relative ms-3">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-slate-600 transition duration-150 ease-in-out hover:text-indigo-600 focus:outline-none dark:bg-slate-800 dark:text-slate-200 dark:hover:text-indigo-200"
                                        >
                                            {{ user?.name }}

                                            <svg
                                                class="-me-0.5 ms-2 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')">
                                        {{ isIndonesian ? 'Profil' : 'Profile' }}
                                    </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        {{ isIndonesian ? 'Keluar' : 'Log Out' }}
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <div class="-me-2 flex items-center sm:hidden">
                        <button
                            @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:hover:bg-slate-800 dark:hover:text-slate-200"
                        >
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path
                                    :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div
                :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                class="sm:hidden"
            >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            {{ isIndonesian ? 'Dashboard' : 'Dashboard' }}
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('jobs.index')"
                            :active="
                                route().current('jobs.index') ||
                                route().current('jobs.show')
                            "
                        >
                            {{ isIndonesian ? 'Daftar Job' : 'Jobs' }}
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            v-if="isEmployer"
                            :href="route('jobs.create')"
                            :active="route().current('jobs.create')"
                        >
                            {{ isIndonesian ? 'Post Job' : 'Post a Job' }}
                        </ResponsiveNavLink>
                        <div class="flex items-center gap-3 px-4 pt-3">
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
                            >
                                {{ themeLabel }}
                            </button>
                        </div>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-gray-200 pb-1 pt-4"
                    >
                        <div class="px-4">
                            <div
                                class="text-base font-medium text-gray-800"
                            >
                                {{ user?.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ user?.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                {{ isIndonesian ? 'Profil' : 'Profile' }}
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                {{ isIndonesian ? 'Keluar' : 'Log Out' }}
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header
                class="bg-white shadow transition-colors duration-200 dark:bg-slate-900 dark:text-slate-100 dark:shadow-none"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="bg-gray-50 transition-colors duration-200 dark:bg-slate-950">
                <slot />
            </main>
        </div>
</template>
