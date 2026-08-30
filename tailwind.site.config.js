/**
 * Tailwind configuration for the PUBLIC site only.
 *
 * The public pages keep the original template stylesheet (assets/css/main.css),
 * which ships its own base typography, CSS variables and component styles.
 * Two consequences drive this config:
 *
 *  1. Preflight is disabled. Tailwind's reset would flatten the template's
 *     h1–h6 sizing and body typography, which main.css defines itself.
 *  2. Breakpoints and container widths mirror Bootstrap 5 exactly, so markup
 *     converted from `col-lg-6` to `lg:w-1/2` breaks at the same widths as
 *     before the migration.
 *
 * The back office uses tailwind.config.js instead, with preflight enabled.
 */

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/layouts/site.blade.php',
        './resources/views/site/**/*.blade.php',
        './resources/views/components/site/**/*.blade.php',
    ],

    corePlugins: {
        preflight: false,
    },

    theme: {
        // Bootstrap 5 breakpoints.
        screens: {
            sm: '576px',
            md: '768px',
            lg: '992px',
            xl: '1200px',
            '2xl': '1400px',
        },

        container: {
            center: true,
            padding: '0.75rem',
            // Bootstrap 5 container max-widths.
            screens: {
                sm: '540px',
                md: '720px',
                lg: '960px',
                xl: '1140px',
                '2xl': '1320px',
            },
        },

        extend: {},
    },

    plugins: [],
};
