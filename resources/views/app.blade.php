<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <script>
            (function () {
                try {
                    const theme = localStorage.getItem('livestream-theme');
                    if (theme === 'dark') {
                        document.documentElement.classList.add('dark');
                    }
                } catch (e) {
                    // no-op
                }
            })();
        </script>
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased bg-gray-50 text-slate-900 transition-colors duration-200 dark:bg-slate-950 dark:text-slate-100">
        @inertia
    </body>
</html>
