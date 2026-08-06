<v-categories-carousel
    src="{{ $src }}"
    title="{{ $title }}"
    navigation-link="{{ $navigationLink ?? '' }}"
>
    <x-shop::shimmer.categories.carousel
        :count="8"
        :navigation-link="$navigationLink ?? false"
    />
</v-categories-carousel>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-categories-carousel-template"
    >
        <div
            class="mx-auto max-w-[1440px] px-[60px] mt-10 max-md:px-8 max-md:mt-7 max-sm:mt-5 max-sm:px-4"
            v-if="! isLoading && categories?.length"
        >
            <div class="relative">
                <div
                    ref="swiperContainer"
                    class="scrollbar-hide flex gap-10 overflow-auto scroll-smooth max-lg:gap-6"
                >
                    <div
                        class="grid min-w-[150px] max-w-[150px] grid-cols-1 justify-items-center gap-6 font-medium max-md:min-w-[120px] max-md:max-w-[120px] max-md:gap-4 max-sm:min-w-[90px] max-sm:max-w-[90px] max-sm:gap-3 group"
                        v-for="category in categories"
                    >
                        <a
                            :href="category.slug"
                            class="h-[150px] w-[150px] overflow-hidden rounded-full bg-[#f8f5f0] border-2 border-transparent transition-all duration-300 group-hover:border-fashion-accent max-md:h-[120px] max-md:w-[120px] max-sm:h-[90px] max-sm:w-[90px] shadow-sm relative flex items-center justify-center"
                            :aria-label="category.name"
                        >
                            <x-shop::media.images.lazy
                                ::src="category.logo?.original_image_url || category.logo?.large_image_url || fallback"
                                width="150"
                                height="150"
                                class="w-full h-full object-cover rounded-full transform group-hover:scale-110 transition-transform duration-500"
                                ::alt="category.name"
                            />
                        </a>

                        <a
                            :href="category.slug"
                            class=""
                        >
                            <p
                                class="text-center text-sm font-semibold uppercase tracking-widest text-fashion-navy transition-colors duration-300 group-hover:text-fashion-accent"
                                v-text="category.name"
                            >
                            </p>
                        </a>
                    </div>
                </div>

                <span
                    class="icon-arrow-left-stylish absolute -left-12 top-[50px] flex h-[50px] w-[50px] cursor-pointer items-center justify-center rounded-full border border-fashion-border bg-white text-2xl text-fashion-navy shadow-sm transition hover:bg-fashion-navy hover:text-white hover:border-fashion-navy max-lg:-left-4 max-md:hidden"
                    role="button"
                    aria-label="@lang('shop::components.carousel.previous')"
                    tabindex="0"
                    @click="swipeLeft"
                >
                </span>

                <span
                    class="icon-arrow-right-stylish absolute -right-12 top-[50px] flex h-[50px] w-[50px] cursor-pointer items-center justify-center rounded-full border border-fashion-border bg-white text-2xl text-fashion-navy shadow-sm transition hover:bg-fashion-navy hover:text-white hover:border-fashion-navy max-lg:-right-4 max-md:hidden"
                    role="button"
                    aria-label="@lang('shop::components.carousel.next')"
                    tabindex="0"
                    @click="swipeRight"
                >
                </span>
            </div>
        </div>

        <!-- Category Carousel Shimmer -->
        <template v-if="isLoading">
            <x-shop::shimmer.categories.carousel
                :count="8"
                :navigation-link="$navigationLink ?? false"
            />
        </template>
    </script>

    <script type="module">
        app.component('v-categories-carousel', {
            template: '#v-categories-carousel-template',

            props: [
                'src',
                'title',
                'navigationLink',
            ],

            data() {
                return {
                    isLoading: true,

                    categories: [],

                    offset: 323,

                    fallback: "{{ bagisto_asset('images/small-product-placeholder.webp') }}"
                };
            },

            mounted() {
                this.getCategories();
            },

            methods: {
                getCategories() {
                    this.$axios.get(this.src)
                        .then(response => {
                            this.isLoading = false;

                            this.categories = response.data.data;
                        }).catch(error => {
                            console.log(error);
                        });
                },

                swipeLeft() {
                    const container = this.$refs.swiperContainer;

                    container.scrollLeft -= this.offset;
                },

                swipeRight() {
                    const container = this.$refs.swiperContainer;

                    container.scrollLeft += this.offset;
                },
            },
        });
    </script>
@endPushOnce
