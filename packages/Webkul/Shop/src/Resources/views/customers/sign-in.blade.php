<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('shop::app.customers.login-form.page-title')"/>
    <meta name="keywords" content="@lang('shop::app.customers.login-form.page-title')"/>
@endPush

<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.login-form.page-title')
    </x-slot>

    <div class="container mt-20 max-1180:px-5 max-md:mt-12 mb-20 flex justify-center">
        <!-- Form Container -->
        <div class="w-full max-w-[480px]">
            {!! view_render_event('bagisto.shop.customers.login.logo.before') !!}
            
            <h1 class="text-5xl font-bold text-black max-md:text-4xl max-sm:text-3xl tracking-tight">
                Welcome back!
            </h1>

            <p class="mt-3 text-lg text-zinc-500 max-sm:text-base">
                Don't have an account? 
                <a class="text-navyBlue font-medium hover:underline" href="{{ route('shop.customers.register.index') }}">
                    Sign Up
                </a>
            </p>

            {!! view_render_event('bagisto.shop.customers.login.before') !!}

            <div class="mt-12 rounded max-sm:mt-8">
                <x-shop::form :action="route('shop.customer.session.create')">
                    {!! view_render_event('bagisto.shop.customers.login_form_controls.before') !!}

                    <!-- Email -->
                    <x-shop::form.control-group class="mb-5">
                        <x-shop::form.control-group.label class="font-medium text-black text-sm mb-2 block">
                            Email Address
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            class="!rounded-full px-6 py-4 max-md:py-3 max-sm:py-2 w-full border border-zinc-200 focus:border-navyBlue outline-none"
                            name="email"
                            rules="required|email"
                            value=""
                            :label="trans('shop::app.customers.login-form.email')"
                            placeholder="email@example.com"
                            :aria-label="trans('shop::app.customers.login-form.email')"
                            aria-required="true"
                        />
                        <x-shop::form.control-group.error control-name="email" />
                    </x-shop::form.control-group>

                    <!-- Password -->
                    <x-shop::form.control-group class="mb-5 relative">
                        <x-shop::form.control-group.label class="font-medium text-black text-sm mb-2 block">
                            Password
                        </x-shop::form.control-group.label>

                        <div class="relative">
                            <x-shop::form.control-group.control
                                type="password"
                                class="!rounded-full px-6 py-4 max-md:py-3 max-sm:py-2 w-full border border-zinc-200 focus:border-navyBlue outline-none pr-12"
                                id="password"
                                name="password"
                                rules="required|min:6"
                                value=""
                                :label="trans('shop::app.customers.login-form.password')"
                                :placeholder="trans('shop::app.customers.login-form.password')"
                                :aria-label="trans('shop::app.customers.login-form.password')"
                                aria-required="true"
                            />
                            
                            <!-- Toggle Password Icon -->
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 cursor-pointer flex items-center justify-center text-zinc-400 hover:text-black" onclick="switchVisibility()">
                                <svg id="eye-icon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-off-icon" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </div>
                        </div>

                        <x-shop::form.control-group.error control-name="password" />
                    </x-shop::form.control-group>

                    <!-- Captcha -->
                    @if (core()->getConfigData('customer.captcha.credentials.status'))
                        <x-shop::form.control-group class="mt-5">
                            {!! \Webkul\Customer\Facades\Captcha::render() !!}
                            <x-shop::form.control-group.error control-name="recaptcha_token" />
                        </x-shop::form.control-group>
                    @endif

                    <!-- Submit Button -->
                    <div class="mt-8 flex flex-col items-center gap-4">
                        <button
                            class="primary-button !rounded-full w-full py-4 text-center text-lg font-medium"
                            type="submit"
                        >
                            Login
                        </button>
                    </div>

                    <div class="mt-6 text-center text-sm text-zinc-500">
                        Forgot Password? 
                        <a href="{{ route('shop.customers.forgot_password.create') }}" class="text-navyBlue font-medium hover:underline">
                            Recover
                        </a>
                    </div>
                    
                    <div class="flex items-center gap-4 mt-8">
                        <hr class="flex-grow border-zinc-200">
                        <span class="text-zinc-400 text-sm">Or</span>
                        <hr class="flex-grow border-zinc-200">
                    </div>

                    {!! view_render_event('bagisto.shop.customers.login_form_controls.after') !!}
                </x-shop::form>
            </div>

            {!! view_render_event('bagisto.shop.customers.login.after') !!}

            @if (
                request()->cookie('enable-resend')
                && request()->cookie('email-for-resend')
            )
                <p class="mt-5 font-medium text-zinc-500 max-sm:text-center max-sm:text-sm">
                    <a
                        class="text-navyBlue"
                        href="{{ route('shop.customers.resend.verification_email', urlencode(request()->cookie('email-for-resend'))) }}"
                    >
                        @lang('shop::app.customers.login-form.resend-verification')
                    </a>
                </p>
            @endif

        </div>

    </div>

    @push('scripts')
        {!! \Webkul\Customer\Facades\Captcha::renderJS() !!}

        <script>
            function switchVisibility() {
                let passwordField = document.getElementById("password");
                let eyeIcon = document.getElementById("eye-icon");
                let eyeOffIcon = document.getElementById("eye-off-icon");

                if (passwordField.type === "password") {
                    passwordField.type = "text";
                    eyeIcon.classList.add("hidden");
                    eyeOffIcon.classList.remove("hidden");
                } else {
                    passwordField.type = "password";
                    eyeIcon.classList.remove("hidden");
                    eyeOffIcon.classList.add("hidden");
                }
            }
        </script>
    @endpush
</x-shop::layouts>
