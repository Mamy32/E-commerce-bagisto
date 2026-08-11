@php
    $enabledSocials = [];
    foreach(['enable_facebook', 'enable_twitter', 'enable_google', 'enable_linkedin-openid', 'enable_github'] as $social) {
        if (core()->getConfigData('customer.settings.social_login.' . $social)) {
            $enabledSocials[] = $social;
        }
    }
@endphp

@if (count($enabledSocials) > 0)
<div class="flex flex-col gap-4 mt-2 mb-6">
    @foreach($enabledSocials as $social)
        @php 
            $icon = explode('_', $social);
            $name = ucfirst($icon[1]);
            if ($name === 'Linkedin-openid') {
                $name = 'LinkedIn';
            }
        @endphp

        <a
            href="{{ route('customer.social-login.index', $icon[1]) }}"
            class="group flex items-center justify-center gap-3 w-full py-3.5 border-2 border-zinc-200 rounded-full bg-white transition-all duration-300 hover:border-fashion-navy hover:bg-zinc-50 hover:shadow-md hover:-translate-y-1"
            aria-label="{{ $name }}"
        >
            <div class="w-5 h-5 flex items-center justify-center transition-transform duration-300 group-hover:scale-110">
                @include('social_login::icons.' . $icon[1])
            </div>
            <span class="font-semibold text-zinc-700 transition-colors duration-300 group-hover:text-fashion-navy">Login with {{ $name }}</span>
        </a>
    @endforeach

    <div class="flex items-center gap-4 my-2">
        <div class="flex-1 h-px bg-zinc-200"></div>
        <span class="text-xs text-zinc-400 font-bold uppercase tracking-wider">OR</span>
        <div class="flex-1 h-px bg-zinc-200"></div>
    </div>
</div>
@endif