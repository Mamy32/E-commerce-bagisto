{!! view_render_event('bagisto.shop.layout.features.before') !!}

<!--
    The ThemeCustomizationRepository repository is injected directly here because there is no way
    to retrieve it from the view composer, as this is an anonymous component.
-->
@inject('themeCustomizationRepository', 'Webkul\Theme\Repositories\ThemeCustomizationRepository')

@php
    $channel = core()->getCurrentChannel();

    $customization = $themeCustomizationRepository->findOneWhere([
        'type'       => 'services_content',
        'status'     => 1,
        'theme_code' => $channel->theme,
        'channel_id' => $channel->id,
    ]); 
@endphp

<!-- Features -->
@if ($customization)
    <div
        class="mx-auto mt-20 max-w-[1440px] px-[60px] pb-12 max-lg:px-8 max-md:mt-10 max-md:px-4"
        v-pre
    >
        <div class="grid grid-cols-4 gap-8 max-lg:grid-cols-2 max-md:grid-cols-1">
            @foreach ($customization->options['services'] as $service)
                <div class="flex items-center gap-5 bg-white p-6 rounded-xl shadow-sm border border-[#e9decc] transition-all hover:shadow-md max-sm:p-4">
                    <span
                        class="{{ $service['service_icon'] }} flex shrink-0 items-center justify-center w-[60px] h-[60px] bg-[#F1EADF] border border-fashion-navy rounded-full text-3xl text-fashion-navy p-2.5 max-sm:w-12 max-sm:h-12 max-sm:text-xl"
                        role="presentation"
                    >
                    </span>

                    <div>
                        <!-- Service Title -->
                        <p class="font-dmserif text-lg font-medium text-fashion-navy max-sm:text-base">
                            {{ $service['title'] }}
                        </p>

                        <!-- Service Description -->
                        <p class="mt-1 text-sm font-medium text-zinc-500 leading-snug max-sm:text-xs">
                            {{ $service['description'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

{!! view_render_event('bagisto.shop.layout.features.after') !!}