import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/**
 * Tailwind configuration for the BACK OFFICE.
 *
 * The palette, typography and button metrics mirror the Orisa template's own
 * design tokens (the --at-* custom properties in public/assets/css/main.css),
 * so the dashboard reads as the same product as the public site without
 * pulling in that 1 MB stylesheet — the dashboard's tables, sidebar and form
 * grids have no counterpart in the template anyway.
 *
 * The public site uses tailwind.site.config.js instead.
 */

/** Orisa neutral ramp, copied from main.css :root. */
const neutral = {
    0: '#FEFEFE',
    50: '#F2F2F2',
    100: '#DFDFDF',
    200: '#CDCCCC',
    300: '#B7B7B7',
    500: '#585959',
    700: '#303030',
    800: '#212121',
    900: '#1D1D1D',
    950: '#0F0F0F',
};

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php',
    ],

    theme: {
        extend: {
            colors: {
                neutral,

                // Semantic aliases used across the back-office markup.
                ink: {
                    DEFAULT: neutral[900],
                    soft: neutral[800],
                    muted: neutral[500],
                },
                paper: {
                    DEFAULT: neutral[0],
                    warm: neutral[50],
                    dim: neutral[100],
                },
                // Bleu de marque ESCAT, utilisé pour les boutons pleins.
                brand: {
                    DEFAULT: '#2646D0',
                    dark: '#1C35A0',
                },

                accent: {
                    DEFAULT: '#F0460E',
                    soft: '#FF7A4D',
                    deep: '#C0330A',
                },

                // Palette du tableau de bord (d'après la maquette Velzon) :
                // fond d'application gris-bleu, cartes blanches, pastilles
                // pastel pour les tuiles d'icônes, vert/rouge pour les
                // variations. Le bleu de marque ESCAT reste la couleur d'action.
                app: {
                    bg: '#F3F3F9',
                    card: '#FFFFFF',
                    line: '#E9EBEC',
                    text: '#212529',
                    muted: '#878A99',
                },
                tile: {
                    blue: '#DFF0FA',
                    'blue-ink': '#299CDB',
                    amber: '#FEF4E4',
                    'amber-ink': '#F7B84B',
                    cyan: '#DAF4F0',
                    'cyan-ink': '#0AB39C',
                    violet: '#EDE7FB',
                    'violet-ink': '#6559CC',
                },
                trend: {
                    up: '#0AB39C',
                    'up-bg': '#DAF4F0',
                    down: '#F06548',
                    'down-bg': '#FDE8E4',
                },
            },

            fontFamily: {
                // The template declares DM Sans and lets it fall back to the
                // system sans; the public site does the same, so the two match.
                sans: ['"DM Sans"', ...defaultTheme.fontFamily.sans],
                display: ['"DM Sans"', ...defaultTheme.fontFamily.sans],
            },

            fontSize: {
                'display-sm': ['clamp(1.5rem, 1rem + 1.6vw, 2.25rem)', { lineHeight: '1.2', letterSpacing: '-0.01em' }],
                'display-md': ['clamp(2rem, 1.2rem + 3vw, 3rem)', { lineHeight: '1.15', letterSpacing: '-0.01em' }],
                // Orisa's button metrics: small, medium weight, wide tracking.
                btn: ['12px', { lineHeight: '1.5', letterSpacing: '0.05em' }],
            },

            borderRadius: {
                // rounded-4 / rounded-5 in the template.
                card: '1rem',
                panel: '2rem',
            },

            maxWidth: {
                prose: '68ch',
            },

            transitionTimingFunction: {
                'out-expo': 'cubic-bezier(0.16, 1, 0.3, 1)',
            },
        },
    },

    plugins: [forms, typography],
};
