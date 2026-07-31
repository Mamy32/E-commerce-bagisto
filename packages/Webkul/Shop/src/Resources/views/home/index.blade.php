@php
    $channel = core()->getCurrentChannel();
@endphp

<!-- SEO Meta Content -->
@push ('meta')
    <meta name="title" content="{{ $channel->home_seo['meta_title'] ?? 'Fashion Store' }}" />
    <meta name="description" content="{{ $channel->home_seo['meta_description'] ?? '' }}" />
    <meta name="keywords" content="{{ $channel->home_seo['meta_keywords'] ?? '' }}" />
@endPush

<x-shop::layouts>
    <x-slot:title>
        {{ $channel->home_seo['meta_title'] ?? 'Fashion Store' }}
    </x-slot>

    <!-- HERO SECTION -->
    <section class="container mx-auto px-6 py-12 md:py-24">
        <div class="flex flex-col md:flex-row items-center gap-12">
            <!-- Left Text Content -->
            <div class="w-full md:w-1/2">
                <h1 class="font-dmserif text-5xl md:text-7xl leading-tight text-fashion-navy mb-6">
                    Find Your<br>
                    <span class="text-fashion-accent">Fashion</span> Here
                </h1>
                <p class="text-gray-500 mb-8 max-w-md text-sm md:text-base leading-relaxed">
                    Fashion is not something that exists in dresses only. Fashion is in the sky, in the street, fashion has to do with ideas, the way we live, what is happening.
                </p>
                <div class="flex items-center gap-6 mb-12">
                    <a href="{{ route('shop.search.index') }}" class="bg-[#A68868] hover:bg-[#8F7254] text-white px-8 py-3 text-sm font-semibold uppercase tracking-wider transition-colors duration-300">
                        Buy Now
                    </a>
                    <a href="{{ route('shop.search.index') }}" class="text-fashion-navy font-semibold text-sm uppercase tracking-wider hover:text-fashion-accent transition-colors duration-300">
                        Go To Shop
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="flex items-center gap-12 border-t border-gray-200 pt-8">
                    <div>
                        <div class="font-dmserif text-3xl text-fashion-navy mb-1">9k+</div>
                        <div class="text-xs text-gray-500 uppercase tracking-widest">Unique Style</div>
                    </div>
                    <div>
                        <div class="font-dmserif text-3xl text-fashion-navy mb-1">98k+</div>
                        <div class="text-xs text-gray-500 uppercase tracking-widest">Users</div>
                    </div>
                    <div>
                        <div class="font-dmserif text-3xl text-fashion-navy mb-1">2k+</div>
                        <div class="text-xs text-gray-500 uppercase tracking-widest">Stores Vendor</div>
                    </div>
                </div>
            </div>
            
            <!-- Right Hero Image -->
            <div class="w-full md:w-1/2 relative">
                <div class="absolute right-0 top-0 bottom-0 w-4/5 bg-fashion-surface -z-10"></div>
                <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=800&q=80" alt="Fashion Model" class="w-full h-auto object-cover max-w-md mx-auto relative z-10 shadow-2xl border border-gray-100">
                <div class="absolute -right-8 top-1/2 transform -translate-y-1/2 rotate-90 hidden lg:block">
                    <span class="font-dmserif text-6xl text-gray-200 opacity-50 tracking-widest whitespace-nowrap">easy going</span>
                </div>
            </div>
        </div>
    </section>

    <!-- FIND BY CATEGORY SECTION -->
    <section class="container mx-auto px-6 py-16 md:py-24 text-center">
        <h2 class="font-dmserif text-4xl text-fashion-navy mb-8 uppercase tracking-widest">Find By Category</h2>
        
        <div class="flex justify-center gap-8 mb-12 border-b border-gray-200 pb-4">
            <a href="#" class="text-sm font-medium uppercase tracking-widest text-gray-400 hover:text-fashion-navy transition-colors">Men</a>
            <a href="#" class="text-sm font-medium uppercase tracking-widest text-fashion-navy border-b-2 border-fashion-navy pb-4 -mb-[18px]">Women</a>
            <a href="#" class="text-sm font-medium uppercase tracking-widest text-gray-400 hover:text-fashion-navy transition-colors">Kids</a>
            <a href="#" class="text-sm font-medium uppercase tracking-widest text-gray-400 hover:text-fashion-navy transition-colors">Collections</a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- Item 1 -->
            <div class="text-left group cursor-pointer">
                <div class="overflow-hidden mb-4 bg-gray-100 relative">
                    <img src="https://images.unsplash.com/photo-1515347619362-673471eba6ce?auto=format&fit=crop&w=600&q=80" alt="Dress" class="w-full h-96 object-cover transform group-hover:scale-105 transition-transform duration-700">
                </div>
                <h3 class="font-medium text-sm uppercase tracking-wide mb-1">Slip Spaghetti Strap Dress</h3>
                <p class="text-fashion-accent font-semibold">$38.00</p>
            </div>
            <!-- Item 2 -->
            <div class="text-left group cursor-pointer">
                <div class="overflow-hidden mb-4 bg-gray-100 relative">
                    <img src="https://images.unsplash.com/photo-1529139574466-a303027c028b?auto=format&fit=crop&w=600&q=80" alt="Pants" class="w-full h-96 object-cover transform group-hover:scale-105 transition-transform duration-700">
                </div>
                <h3 class="font-medium text-sm uppercase tracking-wide mb-1">Black Check Jetsetters</h3>
                <p class="text-fashion-accent font-semibold">$82.00</p>
            </div>
            <!-- Item 3 -->
            <div class="text-left group cursor-pointer">
                <div class="overflow-hidden mb-4 bg-gray-100 relative">
                    <img src="https://images.unsplash.com/photo-1527719327859-c6ce80353573?auto=format&fit=crop&w=600&q=80" alt="T-Shirt" class="w-full h-96 object-cover transform group-hover:scale-105 transition-transform duration-700">
                </div>
                <h3 class="font-medium text-sm uppercase tracking-wide mb-1">Abstract Face Comfort T-Shirt</h3>
                <p class="text-fashion-accent font-semibold">$15.00</p>
            </div>
            <!-- Item 4 -->
            <div class="text-left group cursor-pointer">
                <div class="overflow-hidden mb-4 bg-gray-100 relative">
                    <img src="https://images.unsplash.com/photo-1502716119720-b23a93e5fe1b?auto=format&fit=crop&w=600&q=80" alt="Shorts" class="w-full h-96 object-cover transform group-hover:scale-105 transition-transform duration-700">
                </div>
                <h3 class="font-medium text-sm uppercase tracking-wide mb-1">Paperbag Shorts Brown</h3>
                <p class="text-fashion-accent font-semibold">$25.00</p>
            </div>
        </div>
        
        <a href="{{ route('shop.search.index') }}" class="inline-block bg-fashion-navy hover:bg-gray-800 text-white px-8 py-3 text-sm font-semibold uppercase tracking-widest transition-colors duration-300">
            View All Women Dress
        </a>
    </section>

    <!-- FULL WIDTH PARALLAX BANNER -->
    <section class="w-full h-[600px] relative mt-16 bg-black flex items-center justify-start overflow-hidden">
        <div class="absolute inset-0 bg-black/40 z-10"></div>
        <img src="https://images.unsplash.com/photo-1469334031218-e382a71b716b?auto=format&fit=crop&w=1920&q=80" alt="Fashion Banner" class="absolute inset-0 w-full h-full object-cover opacity-80 mix-blend-overlay">
        
        <div class="container mx-auto px-6 relative z-20">
            <div class="border border-white/30 p-12 md:p-20 inline-block bg-black/20 backdrop-blur-sm">
                <h2 class="font-dmserif text-4xl md:text-6xl text-white leading-tight uppercase tracking-widest">
                    STYLE.<br>
                    TECHNICAL.<br>
                    INNOVATIVE.
                </h2>
            </div>
        </div>
    </section>

    <!-- FEATURED COLLECTION (BAGISTO NATIVE CAROUSEL) -->
    <section class="container mx-auto px-6 py-16 md:py-24">
        <div class="text-center mb-12">
            <h2 class="font-dmserif text-4xl text-fashion-navy uppercase tracking-widest">Featured Collection</h2>
        </div>
        
        <!-- We dynamically call Bagisto's new products api endpoint -->
        <x-shop::products.carousel
            :title="''"
            :src="route('shop.api.products.index', ['new' => 1])"
            :navigation-link="route('shop.search.index', ['new' => 1])"
            aria-label="Featured Collection"
        />
    </section>
</x-shop::layouts>
