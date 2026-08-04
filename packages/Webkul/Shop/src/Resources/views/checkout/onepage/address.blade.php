{!! view_render_event('bagisto.shop.checkout.onepage.address.before') !!}

<!-- Accordion Blade Component -->
<x-shop::accordion class="mb-6 overflow-hidden max-md:mb-0 max-md:mt-0 bg-transparent">
    <!-- Accordion Header Component Slot -->
    <x-slot:header class="!p-0 max-md:!mb-0 max-md:!p-3 max-md:text-sm max-md:font-medium max-sm:!p-2 bg-transparent">
        <div class="flex items-center justify-between pb-4 border-b-2 border-transparent">
            <h2 class="text-xl font-serif text-fashion-navy uppercase tracking-widest max-md:text-base">
                1. SHIPPING ADDRESS
            </h2>
        </div>
    </x-slot>

    <!-- Accordion Content Component Slot -->
    <x-slot:content class="mt-8 !p-0 max-md:mt-0 max-md:rounded-t-none max-md:border max-md:border-t-0 max-md:!p-4">
        <!-- If the customer is guest -->
        <template v-if="cart.is_guest">
            @include('shop::checkout.onepage.address.guest')
        </template>

        <!-- If the customer is logged in -->
        <template v-else>
            @include('shop::checkout.onepage.address.customer')
        </template>
    </x-slot:content>
</x-shop::accordion>

{!! view_render_event('bagisto.shop.checkout.onepage.address.after') !!}