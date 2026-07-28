@php
    $channel = core()->getCurrentChannel();
    $hasImageCarousel = $customizations->contains('type', \Webkul\Theme\Models\ThemeCustomization::IMAGE_CAROUSEL);
@endphp

@push('meta')
    <meta name="title" content="{{ $channel->home_seo['meta_title'] ?? '' }}" />
    <meta name="description" content="{{ $channel->home_seo['meta_description'] ?? '' }}" />
    <meta name="keywords" content="{{ $channel->home_seo['meta_keywords'] ?? '' }}" />
@endPush

@push('scripts')
    @if (! empty($categories))
        <script>
            localStorage.setItem('categories', JSON.stringify(@json($categories)));
        </script>
    @endif
@endpush

<x-shop::layouts>

    <x-slot:title>
        {{ $channel->home_seo['meta_title'] ?? config('app.name') }}
    </x-slot>

    @unless ($hasImageCarousel)
        <section
            class="relative overflow-hidden bg-fashion-navy"
            aria-label="Homepage hero"
        >
            <div
                class="pointer-events-none absolute inset-0 opacity-5"
                style="background-image: repeating-linear-gradient(45deg, #C9A84C 0px, #C9A84C 1px, transparent 1px, transparent 50px);"
                aria-hidden="true"
            ></div>

            <div class="relative mx-auto flex max-w-[1440px] flex-col items-center justify-center px-6 py-28 text-center max-lg:py-20 max-sm:py-14">

                <p class="mb-4 text-xs font-semibold uppercase tracking-[0.2em] text-fashion-accent">
                    {{ config('app.name') }}
                </p>

                <h1 class="font-dmserif text-6xl italic leading-[1.1] text-white max-lg:text-5xl max-md:text-4xl max-sm:text-3xl">
                    Crafted for the<br>
                    <em class="not-italic text-fashion-accent">discerning few</em>
                </h1>

                <p class="mt-6 max-w-[480px] text-base leading-relaxed text-white/70 max-sm:text-sm">
                    Discover our curated collection of premium pieces, designed with intention and built to last.
                </p>

                <div class="mt-10 flex flex-wrap justify-center gap-4">
                    <a
                        href="{{ route('shop.home.index') }}#collections"
                        class="rounded-lg bg-fashion-accent px-8 py-3.5 text-sm font-semibold uppercase tracking-wide text-fashion-navy transition-opacity hover:opacity-80"
                    >
                        Shop Collection
                    </a>

                    <a
                        href="{{ route('shop.home.index') }}"
                        class="rounded-lg border border-white/30 px-8 py-3.5 text-sm font-semibold uppercase tracking-wide text-white transition-colors hover:border-white/60 hover:bg-white/10"
                    >
                        Explore More
                    </a>
                </div>
            </div>
        </section>
    @endunless

    <div id="collections">
        @foreach ($customizations as $customization)
            @php ($data = $customization->options) @endphp

            @switch ($customization->type)

                @case ($customization::IMAGE_CAROUSEL)
                    <x-shop::carousel
                        :options="$data"
                        aria-label="{{ trans('shop::app.home.index.image-carousel') }}"
                    />
                    @break

                @case ($customization::STATIC_CONTENT)
                    @if (! empty($data['css']))
                        @push('styles')
                            <style>{!! $data['css'] !!}</style>
                        @endpush
                    @endif

                    @if (! empty($data['html']))
                        <div class="mx-auto max-w-[1440px] px-[60px] max-md:px-8 max-sm:px-4">
                            {!! $data['html'] !!}
                        </div>
                    @endif
                    @break

                @case ($customization::CATEGORY_CAROUSEL)
                    @if (! empty($data['title']))
                        <div class="mx-auto mt-16 max-w-[1440px] px-[60px] max-md:mt-10 max-md:px-8 max-sm:mt-8 max-sm:px-4">
                            <h2 class="font-dmserif text-3xl italic text-fashion-navy max-sm:text-2xl">
                                {{ $data['title'] }}
                            </h2>
                            <div class="mt-2 h-px w-12 bg-fashion-accent"></div>
                        </div>
                    @endif

                    <x-shop::categories.carousel
                        :title=""
                        :src="route('shop.api.categories.index', $data['filters'] ?? [])"
                        :navigation-link="route('shop.home.index')"
                        aria-label="{{ trans('shop::app.home.index.categories-carousel') }}"
                    />
                    @break

                @case ($customization::PRODUCT_CAROUSEL)
                    @if (! empty($data['title']))
                        <div class="mx-auto mt-16 max-w-[1440px] px-[60px] max-md:mt-10 max-md:px-8 max-sm:mt-8 max-sm:px-4">
                            <h2 class="font-dmserif text-3xl italic text-fashion-navy max-sm:text-2xl">
                                {{ $data['title'] }}
                            </h2>
                            <div class="mt-2 h-px w-12 bg-fashion-accent"></div>
                        </div>
                    @endif

                    <x-shop::products.carousel
                        :title=""
                        :src="route('shop.api.products.index', $data['filters'] ?? [])"
                        :navigation-link="route('shop.search.index', $data['filters'] ?? [])"
                        aria-label="{{ trans('shop::app.home.index.product-carousel') }}"
                    />
                    @break

            @endswitch
        @endforeach
    </div>

    @if ($customizations->isEmpty())
        <section
            class="mx-auto mt-20 max-w-[1440px] px-[60px] pb-12 max-md:mt-12 max-md:px-8 max-sm:px-4"
            aria-label="Brand values"
        >
            <div class="grid grid-cols-4 gap-6 max-lg:grid-cols-2 max-sm:grid-cols-1">
                @foreach ([
                    ['icon' => 'icon-packaging', 'title' => 'Free Shipping',   'body' => 'On all orders over a qualifying amount'],
                    ['icon' => 'icon-return',    'title' => 'Easy Returns',    'body' => '30-day hassle-free return policy'],
                    ['icon' => 'icon-security',  'title' => 'Secure Payment',  'body' => 'Your data is protected, always'],
                    ['icon' => 'icon-support',   'title' => 'Expert Support',  'body' => 'Dedicated team ready to help'],
                ] as $feature)
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full border border-fashion-border text-2xl text-fashion-navy">
                            <span class="{{ $feature['icon'] }}" aria-hidden="true"></span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-fashion-navy">{{ $feature['title'] }}</p>
                            <p class="mt-0.5 text-xs text-fashion-muted">{{ $feature['body'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

</x-shop::layouts>