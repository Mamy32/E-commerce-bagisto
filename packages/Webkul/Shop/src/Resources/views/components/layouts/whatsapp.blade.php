@if (
    core()->getConfigData('general.content.whatsapp.enabled')
    && core()->getConfigData('general.content.whatsapp.number')
)
    {!! view_render_event('bagisto.shop.layout.whatsapp.before') !!}

    <a
        href="https://wa.me/{{ preg_replace('/[^0-9]/', '', core()->getConfigData('general.content.whatsapp.number')) }}?text={{ urlencode(core()->getConfigData('general.content.whatsapp.message') ?? '') }}"
        target="_blank"
        rel="noopener noreferrer"
        class="fixed bottom-4 z-[999] flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] shadow-lg transition-transform hover:scale-110 ltr:right-4 rtl:left-4"
        aria-label="@lang('shop::app.components.layouts.whatsapp.chat-on-whatsapp')"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            class="h-8 w-8 fill-white"
        >
            <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.13-2.9-7C17.19 3.03 14.7 2 12.04 2m0 1.67c2.2 0 4.27.86 5.82 2.42a8.2 8.2 0 0 1 2.41 5.82c0 4.54-3.7 8.24-8.24 8.24a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.18 8.18 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24m-4.52 4.7c-.16 0-.42.06-.64.31-.22.24-.85.83-.85 2.03 0 1.2.87 2.35.99 2.51.12.16 1.7 2.72 4.19 3.71 2.07.83 2.49.66 2.94.62.45-.04 1.46-.6 1.66-1.17.2-.58.2-1.08.14-1.18-.06-.1-.22-.16-.46-.28-.24-.12-1.46-.72-1.68-.8-.23-.08-.39-.12-.55.13-.16.24-.63.8-.77.96-.14.16-.28.18-.52.06-.24-.12-1.01-.37-1.92-1.19-.71-.63-1.19-1.42-1.33-1.66-.14-.24-.02-.37.1-.49.11-.11.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.55-1.34-.76-1.83-.2-.48-.4-.42-.55-.42h-.47Z" />
        </svg>
    </a>

    {!! view_render_event('bagisto.shop.layout.whatsapp.after') !!}
@endif
