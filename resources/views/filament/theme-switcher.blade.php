<div x-data class="flex items-center gap-2 px-1">
    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 hidden sm:block">
        Tema:
    </span>
    <button
        x-on:click="
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                document.cookie = 'filament_theme=light;path=/;max-age=31536000';
            } else {
                html.classList.add('dark');
                document.cookie = 'filament_theme=dark;path=/;max-age=31536000';
            }
        "
        type="button"
        class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition cursor-pointer"
        title="Toggle Dark Mode"
    >
        <span class="block dark:hidden text-sm">🌙</span>
        <span class="hidden dark:block text-sm">☀️</span>
    </button>
</div>
