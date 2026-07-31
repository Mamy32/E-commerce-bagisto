@php
    $showCompare = (bool) core()->getConfigData('catalog.products.settings.compare_option');
    $showWishlist = (bool) core()->getConfigData('customer.settings.wishlist.wishlist_option');
@endphp

<div class="flex flex-col gap-3 border-b border-fashion-border bg-white px-4 pt-4 pb-3 shadow-sm lg:hidden">

    <div class="flex items-center justify-between">

        <div class="flex items-center gap-3">
            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.before') !!}

            <v-mobile-drawer></v-mobile-drawer>

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.after') !!}

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.logo.before') !!}

            <a
                href="{{ route('shop.home.index') }}"
                class="flex items-center"
                aria-label="{{ config('app.name') }}"
            >
                <img
                    class="block max-h-[40px] w-auto object-contain"
                    src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                    alt="{{ config('app.name') }}"
                    width="180"
                    height="40"
                >
            </a>

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.logo.after') !!}
        </div>

        <div class="flex items-center gap-4">
            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.compare.before') !!}

            @if ($showCompare)
                <a
                    href="{{ route('shop.compare.index') }}"
                    class="text-fashion-navy transition-opacity hover:opacity-60"
                    aria-label="@lang('shop::app.components.layouts.header.mobile.compare')"
                >
                    <span class="text-2xl icon-compare"></span>
                </a>
            @endif

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.compare.after') !!}

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.mini_cart.before') !!}

            @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                @include('shop::checkout.cart.mini-cart')
            @endif

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.mini_cart.after') !!}

            <div class="max-md:hidden">
                <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                    <x-slot:toggle>
                        <span class="cursor-pointer text-2xl text-fashion-navy icon-users"></span>
                    </x-slot>

                    @guest('customer')
                        <x-slot:content>
                            <div class="grid gap-2">
                                <p class="font-dmserif text-xl text-fashion-navy">
                                    @lang('shop::app.components.layouts.header.mobile.welcome-guest')
                                </p>
                                <p class="text-sm text-fashion-muted">
                                    @lang('shop::app.components.layouts.header.mobile.dropdown-text')
                                </p>
                            </div>

                            <hr class="my-3 border-fashion-border">

                            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.customers_action.before') !!}

                            <div class="flex gap-3 mt-2">
                                <a
                                    href="{{ route('shop.customer.session.create') }}"
                                    class="primary-button rounded-lg px-5 py-2.5 text-xs"
                                >
                                    @lang('shop::app.components.layouts.header.mobile.sign-in')
                                </a>

                                <a
                                    href="{{ route('shop.customers.register.index') }}"
                                    class="secondary-button rounded-lg px-5 py-2.5 text-xs"
                                >
                                    @lang('shop::app.components.layouts.header.mobile.sign-up')
                                </a>
                            </div>

                            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.customers_action.after') !!}
                        </x-slot>
                    @endguest

                    @auth('customer')
                        <x-slot:content class="!p-0">
                            <div class="grid gap-2 p-4 pb-0">
                                <p class="font-dmserif text-xl text-fashion-navy" v-pre>
                                    @lang('shop::app.components.layouts.header.mobile.welcome')'
                                    {{ auth()->guard('customer')->user()->first_name }}
                                </p>
                                <p class="text-sm text-fashion-muted">
                                    @lang('shop::app.components.layouts.header.mobile.dropdown-text')
                                </p>
                            </div>

                            <hr class="my-3 border-fashion-border">

                            <div class="grid gap-0.5 pb-2">
                                <a href="{{ route('shop.customers.account.profile.index') }}" class="px-4 py-2 text-sm text-fashion-navy hover:bg-fashion-surface">
                                    @lang('shop::app.components.layouts.header.mobile.profile')
                                </a>
                                <a href="{{ route('shop.customers.account.orders.index') }}" class="px-4 py-2 text-sm text-fashion-navy hover:bg-fashion-surface">
                                    @lang('shop::app.components.layouts.header.mobile.orders')
                                </a>
                                @if ($showWishlist)
                                    <a href="{{ route('shop.customers.account.wishlist.index') }}" class="px-4 py-2 text-sm text-fashion-navy hover:bg-fashion-surface">
                                        @lang('shop::app.components.layouts.header.mobile.wishlist')
                                    </a>
                                @endif
                                @auth('customer')
                                    <x-shop::form method="DELETE" action="{{ route('shop.customer.session.destroy') }}" id="customerLogout" />
                                    <a
                                        href="{{ route('shop.customer.session.destroy') }}"
                                        class="px-4 py-2 text-sm text-fashion-muted hover:bg-fashion-surface"
                                        onclick="event.preventDefault(); document.getElementById('customerLogout').submit();"
                                    >
                                        @lang('shop::app.components.layouts.header.mobile.logout')
                                    </a>
                                @endauth
                            </div>
                        </x-slot>
                    @endauth
                </x-shop::dropdown>
            </div>

            <div class="md:hidden">
                @guest('customer')
                    <a href="{{ route('shop.customer.session.create') }}" class="text-fashion-navy" aria-label="@lang('shop::app.components.layouts.header.mobile.account')">
                        <span class="text-2xl icon-users"></span>
                    </a>
                @endguest

                @auth('customer')
                    <a href="{{ route('shop.customers.account.index') }}" class="text-fashion-navy" aria-label="@lang('shop::app.components.layouts.header.mobile.account')">
                        <span class="text-2xl icon-users"></span>
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.search.before') !!}

    <form
        action="{{ route('shop.search.index') }}"
        class="flex w-full items-center"
        role="search"
    >
        <label for="mobile-search" class="sr-only">
            @lang('shop::app.components.layouts.header.mobile.search')
        </label>

        <div class="relative w-full">
            <div class="pointer-events-none absolute top-1/2 -translate-y-1/2 text-xl text-fashion-muted ltr:left-3 rtl:right-3 icon-search"></div>

            <input
                id="mobile-search"
                type="text"
                name="query"
                value="{{ request('query') }}"
                class="block w-full rounded-lg border border-fashion-border bg-white py-2.5 pl-10 pr-4 text-sm font-medium text-fashion-navy placeholder-fashion-muted focus:border-fashion-navy focus:outline-none"
                placeholder="@lang('shop::app.components.layouts.header.mobile.search-text')"
                required
            >

            @if (core()->getConfigData('catalog.products.settings.image_search'))
                @include('shop::search.images.index')
            @endif
        </div>
    </form>

    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.search.after') !!}
</div>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-mobile-drawer-template"
    >
        <x-shop::drawer
            position="left"
            width="100%"
            @close="onDrawerClose"
        >
            <x-slot:toggle>
                <span class="cursor-pointer text-2xl text-fashion-navy icon-hamburger"></span>
            </x-slot>

            <x-slot:header class="border-b border-fashion-border">
                <a href="{{ route('shop.home.index') }}">
                    <img
                        src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                        alt="{{ config('app.name') }}"
                        width="180"
                        height="40"
                        class="max-h-[40px] w-auto object-contain"
                    >
                </a>
            </x-slot>

            <x-slot:content class="!p-0">
                <div class="border-b border-fashion-border p-4">
                    <div class="grid grid-cols-[auto_1fr] items-center gap-3 rounded-lg border border-fashion-border bg-fashion-surface p-3">
                        <img
                            src="{{ auth()->user()?->image_url ?? bagisto_asset('images/user-placeholder.png') }}"
                            class="h-12 w-12 rounded-full object-cover"
                            alt="User avatar"
                        >

                        @guest('customer')
                            <a
                                href="{{ route('shop.customer.session.create') }}"
                                class="flex items-center gap-2 text-sm font-semibold text-fashion-navy"
                            >
                                @lang('shop::app.components.layouts.header.mobile.login')
                                <span class="text-xl icon-double-arrow"></span>
                            </a>
                        @endguest

                        @auth('customer')
                            <div class="flex flex-col gap-0.5" v-pre>
                                <p class="text-base font-semibold text-fashion-navy">{{ auth()->user()?->first_name }}</p>
                                <p class="text-xs text-fashion-muted">{{ auth()->user()?->email }}</p>
                            </div>
                        @endauth
                    </div>
                </div>

                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.categories.before') !!}

                <v-mobile-category ref="mobileCategory"></v-mobile-category>

                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.categories.after') !!}
            </x-slot>

            <x-slot:footer>
                @if (core()->getCurrentChannel()->locales()->count() > 1 || core()->getCurrentChannel()->currencies()->count() > 1)
                    <div class="fixed bottom-0 z-10 grid w-full max-w-full grid-cols-[1fr_auto_1fr] items-center justify-items-center border-t border-fashion-border bg-white px-4 ltr:left-0 rtl:right-0">

                        <x-shop::drawer position="bottom" width="100%">
                            <x-slot:toggle>
                                <div class="cursor-pointer px-3 py-3.5 text-sm font-medium uppercase text-fashion-navy" role="button" v-pre>
                                    {{ core()->getCurrentCurrency()->symbol . ' ' . core()->getCurrentCurrencyCode() }}
                                </div>
                            </x-slot>
                            <x-slot:header class="border-b border-fashion-border">
                                <p class="text-base font-semibold text-fashion-navy">
                                    @lang('shop::app.components.layouts.header.mobile.currencies')
                                </p>
                            </x-slot>
                            <x-slot:content class="!px-0">
                                <div class="overflow-auto" :style="{ height: getCurrentScreenHeight }">
                                    <v-currency-switcher></v-currency-switcher>
                                </div>
                            </x-slot>
                        </x-shop::drawer>

                        <span class="h-5 w-px bg-fashion-border"></span>

                        <x-shop::drawer position="bottom" width="100%">
                            <x-slot:toggle>
                                <div class="flex cursor-pointer items-center gap-2 px-3 py-3.5 text-sm font-medium uppercase text-fashion-navy" role="button" v-pre>
                                    <img
                                        src="{{ ! empty(core()->getCurrentLocale()->logo_url) ? core()->getCurrentLocale()->logo_url : bagisto_asset('images/default-language.svg') }}"
                                        class="h-4 w-6 object-cover"
                                        alt="Language"
                                        width="24"
                                        height="16"
                                    />
                                    {{ core()->getCurrentChannel()->locales()->orderBy('name')->where('code', app()->getLocale())->value('name') }}
                                </div>
                            </x-slot>
                            <x-slot:header class="border-b border-fashion-border">
                                <p class="text-base font-semibold text-fashion-navy">
                                    @lang('shop::app.components.layouts.header.mobile.locales')
                                </p>
                            </x-slot>
                            <x-slot:content class="!px-0">
                                <div class="overflow-auto" :style="{ height: getCurrentScreenHeight }">
                                    <v-locale-switcher></v-locale-switcher>
                                </div>
                            </x-slot>
                        </x-shop::drawer>
                    </div>
                @endif
            </x-slot>
        </x-shop::drawer>
    </script>

    <script
        type="text/x-template"
        id="v-mobile-category-template"
    >
        <div class="relative h-full overflow-hidden">
            <div
                class="flex h-full transition-transform duration-300"
                :class="{
                    'ltr:translate-x-0 rtl:translate-x-0': currentViewLevel !== 'third',
                    'ltr:-translate-x-full rtl:translate-x-full': currentViewLevel === 'third'
                }"
            >
                <div class="h-full w-full flex-shrink-0 overflow-auto px-4 py-4">
                    <div v-for="category in categories" :key="category.id">
                        <div class="py-2.5">
                            <a :href="category.url" class="text-sm font-semibold uppercase tracking-wide text-fashion-navy">
                                @{{ category.name }}
                            </a>
                        </div>

                        <div v-if="category.children && category.children.length">
                            <div
                                v-for="secondLevelCategory in category.children"
                                :key="secondLevelCategory.id"
                                class="flex items-center justify-between py-2 pl-4"
                                @click="showThirdLevel(secondLevelCategory, category, $event)"
                            >
                                <a :href="secondLevelCategory.url" class="text-sm text-fashion-muted">
                                    @{{ secondLevelCategory.name }}
                                </a>
                                <span
                                    v-if="secondLevelCategory.children && secondLevelCategory.children.length"
                                    class="text-fashion-muted icon-arrow-right rtl:icon-arrow-left"
                                ></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-full w-full flex-shrink-0" v-if="currentViewLevel === 'third'">
                    <div class="border-b border-fashion-border px-4 py-4">
                        <button @click="goBackToMainView" class="flex items-center gap-2 text-fashion-navy focus:outline-none">
                            <span class="text-lg icon-arrow-left rtl:icon-arrow-right"></span>
                            <span class="text-sm font-medium">
                                @lang('shop::app.components.layouts.header.mobile.back-button')
                            </span>
                        </button>
                    </div>

                    <div class="px-4 py-4">
                        <a
                            v-for="thirdLevelCategory in currentSecondLevelCategory?.children"
                            :key="thirdLevelCategory.id"
                            :href="thirdLevelCategory.url"
                            class="block py-2.5 text-sm text-fashion-navy hover:text-fashion-accent"
                        >
                            @{{ thirdLevelCategory.name }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-mobile-category', {
            template: '#v-mobile-category-template',

            data() {
                return {
                    categories: [],
                    currentViewLevel: 'main',
                    currentSecondLevelCategory: null,
                    currentParentCategory: null,
                };
            },

            mounted() {
                this.initCategories();
            },

            computed: {
                getCurrentScreenHeight() {
                    return window.innerHeight - (window.innerWidth < 920 ? 61 : 0) + 'px';
                },
            },

            methods: {
                initCategories() {
                    try {
                        const stored = localStorage.getItem('categories');
                        if (stored) {
                            this.categories = JSON.parse(stored);
                            return;
                        }
                    } catch (e) {}
                    this.getCategories();
                },

                getCategories() {
                    this.$axios
                        .get('{{ route('shop.api.categories.tree') }}')
                        .then(response => {
                            this.categories = response.data.data;
                            localStorage.setItem('categories', JSON.stringify(this.categories));
                        })
                        .catch(error => console.log(error));
                },

                showThirdLevel(secondLevelCategory, parentCategory, event) {
                    if (secondLevelCategory.children && secondLevelCategory.children.length) {
                        this.currentSecondLevelCategory = secondLevelCategory;
                        this.currentParentCategory = parentCategory;
                        this.currentViewLevel = 'third';
                        if (event) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                    }
                },

                goBackToMainView() {
                    this.currentViewLevel = 'main';
                },
            },
        });

        app.component('v-mobile-drawer', {
            template: '#v-mobile-drawer-template',

            methods: {
                onDrawerClose() {
                    this.$refs.mobileCategory.currentViewLevel = 'main';
                },
            },
        });
    </script>
@endPushOnce