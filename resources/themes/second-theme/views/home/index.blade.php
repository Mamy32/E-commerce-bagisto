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
            class="relative overflow-hidden bg-white"
            aria-label="Homepage hero"
        >
            <div class="relative mx-auto flex max-w-[1440px] flex-col items-center justify-center px-6 py-32 text-center max-lg:py-24 max-sm:py-16">

                <p class="mb-6 text-xs font-semibold uppercase tracking-[0.2em] text-[#25160e]">
                    {{ config('app.name') }}
                </p>

                <h1 class="font-sans text-5xl leading-[1.1] text-[#25160e] max-lg:text-4xl max-md:text-3xl max-sm:text-2xl">
                    Crafted for the<br>
                    <span class="font-light">discerning few</span>
                </h1>

                <p class="mt-8 max-w-[480px] text-base leading-relaxed text-[#666666] max-sm:text-sm">
                    Discover our curated collection of premium pieces, designed with intention and built to last.
                </p>

                <div class="mt-12 flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                    <a
                        href="{{ route('shop.search.index') }}"
                        class="primary-button"
                    >
                        @lang('shop::app.home.index.shop-now')
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
                        title="" 
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
                        title="" 
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