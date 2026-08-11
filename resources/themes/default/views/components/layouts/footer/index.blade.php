{!! view_render_event('bagisto.shop.layout.footer.before') !!}

@inject('themeCustomizationRepository', 'Webkul\Theme\Repositories\ThemeCustomizationRepository')

@php
    $channel = core()->getCurrentChannel();

    $footerLinksCustomization = $themeCustomizationRepository->findOneWhere([
        'type'       => 'footer_links',
        'status'     => 1,
        'theme_code' => $channel->theme,
        'channel_id' => $channel->id,
    ]);
@endphp

<footer
    class="mt-16 bg-[--color-footer-bg] max-sm:mt-10"
    aria-label="Site footer"
>

    <div class="px-[60px] py-14 max-1060:flex-col max-md:px-8 max-sm:px-4 max-sm:py-8">

        <div class="flex justify-between w-full max-1060:flex-col max-lg:gap-8">

            {{-- Brand column --}}
            <div class="flex max-w-[280px] flex-col gap-5 max-1060:max-w-full max-1060:flex-row max-1060:flex-wrap max-1060:items-start max-sm:flex-col">

                <a
                    href="{{ route('shop.home.index') }}"
                    aria-label="{{ config('app.name') }}"
                >
                    <img
                        src="{{ $channel->logo_url ?? bagisto_asset('images/logo.svg') }}"
                        alt="{{ config('app.name') }}"
                        width="120"
                        height="28"
                        class="max-h-[32px] w-auto object-contain"
                    >
                </a>

                <p class="max-w-[220px] text-sm leading-relaxed text-fashion-muted max-1060:max-w-full">
                    @lang('shop::app.components.layouts.footer.subscribe-stay-touch')
                </p>

                <div class="flex items-center gap-4">
                    <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full border border-fashion-border text-fashion-muted transition-colors hover:border-fashion-navy hover:text-fashion-navy" aria-label="Instagram" rel="noopener noreferrer" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true"><path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm.003 1.44c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.47 2.47 0 0 1-.92-.598 2.47 2.47 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.843-.038 1.097-.046 3.233-.046zm0 2.452a4.108 4.108 0 1 0 0 8.216 4.108 4.108 0 0 0 0-8.216zm0 6.775a2.667 2.667 0 1 1 0-5.334 2.667 2.667 0 0 1 0 5.334zm5.23-6.937a.96.96 0 1 1-1.92 0 .96.96 0 0 1 1.92 0z"/></svg>
                    </a>

                    <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full border border-fashion-border text-fashion-muted transition-colors hover:border-fashion-navy hover:text-fashion-navy" aria-label="Facebook" rel="noopener noreferrer" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true"><path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/></svg>
                    </a>

                    <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full border border-fashion-border text-fashion-muted transition-colors hover:border-fashion-navy hover:text-fashion-navy" aria-label="Pinterest" rel="noopener noreferrer" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true"><path d="M8 0a8 8 0 0 0-2.915 15.452c-.07-.633-.134-1.606.027-2.297.146-.625.984-4.171.984-4.171s-.252-.504-.252-1.25c0-1.17.679-2.046 1.523-2.046.718 0 1.066.539 1.066 1.185 0 .721-.46 1.801-.698 2.804-.198.836.42 1.517 1.242 1.517 1.49 0 2.639-1.57 2.639-3.837 0-2.006-1.443-3.41-3.503-3.41-2.386 0-3.786 1.788-3.786 3.638 0 .72.277 1.49.623 1.91a.25.25 0 0 1 .058.239c-.063.263-.205.836-.233.953-.037.154-.124.187-.286.113-1.05-.488-1.705-2.025-1.705-3.26 0-2.65 1.924-5.082 5.549-5.082 2.913 0 5.177 2.076 5.177 4.85 0 2.893-1.824 5.22-4.358 5.22-.851 0-1.651-.443-1.924-.965l-.522 1.95c-.19.73-.7 1.644-1.04 2.2A8 8 0 1 0 8 0z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Footer link columns --}}
            <div class="flex w-2/3 justify-between max-1060:w-full max-1060:justify-start max-sm:hidden">
                @if ($footerLinksCustomization?->options)
                    @foreach ($footerLinksCustomization->options as $footerLinkSection)
                        @php
                            usort($footerLinkSection, fn ($a, $b) => $a['sort_order'] - $b['sort_order']);
                        @endphp

                        <div class="flex w-1/2 @if($loop->first) justify-center @else justify-end @endif max-1060:justify-start max-1060:w-auto max-1060:mr-8">
                            <ul class="grid gap-3">
                                @foreach ($footerLinkSection as $index => $link)
                                    @if ($index === 0)
                                        <li class="mb-1">
                                            <span class="text-xs font-semibold uppercase tracking-[0.12em] text-fashion-navy">
                                                {{ $link['title'] }}
                                            </span>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{ $link['url'] }}" class="text-sm text-fashion-muted transition-colors hover:text-fashion-navy">
                                                {{ $link['title'] }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                @else
                    <div class="flex w-1/2 justify-center max-1060:justify-start max-1060:w-auto">
                        <ul class="grid gap-3">
                            <li class="mb-1">
                                <span class="text-xs font-semibold uppercase tracking-[0.12em] text-fashion-navy">
                                    @lang('shop::app.components.layouts.footer.footer-content')
                                </span>
                            </li>
                            <li>
                                <a href="{{ route('shop.home.contact_us') }}" class="text-sm text-fashion-muted hover:text-fashion-navy">
                                    @lang('shop::app.components.layouts.footer.contact-us')
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>

            {{-- Mobile accordion --}}
            <x-shop::accordion
                :is-active="false"
                class="hidden !w-full rounded-xl !border !border-fashion-border sm:hidden max-sm:block"
            >
                <x-slot:header class="rounded-t-xl bg-fashion-surface px-4 py-3 text-sm font-semibold text-fashion-navy">
                    @lang('shop::app.components.layouts.footer.footer-content')
                </x-slot>

                <x-slot:content class="flex flex-wrap justify-between gap-6 !bg-transparent !p-4">
                    @if ($footerLinksCustomization?->options)
                        @foreach ($footerLinksCustomization->options as $footerLinkSection)
                            <ul class="grid gap-3">
                                @foreach ($footerLinkSection as $link)
                                    <li>
                                        <a href="{{ $link['url'] }}" class="text-sm text-fashion-muted hover:text-fashion-navy">
                                            {{ $link['title'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                    @endif
                </x-slot>
            </x-shop::accordion>

            {{-- Newsletter --}}
            {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.before') !!}

            @if (core()->getConfigData('customer.settings.newsletter.subscription'))
                <div class="flex flex-col gap-4 max-w-[320px] max-1060:max-w-full">
                    <div>
                        <p
                            class="font-dmserif text-2xl italic leading-snug text-fashion-navy max-sm:text-xl"
                            role="heading"
                            aria-level="2"
                        >
                            @lang('shop::app.components.layouts.footer.newsletter-text')
                        </p>

                        <p class="mt-1 text-sm text-fashion-muted">
                            @lang('shop::app.components.layouts.footer.subscribe-stay-touch')
                        </p>
                    </div>

                    <x-shop::form
                        :action="route('shop.subscription.store')"
                        toolname="subscribe_to_newsletter"
                        tooldescription="{{ trans('shop::app.components.layouts.webmcp.subscribe-newsletter') }}"
                        toolautosubmit
                    >
                        <div class="relative">
                            <x-shop::form.control-group.control
                                type="email"
                                class="block w-full rounded-xl border border-fashion-border bg-white px-4 py-3.5 text-sm placeholder-fashion-muted focus:border-fashion-navy focus:outline-none max-sm:rounded-lg max-sm:py-3 max-sm:text-xs"
                                name="email"
                                rules="required|email"
                                label="Email"
                                :aria-label="trans('shop::app.components.layouts.footer.email')"
                                placeholder="email@example.com"
                                toolparamdescription="{{ trans('shop::app.components.layouts.webmcp.subscribe-newsletter-email') }}"
                            />

                            <x-shop::form.control-group.error control-name="email" />

                            <button
                                type="submit"
                                class="absolute right-2 top-1.5 rounded-lg bg-fashion-navy px-5 py-2 text-xs font-semibold uppercase tracking-wide text-white transition-opacity hover:opacity-80 max-sm:top-1.5 max-sm:px-4 max-sm:py-2 max-sm:text-[10px]"
                            >
                                @lang('shop::app.components.layouts.footer.subscribe')
                            </button>
                        </div>
                    </x-shop::form>
                </div>
            @endif

            {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.after') !!}
        </div>
    </div>

    {{-- Bottom bar --}}
    <div class="flex items-center justify-center border-t border-fashion-border bg-fashion-surface px-[60px] py-4 max-md:flex-col max-md:gap-2 max-md:text-center max-sm:px-4">

        {!! view_render_event('bagisto.shop.layout.footer.footer_text.before') !!}

        <p class="text-xs text-fashion-muted">
            &copy; 2026 Jfc Fashion. All rights reserved.
        </p>

        {!! view_render_event('bagisto.shop.layout.footer.footer_text.after') !!}

    </div>

</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}