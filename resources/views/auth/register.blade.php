<x-authentication-layout>
    <h1 class="text-3xl text-gray-800 dark:text-gray-100 font-bold mb-6">{{ __('Create your Account') }}</h1>
    <!-- Form -->
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="space-y-4">
            <div class="flex justify-between gap-5">
                <!-- Nombres -->
                <div>
                    <x-label for="name">{{ __('Full Name') }} <span class="text-red-500">*</span></x-label>
                    <x-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>

                <!-- Apellidos -->
                <div>
                    <x-label for="surnames">{{ __('Surnames') }} <span class="text-red-500">*</span></x-label>
                    <x-input id="surnames" type="text" name="surnames" :value="old('surnames')" required autocomplete="surnames" />
                </div>
            </div>

            <!-- Correo electrónico -->
            <div>
                <x-label for="email">{{ __('Email Address') }} <span class="text-red-500">*</span></x-label>
                <x-input id="email" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="flex justify-between gap-5">
                <!-- DNI -->
                <div>
                    <x-label for="dni">{{ __('DNI') }} <span class="text-red-500">*</span></x-label>
                    <x-input id="dni" type="text" name="dni" :value="old('dni')" required maxlength="8" />
                </div>

                <!-- Teléfono -->
                <div>
                    <x-label for="phone">{{ __('Phone') }} <span class="text-red-500">*</span></x-label>
                    <x-input id="phone" type="text" name="phone" :value="old('phone')" required maxlength="9" />
                </div>
            </div>

            <!-- Contraseña -->
            <div>
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div>
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" type="password" name="password_confirmation" required
                    autocomplete="new-password" />
            </div>
        </div>
        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
        <div class="mt-6">
            <label class="flex items-start">
                <input type="checkbox" class="form-checkbox mt-1" name="terms" id="terms" />
                <span class="text-sm ml-2">
                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                            'terms_of_service' =>
                                '<a target="_blank" href="' .
                                route('terms.show') .
                                '" class="text-sm underline hover:no-underline">' .
                                __('Terms of Service') .
                                '</a>',
                            'privacy_policy' =>
                                '<a target="_blank" href="' .
                                route('policy.show') .
                                '" class="text-sm underline hover:no-underline">' .
                                __('Privacy Policy') .
                                '</a>',
                        ]) !!}
                    </span>
            </label>
        </div>
        @endif
        <div class="flex items-center justify-between mt-6">
            <x-button>
                {{ __('Sign Up') }}
            </x-button>
        </div>
    </form>
    <x-validation-errors class="mt-4" />
    <!-- Footer -->
    <div class="pt-5 mt-6 border-t border-gray-100 dark:border-gray-700/60">
        <div class="text-sm">
            {{ __('Have an account?') }} <a
                class="font-medium text-violet-500 hover:text-violet-600 dark:hover:text-violet-400"
                href="{{ route('login') }}">{{ __('Sign In') }}</a>
        </div>
    </div>
</x-authentication-layout>
