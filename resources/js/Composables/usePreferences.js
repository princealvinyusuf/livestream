import { computed, reactive, watch } from 'vue';

const THEME_KEY = 'livestream-theme';
const LOCALE_KEY = 'livestream-locale';

const state = reactive({
    theme:
        typeof window !== 'undefined'
            ? localStorage.getItem(THEME_KEY) ?? 'light'
            : 'light',
    locale:
        typeof window !== 'undefined'
            ? localStorage.getItem(LOCALE_KEY) ?? 'id'
            : 'id',
});

const applyTheme = (theme) => {
    if (typeof document === 'undefined') {
        return;
    }

    const root = document.documentElement;

    if (theme === 'dark') {
        root.classList.add('dark');
    } else {
        root.classList.remove('dark');
    }
};

if (typeof window !== 'undefined') {
    applyTheme(state.theme);
}

watch(
    () => state.theme,
    (theme) => {
        if (typeof window !== 'undefined') {
            localStorage.setItem(THEME_KEY, theme);
        }

        applyTheme(theme);
    },
    { immediate: false },
);

watch(
    () => state.locale,
    (locale) => {
        if (typeof window !== 'undefined') {
            localStorage.setItem(LOCALE_KEY, locale);
        }
    },
    { immediate: false },
);

export function usePreferences() {
    const theme = computed(() => state.theme);
    const locale = computed(() => state.locale);

    const toggleTheme = () => {
        state.theme = state.theme === 'dark' ? 'light' : 'dark';
    };

    const setTheme = (value) => {
        state.theme = value;
    };

    const toggleLocale = () => {
        state.locale = state.locale === 'id' ? 'en' : 'id';
    };

    const setLocale = (value) => {
        state.locale = value;
    };

    return {
        theme,
        locale,
        isDark: computed(() => state.theme === 'dark'),
        isLight: computed(() => state.theme === 'light'),
        isIndonesian: computed(() => state.locale === 'id'),
        isEnglish: computed(() => state.locale === 'en'),
        toggleTheme,
        setTheme,
        toggleLocale,
        setLocale,
    };
}


