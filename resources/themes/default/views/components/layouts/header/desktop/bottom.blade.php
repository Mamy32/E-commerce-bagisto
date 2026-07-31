{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.before') !!}

<div class="flex min-h-[78px] w-full items-center justify-between px-10">

    {{-- LEFT: Logo --}}
    <div class="flex items-center">

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.before') !!}

        <a
            href="{{ route('shop.home.index') }}"
            aria-label="{{ config('app.name') }}"
            class="flex-shrink-0"
        >
            <img
                src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                width="220"
                height="55"
                alt="{{ config('app.name') }}"
                class="h-auto max-h-[55px] w-auto object-contain"
            >
        </a>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.after') !!}

    </div>

    {{-- CENTER: Category Navigation --}}
    <div class="hidden lg:flex items-center justify-center flex-1">
        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.category.before') !!}

        <v-desktop-category>
            <div
                class="flex items-center gap-8"
                aria-hidden="true"
            >
                <span class="h-4 w-16 rounded shimmer"></span>
                <span class="h-4 w-20 rounded shimmer"></span>
                <span class="h-4 w-14 rounded shimmer"></span>
            </div>
        </v-desktop-category>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.category.after') !!}
    </div>

    {{-- RIGHT: Search + Icons --}}
    <div class="flex items-center gap-x-6">

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.before') !!}

        <div class="relative flex items-center group">
            <form
                action="{{ route('shop.search.index') }}"
                class="flex items-center"
                role="search"
                toolname="search_products"
                tooldescription="{{ trans('shop::app.components.layouts.webmcp.search-products') }}"
                toolautosubmit
            >
                <label for="organic-search" class="sr-only">@lang('shop::app.components.layouts.header.desktop.bottom.search')</label>

                <div class="icon-search pointer-events-none absolute z-10 flex items-center text-2xl text-fashion-navy left-2"></div>

                <input
                    id="organic-search"
                    type="text"
                    name="query"
                    value="{{ request('query') }}"
                    class="w-10 opacity-0 focus:w-[260px] xl:focus:w-[340px] focus:opacity-100 focus:pl-10 focus:bg-white/50 focus:border-b focus:border-fashion-navy transition-all duration-400 bg-transparent text-sm font-medium text-fashion-navy placeholder-fashion-muted outline-none py-2 rounded-full cursor-pointer group-hover:w-[260px] xl:group-hover:w-[340px] group-hover:opacity-100 group-hover:pl-10 group-hover:bg-white/50"
                    placeholder="@lang('shop::app.components.layouts.header.desktop.bottom.search-text')"
                    aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.search-text')"
                    required
                >

                <button type="submit" class="hidden"></button>
            </form>
        </div>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.after') !!}

        <div class="flex items-center gap-x-5">

            <!-- Locales Switcher -->
            <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                <x-slot:toggle>
                    <div
                        class="flex cursor-pointer items-center gap-1.5 hover:opacity-60 transition-opacity"
                        role="button"
                        tabindex="0"
                    >
                        <img
                            src="{{ ! empty(core()->getCurrentLocale()->logo_url)
                                    ? core()->getCurrentLocale()->logo_url
                                    : bagisto_asset('images/default-language.svg')
                                }}"
                            class="h-[16px] w-[24px] object-cover rounded-sm"
                            alt="Locale"
                        />
                        
                        <span class="text-sm font-medium text-fashion-navy" v-pre>
                            {{ strtoupper(app()->getLocale()) }}
                        </span>

                        <span class="text-xl icon-arrow-down" role="presentation"></span>
                    </div>
                </x-slot>
            
                <x-slot:content class="journal-scroll max-h-[500px] !p-0">
                    <v-locale-switcher></v-locale-switcher>
                </x-slot>
            </x-shop::dropdown>

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.compare.before') !!}

            @if (core()->getConfigData('catalog.products.settings.compare_option'))
                <a
                    href="{{ route('shop.compare.index') }}"
                    class="flex items-center text-fashion-navy transition-opacity hover:opacity-60"
                    aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.compare')"
                >
                    <span class="text-2xl icon-compare" role="presentation"></span>
                </a>
            @endif

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.compare.after') !!}

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.mini_cart.before') !!}

            @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                @include('shop::checkout.cart.mini-cart')
            @endif

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.mini_cart.after') !!}

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile.before') !!}

            <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">

                <x-slot:toggle>
                    <span
                        class="flex cursor-pointer items-center text-2xl text-fashion-navy transition-opacity hover:opacity-60 icon-users"
                        role="button"
                        aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.profile')"
                        tabindex="0"
                    ></span>
                </x-slot>

                @guest('customer')
                    <x-slot:content>
                        <div class="grid gap-2">
                            <p class="font-dmserif text-xl text-fashion-navy">
                                @lang('shop::app.components.layouts.header.desktop.bottom.welcome-guest')
                            </p>

                            <p class="text-sm text-fashion-muted">
                                @lang('shop::app.components.layouts.header.desktop.bottom.dropdown-text')
                            </p>
                        </div>

                        <hr class="my-3 border-fashion-border">

                        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.customers_action.before') !!}

                        <div class="flex gap-3 mt-2">
                            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.sign_in_button.before') !!}

                            <a
                                href="{{ route('shop.customer.session.create') }}"
                                class="primary-button rounded-lg px-6 py-2.5 text-xs"
                            >
                                @lang('shop::app.components.layouts.header.desktop.bottom.sign-in')
                            </a>

                            <a
                                href="{{ route('shop.customers.register.index') }}"
                                class="secondary-button rounded-lg px-6 py-2.5 text-xs"
                            >
                                @lang('shop::app.components.layouts.header.desktop.bottom.sign-up')
                            </a>

                            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.sign_up_button.after') !!}
                        </div>

                        @if (core()->getConfigData('sales.eu_withdrawal.general.enabled', core()->getCurrentChannelCode()))
                            <a
                                href="{{ route('shop.eu-withdrawal.guest.lookup') }}"
                                class="mt-4 inline-flex items-center gap-1 text-xs font-medium text-fashion-muted hover:text-fashion-navy"
                            >
                                @lang('shop::app.eu_withdrawal.guest_dropdown.link')
                            </a>
                        @endif

                        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.customers_action.after') !!}
                    </x-slot>
                @endguest

                @auth('customer')
                    <x-slot:content class="!p-0">
                        <div class="grid gap-2 p-4 pb-0">
                            <p class="font-dmserif text-xl text-fashion-navy" v-pre>
                                @lang('shop::app.components.layouts.header.desktop.bottom.welcome')'
                                {{ auth()->guard('customer')->user()->first_name }}
                            </p>

                            <p class="text-sm text-fashion-muted">
                                @lang('shop::app.components.layouts.header.desktop.bottom.dropdown-text')
                            </p>
                        </div>

                        <hr class="my-3 border-fashion-border">

                        <div class="grid gap-0.5 pb-2">
                            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile_dropdown.links.before') !!}

                            <a
                                href="{{ route('shop.customers.account.profile.index') }}"
                                class="px-4 py-2 text-sm text-fashion-navy hover:bg-fashion-surface transition-colors"
                            >
                                @lang('shop::app.components.layouts.header.desktop.bottom.profile')
                            </a>

                            <a
                                href="{{ route('shop.customers.account.orders.index') }}"
                                class="px-4 py-2 text-sm text-fashion-navy hover:bg-fashion-surface transition-colors"
                            >
                                @lang('shop::app.components.layouts.header.desktop.bottom.orders')
                            </a>

                            @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                                <a
                                    href="{{ route('shop.customers.account.wishlist.index') }}"
                                    class="px-4 py-2 text-sm text-fashion-navy hover:bg-fashion-surface transition-colors"
                                >
                                    @lang('shop::app.components.layouts.header.desktop.bottom.wishlist')
                                </a>
                            @endif

                            @auth('customer')
                                <x-shop::form
                                    method="DELETE"
                                    action="{{ route('shop.customer.session.destroy') }}"
                                    id="customerLogout"
                                />

                                <a
                                    href="{{ route('shop.customer.session.destroy') }}"
                                    class="px-4 py-2 text-sm text-fashion-muted hover:bg-fashion-surface transition-colors"
                                    onclick="event.preventDefault(); document.getElementById('customerLogout').submit();"
                                >
                                    @lang('shop::app.components.layouts.header.desktop.bottom.logout')
                                </a>
                            @endauth

                            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile_dropdown.links.after') !!}
                        </div>
                    </x-slot>
                @endauth

            </x-shop::dropdown>

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile.after') !!}
        </div>
    </div>
</div>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-desktop-category-template"
    >
        <div
            class="flex items-center gap-8"
            v-if="isLoading"
            aria-hidden="true"
        >
            <span class="h-4 w-16 rounded shimmer"></span>
            <span class="h-4 w-20 rounded shimmer"></span>
            <span class="h-4 w-14 rounded shimmer"></span>
        </div>

        <div
            class="flex items-center"
            v-else-if="'{{ core()->getConfigData('general.design.categories.category_view') }}' !== 'sidebar'"
        >
            <div
                class="nav-category-item group relative flex h-[77px] items-center border-b-2 border-transparent hover:border-fashion-accent"
                v-for="category in categories"
                :key="category.id"
            >
                <a
                    :href="category.url"
                    class="inline-block px-4 text-sm font-medium uppercase tracking-wider text-fashion-navy transition-opacity group-hover:opacity-60"
                >
                    @{{ category.name }}
                </a>

                <div
                    class="pointer-events-none absolute top-[78px] z-20 max-h-[560px] w-max max-w-[1200px] translate-y-2 overflow-auto rounded-b-lg border-t-2 border-fashion-accent bg-white p-8 opacity-0 shadow-lg transition duration-200 ease-out group-hover:pointer-events-auto group-hover:translate-y-0 group-hover:opacity-100 ltr:-left-8 rtl:-right-8"
                    v-if="category.children && category.children.length"
                >
                    <div class="flex gap-x-16">
                        <div
                            class="grid min-w-[120px] max-w-[160px] flex-auto content-start gap-4"
                            v-for="pairCategoryChildren in pairCategoryChildren(category)"
                        >
                            <template v-for="secondLevelCategory in pairCategoryChildren">
                                <p class="text-xs font-semibold uppercase tracking-widest text-fashion-accent">
                                    <a :href="secondLevelCategory.url">@{{ secondLevelCategory.name }}</a>
                                </p>

                                <ul
                                    class="grid gap-2"
                                    v-if="secondLevelCategory.children && secondLevelCategory.children.length"
                                >
                                    <li
                                        class="text-sm text-fashion-navy transition-opacity hover:opacity-60"
                                        v-for="thirdLevelCategory in secondLevelCategory.children"
                                    >
                                        <a :href="thirdLevelCategory.url">@{{ thirdLevelCategory.name }}</a>
                                    </li>
                                </ul>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else>
            <div class="flex items-center">
                <div
                    class="flex h-[77px] cursor-pointer items-center border-b-2 border-transparent hover:border-fashion-accent"
                    @click="toggleCategoryDrawer"
                >
                    <span class="flex items-center gap-1.5 px-4 text-sm font-medium uppercase tracking-wider text-fashion-navy transition-opacity hover:opacity-60">
                        <span class="text-xl icon-hamburger"></span>
                        @lang('shop::app.components.layouts.header.desktop.bottom.all')
                    </span>
                </div>

                <div
                    class="nav-category-item group relative flex h-[77px] items-center border-b-2 border-transparent hover:border-fashion-accent"
                    v-for="category in categories.slice(0, 4)"
                    :key="category.id"
                >
                    <a
                        :href="category.url"
                        class="inline-block px-4 text-sm font-medium uppercase tracking-wider text-fashion-navy transition-opacity group-hover:opacity-60"
                    >
                        @{{ category.name }}
                    </a>

                    <div
                        class="pointer-events-none absolute top-[78px] z-20 max-h-[560px] w-max max-w-[1200px] translate-y-2 overflow-auto rounded-b-lg border-t-2 border-fashion-accent bg-white p-8 opacity-0 shadow-lg transition duration-200 ease-out group-hover:pointer-events-auto group-hover:translate-y-0 group-hover:opacity-100 ltr:-left-8 rtl:-right-8"
                        v-if="category.children && category.children.length"
                    >
                        <div class="flex gap-x-16">
                            <div
                                class="grid min-w-[120px] max-w-[160px] flex-auto content-start gap-4"
                                v-for="pairCategoryChildren in pairCategoryChildren(category)"
                            >
                                <template v-for="secondLevelCategory in pairCategoryChildren">
                                    <p class="text-xs font-semibold uppercase tracking-widest text-fashion-accent">
                                        <a :href="secondLevelCategory.url">@{{ secondLevelCategory.name }}</a>
                                    </p>

                                    <ul
                                        class="grid gap-2"
                                        v-if="secondLevelCategory.children && secondLevelCategory.children.length"
                                    >
                                        <li
                                            class="text-sm text-fashion-navy hover:text-fashion-accent"
                                            v-for="thirdLevelCategory in secondLevelCategory.children"
                                        >
                                            <a :href="thirdLevelCategory.url">@{{ thirdLevelCategory.name }}</a>
                                        </li>
                                    </ul>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <x-shop::drawer
                position="left"
                width="400px"
                ::is-active="isDrawerActive"
                @toggle="onDrawerToggle"
                @close="onDrawerClose"
            >
                <x-slot:toggle></x-slot>

                <x-slot:header class="border-b border-fashion-border">
                    <p class="font-dmserif text-xl text-fashion-navy">
                        @lang('shop::app.components.layouts.header.desktop.bottom.categories')
                    </p>
                </x-slot>

                <x-slot:content class="!px-0">
                    <div class="relative h-full overflow-hidden">
                        <div
                            class="flex h-full transition-transform duration-300"
                            :class="{
                                'ltr:translate-x-0 rtl:translate-x-0': currentViewLevel !== 'third',
                                'ltr:-translate-x-full rtl:translate-x-full': currentViewLevel === 'third'
                            }"
                        >
                            <div class="h-[calc(100vh-74px)] w-full flex-shrink-0 overflow-auto py-4">
                                <div
                                    v-for="category in categories"
                                    :key="category.id"
                                >
                                    <div class="px-6 py-2.5 hover:bg-fashion-surface transition-colors">
                                        <a
                                            :href="category.url"
                                            class="text-sm font-medium text-fashion-navy"
                                        >
                                            @{{ category.name }}
                                        </a>
                                    </div>

                                    <div v-if="category.children && category.children.length">
                                        <div
                                            v-for="secondLevelCategory in category.children"
                                            :key="secondLevelCategory.id"
                                            class="flex items-center justify-between px-8 py-2 hover:bg-fashion-surface transition-colors cursor-pointer"
                                            @click="showThirdLevel(secondLevelCategory, category, $event)"
                                        >
                                            <a
                                                :href="secondLevelCategory.url"
                                                class="text-sm text-fashion-muted"
                                            >
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

                            <div
                                class="h-full w-full flex-shrink-0"
                                v-if="currentViewLevel === 'third'"
                            >
                                <div class="border-b border-fashion-border px-6 py-4">
                                    <button
                                        @click="goBackToMainView"
                                        class="flex items-center gap-2 text-fashion-navy focus:outline-none"
                                    >
                                        <span class="text-lg icon-arrow-left rtl:icon-arrow-right"></span>
                                        <span class="text-sm font-medium">
                                            @lang('shop::app.components.layouts.header.desktop.bottom.back-button')
                                        </span>
                                    </button>
                                </div>

                                <div class="py-4">
                                    <a
                                        v-for="thirdLevelCategory in currentSecondLevelCategory?.children"
                                        :key="thirdLevelCategory.id"
                                        :href="thirdLevelCategory.url"
                                        class="block px-6 py-2.5 text-sm text-fashion-navy hover:bg-fashion-surface transition-colors"
                                    >
                                        @{{ thirdLevelCategory.name }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-slot>
            </x-shop::drawer>
        </div>
    </script>

    <script type="module">
        app.component('v-desktop-category', {
            template: '#v-desktop-category-template',

            data() {
                return {
                    isLoading: true,
                    categories: [],
                    isDrawerActive: false,
                    currentViewLevel: 'main',
                    currentSecondLevelCategory: null,
                    currentParentCategory: null,
                };
            },

            mounted() {
                this.initCategories();
            },

            methods: {
                initCategories() {
                    try {
                        const stored = localStorage.getItem('categories');
                        if (stored) {
                            this.categories = JSON.parse(stored);
                            this.isLoading = false;
                            return;
                        }
                    } catch (e) {}
                    this.getCategories();
                },

                getCategories() {
                    this.$axios
                        .get('{{ route('shop.api.categories.tree') }}')
                        .then(response => {
                            this.isLoading = false;
                            this.categories = response.data.data;
                            localStorage.setItem('categories', JSON.stringify(this.categories));
                        })
                        .catch(error => console.log(error));
                },

                pairCategoryChildren(category) {
                    if (!category.children) return [];
                    return category.children.reduce((result, value, index, array) => {
                        if (index % 2 === 0) result.push(array.slice(index, index + 2));
                        return result;
                    }, []);
                },

                toggleCategoryDrawer() {
                    this.isDrawerActive = !this.isDrawerActive;
                    if (this.isDrawerActive) this.currentViewLevel = 'main';
                },

                onDrawerToggle(event) {
                    this.isDrawerActive = event.isActive;
                },

                onDrawerClose() {
                    this.isDrawerActive = false;
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
    </script>

    <script type="text/x-template" id="v-locale-switcher-template">
        <div class="my-2.5 grid gap-1 overflow-auto max-md:my-0 sm:max-h-[500px]">
            <span
                class="flex cursor-pointer items-center gap-2.5 px-5 py-2 text-sm text-fashion-navy hover:bg-fashion-surface transition-colors"
                :class="{'bg-fashion-surface font-semibold': locale.code == '{{ app()->getLocale() }}'}"
                v-for="locale in locales"
                @click="change(locale)"                  
            >
                <img
                    :src="locale.logo_url || '{{ bagisto_asset('images/default-language.svg') }}'"
                    width="24"
                    height="16"
                    class="rounded-sm object-cover"
                />
                @{{ locale.name }}
            </span>
        </div>
    </script>

    <script type="module">
        app.component('v-locale-switcher', {
            template: '#v-locale-switcher-template',
            data() {
                return {
                    locales: @json(core()->getCurrentChannel()->locales()->orderBy('name')->get()),
                };
            },
            methods: {
                change(locale) {
                    let url = new URL(window.location.href);
                    url.searchParams.set('locale', locale.code);
                    window.location.href = url.href;
                }
            }
        });
    </script>
@endPushOnce

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.after') !!}