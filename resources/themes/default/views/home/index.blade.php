@php
    $channel = core()->getCurrentChannel();
    $hasImageCarousel = $customizations->contains('type', \Webkul\Theme\Models\ThemeCustomization::IMAGE_CAROUSEL);
    
    $heroCustomization = $customizations->firstWhere('type', \Webkul\Theme\Models\ThemeCustomization::IMAGE_CAROUSEL);
    $heroImages = $heroCustomization ? ($heroCustomization->options['images'] ?? []) : [];
    $heroSlide = $heroImages[0] ?? null;
    
    // If the admin uploads an image, we use it. Otherwise fallback to our cinematic image.
    $heroImage = $heroSlide && !empty($heroSlide['image']) ? asset($heroSlide['image']) : asset('storage/theme/hero-cinematic.jpg');
    
    // Use the title from admin if provided, otherwise fallback to default
    $heroTitle = $heroSlide && !empty($heroSlide['title']) ? $heroSlide['title'] : __('The Fall/Winter<br><em class="not-italic text-fashion-accent">Collection</em>');
    
    // Use the link from admin if provided
    $heroLink = $heroSlide && !empty($heroSlide['link']) ? $heroSlide['link'] : route('shop.home.index') . '#collections';
@endphp

@push('meta')
    <meta name="title" content="{{ $channel->home_seo['meta_title'] ?? '' }}" />
    <meta name="description" content="{{ $channel->home_seo['meta_description'] ?? '' }}" />
    <meta name="keywords" content="{{ $channel->home_seo['meta_keywords'] ?? '' }}" />
    <link rel="preload" as="image" href="{{ $heroImage }}">
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

    <section
        class="relative flex h-[100vh] max-md:h-[80vh] min-h-[600px] max-md:min-h-[400px] w-full items-center justify-start overflow-hidden bg-[#1a1a1a] bg-cover bg-center bg-no-repeat"
        style="background-image: url('{{ $heroImage }}');"
        aria-label="Homepage hero"
    >
        <!-- Dark gradient overlay to make text pop -->
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>

        <div class="relative z-10 mx-auto flex w-full max-w-[1440px] flex-col items-start justify-center px-[60px] max-lg:px-8 max-sm:px-4 max-md:-mt-20">
            <p class="mb-4 text-xs font-semibold uppercase tracking-[0.2em] text-fashion-accent">
                {{ config('app.name') }}
            </p>

            <h1 class="font-dmserif text-7xl italic leading-[1.1] text-white max-lg:text-6xl max-md:text-5xl max-sm:text-4xl">
                {!! $heroTitle !!}
            </h1>

            <p class="mt-6 max-w-[500px] text-lg leading-relaxed text-white/80 max-sm:text-base">
                {{ __('Discover our curated selection of premium pieces. Designed with intention, tailored to perfection, and built for the modern individual.') }}
            </p>

            <div class="mt-10 flex flex-wrap gap-4">
                <a
                    href="{{ $heroLink }}"
                    class="rounded-xl bg-fashion-accent px-10 py-4 text-sm font-bold uppercase tracking-wide text-fashion-navy shadow-lg transition-transform hover:scale-105"
                >
                    {{ __('Discover Now') }}
                </a>
                
                <a
                    href="{{ route('shop.search.index') }}"
                    class="rounded-xl border border-white/40 px-10 py-4 text-sm font-bold uppercase tracking-wide text-white backdrop-blur-sm transition-colors hover:border-white hover:bg-white/10"
                >
                    {{ __('View All Categories') }}
                </a>
            </div>
        </div>
    </section>

    <div id="collections">
        @foreach ($customizations as $customization)
            @php ($data = $customization->options) @endphp

            @switch ($customization->type)

                @case ($customization::IMAGE_CAROUSEL)
                    {{-- The default image carousel is disabled in favor of our custom cinematic hero banner at the top --}}
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
                    ['icon' => 'icon-packaging', 'title' => __('Free Shipping'),   'body' => __('On all orders over a qualifying amount')],
                    ['icon' => 'icon-return',    'title' => __('Easy Returns'),    'body' => __('30-day hassle-free return policy')],
                    ['icon' => 'icon-security',  'title' => __('Secure Payment'),  'body' => __('Your data is protected, always')],
                    ['icon' => 'icon-support',   'title' => __('Expert Support'),  'body' => __('Dedicated team ready to help')],
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