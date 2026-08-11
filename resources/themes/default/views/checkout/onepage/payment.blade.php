{!! view_render_event('bagisto.shop.checkout.onepage.payment_methods.before') !!}

<v-payment-methods
    :methods="paymentMethods"
    @payment-method-selected="setSelectedPaymentMethod"
    @processing="stepForward"
    @processed="stepProcessed"
>
    <x-shop::shimmer.checkout.onepage.payment-method />
</v-payment-methods>

{!! view_render_event('bagisto.shop.checkout.onepage.payment_methods.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-payment-methods-template"
    >
        <div class="mb-7 max-md:last:!mb-0">
            <template v-if="! methods">
                <!-- Payment Method shimmer Effect -->
                <x-shop::shimmer.checkout.onepage.payment-method />
            </template>
    
            <template v-else>
                {!! view_render_event('bagisto.shop.checkout.onepage.payment_method.accordion.before') !!}

                <!-- Accordion Blade Component -->
                <x-shop::accordion class="mb-6 overflow-hidden max-md:mb-0 max-md:mt-0 bg-transparent">
                    <!-- Accordion Header Component Slot -->
                    <x-slot:header class="!p-0 max-md:!mb-0 max-md:!p-3 max-md:text-sm max-md:font-medium max-sm:!p-2 bg-transparent">
                        <div class="flex items-center justify-between pb-4 border-b-2 border-transparent">
                            <h2 class="text-xl font-serif text-fashion-navy uppercase tracking-widest max-md:text-base">
                                3. PAYMENT METHOD
                            </h2>
                        </div>
                    </x-slot>
    
                    <!-- Accordion Blade Component Content -->
                    <x-slot:content class="mt-8 !p-0 max-md:mt-0 max-md:rounded-t-none max-md:border max-md:border-t-0 max-md:!p-4">
                        <div class="flex flex-col gap-4">
                            <div 
                                class="relative cursor-pointer select-none flex items-center gap-4"
                                v-for="(payment, index) in methods"
                            >
                                {!! view_render_event('bagisto.shop.checkout.payment-method.before') !!}

                                <div class="mt-1">
                                    <input 
                                        type="radio" 
                                        name="payment[method]" 
                                        :value="payment.payment"
                                        :id="payment.method"
                                        class="peer hidden"
                                        @change="store(payment)"
                                    >
        
                                    <label 
                                        :for="payment.method" 
                                        class="icon-radio-unselect peer-checked:icon-radio-select cursor-pointer text-2xl text-navyBlue"
                                    >
                                    </label>
                                </div>

                                <label 
                                    :for="payment.method" 
                                    class="cursor-pointer flex items-center gap-4"
                                >
                                    {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.image.before') !!}

                                    {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.image.after') !!}

                                    <div>
                                        {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.title.before') !!}

                                        <p class="text-base font-semibold text-zinc-900">
                                            @{{ payment.method_title }}
                                        </p>
                                        
                                        {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.title.after') !!}

                                        {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.description.before') !!}

                                        <p class="text-sm font-normal text-zinc-600 mt-1">
                                            @{{ payment.description }}
                                        </p> 

                                        {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.description.after') !!}
    
                                    </div>
                                </label>

                                {!! view_render_event('bagisto.shop.checkout.payment-method.after') !!}

                                <!-- Duitku Channels Sub-selection -->
                                <div v-if="payment.method === 'duitku' && selectedDuitkuMain" class="ml-10 mt-4 flex flex-col gap-3 pb-2 w-full">
                                    <div v-if="isDuitkuLoading" class="text-sm text-zinc-500 italic">
                                        Loading available payment methods...
                                    </div>
                                    <div v-else v-for="dMethod in duitkuMethods" :key="dMethod.paymentMethod" class="flex items-center gap-3">
                                        <input 
                                            type="radio" 
                                            :id="dMethod.paymentMethod" 
                                            name="duitku_sub_method" 
                                            :value="dMethod.paymentMethod" 
                                            class="peer hidden"
                                            @change="storeDuitku(payment, dMethod.paymentMethod)"
                                        >
                                        <label 
                                            :for="dMethod.paymentMethod" 
                                            class="icon-radio-unselect peer-checked:icon-radio-select cursor-pointer text-xl text-navyBlue"
                                        ></label>
                                        <label :for="dMethod.paymentMethod" class="cursor-pointer flex items-center gap-3 w-full">
                                            <img v-if="dMethod.paymentImage" :src="dMethod.paymentImage" class="h-6 object-contain" :alt="dMethod.paymentMethodName">
                                            <span class="text-sm font-medium text-zinc-800">@{{ dMethod.paymentMethodName }}</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Todo implement the additionalDetails -->
                                {{-- \Webkul\Payment\Payment::getAdditionalDetails($payment['method'] --}}
                            </div>
                        </div>
                    </x-slot>
                </x-shop::accordion>

                {!! view_render_event('bagisto.shop.checkout.onepage.payment_method.accordion.after') !!}
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-payment-methods', {
            template: '#v-payment-methods-template',

            props: {
                methods: {
                    type: Object,
                    required: true,
                    default: () => null,
                },
            },

            emits: ['payment-method-selected', 'processing', 'processed'],

            data() {
                return {
                    duitkuMethods: [],
                    isDuitkuLoading: false,
                    selectedDuitkuMain: false,
                };
            },

            methods: {
                loadDuitku() {
                    this.selectedDuitkuMain = true;
                    if (this.duitkuMethods.length === 0) {
                        this.isDuitkuLoading = true;
                        this.$axios.get("{{ route('duitku.payment_methods') }}")
                            .then(res => {
                                this.duitkuMethods = res.data;
                                this.isDuitkuLoading = false;
                            })
                            .catch(err => {
                                this.isDuitkuLoading = false;
                            });
                    }
                },
                storeDuitku(payment, duitkuMethodCode) {
                    this.$emit('processing', 'review');
                    this.$axios.post("{{ route('duitku.set_method') }}", { method: duitkuMethodCode })
                        .then(() => {
                            this.store(payment, true);
                        })
                        .catch(err => {
                            this.$emit('processing', 'payment');
                            console.error(err);
                        });
                },
                store(selectedMethod, skipDuitkuCheck = false) {
                    if (selectedMethod.method === 'duitku' && !skipDuitkuCheck) {
                        this.loadDuitku();
                        return;
                    }

                    this.$emit('payment-method-selected', selectedMethod.method);

                    this.$emit('processing', 'review');

                    this.$axios.post("{{ route('shop.checkout.onepage.payment_methods.store') }}", {
                            payment: selectedMethod
                        })
                        .then(response => {
                            this.$emit('processed', response.data.cart);

                            // Used in mobile view. 
                            if (window.innerWidth <= 768) {
                                window.scrollTo({
                                    top: document.body.scrollHeight,
                                    behavior: 'smooth'
                                });
                            }
                        })
                        .catch(error => {
                            this.$emit('processing', 'payment');

                            if (error.response.data.redirect_url) {
                                window.location.href = error.response.data.redirect_url;
                            }
                        });
                },
            },
        });
    </script>
@endPushOnce
