<div class="flex gap-4 mt-6">
    @foreach(['enable_facebook', 'enable_twitter', 'enable_google', 'enable_linkedin-openid', 'enable_github'] as $social)
        @if (! core()->getConfigData('customer.settings.social_login.' . $social))
            @continue
        @endif

        @php 
            $icon = explode('_', $social);
            $name = ucfirst($icon[1]);
            // If it's linkedin-openid, rename it to LinkedIn
            if ($name === 'Linkedin-openid') {
                $name = 'LinkedIn';
            }
        @endphp

        <a
            href="{{ route('customer.social-login.index', $icon[1]) }}"
            class="flex items-center justify-center gap-2 w-full py-3 border border-zinc-200 rounded-full transition-all hover:bg-zinc-50"
            aria-label="{{ $name }}"
        >
            <div class="w-6 h-6 flex items-center justify-center">
                @include('social_login::icons.' . $icon[1])
            </div>
            <span class="font-medium text-black">{{ $name }}</span>
        </a>
    @endforeach
</div>