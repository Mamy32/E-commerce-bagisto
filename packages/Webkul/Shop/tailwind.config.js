/** @type {import('tailwindcss').Config} */
module.exports = {
    /**
     * Content paths — scans both the Bagisto Shop package views/js AND
     * our custom theme override views so Tailwind never purges classes
     * used only in our overridden Blade files.
     */
    content: [
        "./src/Resources/**/*.blade.php",
        "./src/Resources/**/*.js",
        "../../../resources/themes/default/views/**/*.blade.php",
        "../../../resources/themes/default/assets/**/*.js",
        "../../../resources/themes/default/assets/**/*.vue",
    ],

    theme: {
        container: {
            center: true,

            screens: {
                "2xl": "1440px",
            },

            padding: {
                DEFAULT: "90px",
            },
        },

        screens: {
            sm: "525px",
            md: "768px",
            lg: "1024px",
            xl: "1240px",
            "2xl": "1440px",
            1180: "1180px",
            1060: "1060px",
            991: "991px",
            868: "868px",
        },

        extend: {
            /**
             * ─── Bagisto default colors (preserved) ───────────────────────
             */
            colors: {
                navyBlue: "#060C3B",
                lightOrange: "#F6F2EB",
                darkGreen:  '#40994A',
                darkBlue:   '#0044F2',
                darkPink:   '#F85156',

                /**
                 * ─── LUXE Brand Design Tokens ─────────────────────────────
                 *
                 * Palette: Minimalist Luxury
                 * Usage:
                 *   fashion-surface  → page background (warm off-white)
                 *   fashion-navy     → primary text, nav background
                 *   fashion-accent   → gold CTA buttons, highlights
                 *   fashion-muted    → secondary text, borders
                 *   fashion-overlay  → dark overlay for modals / hero
                 */
                'fashion-surface':  '#FAF8F5',
                'fashion-navy':     '#0D1B2A',
                'fashion-accent':   '#C9A84C',
                'fashion-accent-dark': '#A8873A',
                'fashion-muted':    '#8A8A8A',
                'fashion-border':   '#E8E2DA',
                'fashion-overlay':  'rgba(13, 27, 42, 0.6)',
            },

            fontFamily: {
                poppins: ["Poppins", "sans-serif"],
                dmserif: ["DM Serif Display", "serif"],
            },

            /**
             * Custom spacing values used in the fashion layout.
             * Follows an 8-point grid system.
             */
            spacing: {
                '18': '4.5rem',
                '22': '5.5rem',
                '26': '6.5rem',
                '30': '7.5rem',
            },

            /**
             * Letter-spacing tokens for fashion editorial headings.
             */
            letterSpacing: {
                'fashion-tight':  '-0.02em',
                'fashion-wide':   '0.12em',
                'fashion-widest': '0.2em',
            },

            /**
             * Transition durations for micro-animations.
             */
            transitionDuration: {
                '400': '400ms',
            },
        },
    },

    plugins: [],

    safelist: [
        { pattern: /icon-/ },
        /**
         * Safelist our fashion utility classes to ensure they are never
         * purged even when only used in dynamically generated HTML.
         */
        { pattern: /fashion-/ },
    ],
};
