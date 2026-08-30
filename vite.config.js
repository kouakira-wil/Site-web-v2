import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Back office: Tailwind with preflight (tailwind.config.js).
                'resources/css/app.css',
                'resources/js/app.js',
                // Public site: Tailwind without preflight (tailwind.site.config.js).
                'resources/css/site.css',
                'resources/css/brand.css',
                'resources/js/site.js',
            ],
            refresh: true,
        }),
    ],
});
