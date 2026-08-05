{{--
    LUXE Fashion Store — Main Header Wrapper Override
    ──────────────────────────────────────────────────
    Override of: packages/Webkul/Shop/src/Resources/views/
                 components/layouts/header/index.blade.php

    Changes from default:
      1. <header> bg-white → bg-fashion-surface, border instead of shadow
      2. Currency/locale top bar rendered only when multiple exist (preserved)
      3. All Vue component registrations are byte-identical to the original —
         only the wrapper <header> element's Tailwind classes have changed.

    Why override here instead of `header/desktop/index.blade.php`:
      The <header> HTML element with sticky positioning and background lives
      in THIS file. The desktop index only wraps the announcement bar +
      sub-components. The actual sticky header shell is here.
--}}

{!! view_render_event('bagisto.shop.layout.header.before') !!}

{{--
    ── LUXE Styled Header Shell ────────────────────────────────────────────
    The Bagisto topbar (currency/locale/offer) is rendered by the CORE
    x-shop::layouts.header component and cannot be cleanly removed via
    theme override without duplicating all Vue component registration code.

    Strategy: We let Bagisto render its topbar, then use CSS to restyle
    it with the fashion palette. The announcement bar content (offer text)
    is already shown in Bagisto's topbar — we just style it to match LUXE.
    The v-fashion-announcement component is available globally if needed
    for other pages.
--}}
<header class="sticky top-0 w-full left-0 right-0 z-[300] bg-white transition-all duration-500 lg:fixed lg:bg-transparent" id="luxe-main-header">
    <div id="luxe-main-header-inner" class="mx-auto max-w-[1440px] lg:px-8 transition-all duration-500">
    <v-header-switcher>
        {{-- Desktop Header Shimmer (shown before Vue hydrates) --}}
        <div class="flex flex-wrap max-lg:hidden">
            <div class="flex min-h-[72px] w-full justify-between border-b border-fashion-border px-[60px] max-1180:px-8">
                {{-- Left Navigation Section --}}
                <div class="flex items-center gap-x-10 max-[1180px]:gap-x-5">
                    {{-- Logo Shimmer --}}
                    <span
                        class="shimmer block h-[29px] w-[131px] rounded"
                        role="presentation"
                    ></span>

                    {{-- Categories Shimmer --}}
                    <div class="flex items-center gap-5">
                        <span class="shimmer h-6 w-20 rounded" role="presentation"></span>
                        <span class="shimmer h-6 w-20 rounded" role="presentation"></span>
                        <span class="shimmer h-6 w-20 rounded" role="presentation"></span>
                    </div>
                </div>

                {{-- Right Navigation Section --}}
                <div class="flex items-center gap-x-9 max-[1100px]:gap-x-6 max-lg:gap-x-8">
                    {{-- Search Bar Shimmer --}}
                    <div class="relative w-full max-w-[445px]">
                        <span class="shimmer block h-[42px] w-[250px] rounded-lg px-11 py-3" role="presentation"></span>
                    </div>

                    {{-- Right Navigation Icons Shimmer --}}
                    <div class="mt-1.5 flex gap-x-8 max-[1100px]:gap-x-6 max-lg:gap-x-8">
                        <span class="shimmer h-6 w-6 rounded" role="presentation"></span>
                        <span class="shimmer h-6 w-6 rounded" role="presentation"></span>
                        <span class="shimmer h-6 w-6 rounded" role="presentation"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mobile Header Shimmer --}}
        <div class="flex flex-wrap gap-4 px-4 pb-4 pt-6 shadow-sm lg:hidden">
            <div class="flex w-full items-center justify-between">
                <div class="flex items-center gap-x-1.5">
                    <span class="shimmer block h-6 w-6 rounded" role="presentation"></span>
                    <span class="shimmer block h-[29px] w-[131px] rounded" role="presentation"></span>
                </div>

                <div class="flex items-center gap-x-5 max-md:gap-x-4">
                    <span class="shimmer block h-6 w-6 rounded" role="presentation"></span>
                    <span class="shimmer block h-6 w-6 rounded" role="presentation"></span>
                    <span class="shimmer block h-6 w-6 rounded" role="presentation"></span>
                </div>
            </div>

            <div class="flex w-full items-center">
                <div class="relative w-full">
                    <span class="shimmer block h-[42px] w-full rounded-xl px-11 py-3.5 max-md:rounded-lg" role="presentation"></span>
                </div>
            </div>
        </div>
    </v-header-switcher>
    </div>
</header>

{!! view_render_event('bagisto.shop.layout.header.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-header-switcher-template"
    >
        <v-desktop-header v-if="isDesktop"></v-desktop-header>
        <v-mobile-header v-else></v-mobile-header>
    </script>

    <script type="module">
        app.component('v-header-switcher', {
            template: '#v-header-switcher-template',

            data() {
                return {
                    isDesktop: window.innerWidth >= 1024
                }
            },

            mounted() {
                this.media = window.matchMedia('(min-width: 1024px)');
                this.media.addEventListener('change', this.handleMedia);
            },

            beforeUnmount() {
                this.media.removeEventListener('change', this.handleMedia);
            },

            methods: {
                handleMedia(e) {
                    this.isDesktop = e.matches;
                }
            }
        });

        app.component('v-desktop-header', {
            template: '#v-desktop-header-template'
        });

        app.component('v-mobile-header', {
            template: '#v-mobile-header-template'
        });
    </script>

    <script
        type="text/x-template"
        id="v-desktop-header-template"
    >
        <x-shop::layouts.header.desktop />
    </script>

    <script
        type="text/x-template"
        id="v-mobile-header-template"
    >
        <x-shop::layouts.header.mobile />
    </script>

    <script type="module">
        /**
         * Make the navbar transparent at the top, and solid white on scroll.
         * We inject a <style> tag to bypass Vue 3's Virtual DOM which aggressively 
         * overwrites vanilla JS class manipulations inside the #app container.
         */
        (function () {
            // Create a dedicated style block in the document head
            const style = document.createElement('style');
            document.head.appendChild(style);

            // Use IntersectionObserver to detect scroll reliably
            const observer = new IntersectionObserver(
                ([e]) => {
                    const isScrolled = !e.isIntersecting;
                    
                    if (isScrolled) {
                        // Apply design-matched background and shadow using CSS rules
                        style.innerHTML = `
                            #luxe-main-header {
                                background-color: var(--color-surface) !important;
                                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
                                border-bottom: 1px solid rgba(0,0,0,0.05) !important;
                                --nav-dynamic-color: #2F394A !important;
                            }
                            @media (min-width: 1024px) {
                                #luxe-main-header .dynamic-nav-element { color: var(--nav-dynamic-color) !important; }
                                #luxe-main-header .dynamic-nav-placeholder::placeholder { color: var(--nav-dynamic-color) !important; }
                            }
                        `;
                    } else {
                        // Revert to transparent
                        style.innerHTML = `
                            #luxe-main-header {
                                background-color: transparent !important;
                                box-shadow: none !important;
                                border-bottom: none !important;
                                --nav-dynamic-color: #ffffff !important;
                            }
                            @media (min-width: 1024px) {
                                #luxe-main-header .dynamic-nav-element { color: var(--nav-dynamic-color) !important; }
                                #luxe-main-header .dynamic-nav-placeholder::placeholder { color: var(--nav-dynamic-color) !important; }
                            }
                        `;
                    }
                },
                { threshold: [0] }
            );

            // Create an invisible 50px anchor at the absolute top of the page
            const anchor = document.createElement('div');
            anchor.style.position = 'absolute';
            anchor.style.top = '0';
            anchor.style.left = '0';
            anchor.style.width = '100%';
            anchor.style.height = '50px';
            anchor.style.pointerEvents = 'none';
            anchor.style.visibility = 'hidden';
            
            // Insert it as the very first child of body
            document.body.prepend(anchor);
            observer.observe(anchor);
        })();
    </script>
@endPushOnce
