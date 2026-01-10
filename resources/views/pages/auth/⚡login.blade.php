<?php

declare(strict_types=1);

ini_set('json.exceptions', '1');

use App\Models\Passkey;
use App\Models\User;
use App\Services\Serializer;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\AuthenticatorAssertionResponseValidator;
use Webauthn\CeremonyStep\CeremonyStepManagerFactory;
use Webauthn\Exception\AuthenticatorResponseVerificationException;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialRequestOptions;
use Webauthn\PublicKeyCredentialSource;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;

new #[Title('Login')]
class extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public string $answer = '';

    #[Locked]
    public string $optionEndpoint = '';

    public function mount(): void
    {
        $this->optionEndpoint = route('passkeys.authentication-options');
    }

    public function login(): void
    {
        $this->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // If the user has PassKeys, he won't be able to log in using just the password
        if (Auth::user()->passkeys()->count() > 0) {
            session()->flash('status', __('Your account has registered a passkey, please use the passkey to log in.'));

            Auth::logout();

            return;
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->dispatch('toast', status: 'success', message: __('Login successful!'));

        $this->redirectIntended(route('root', absolute: false), navigate: true);
    }

    public function loginWithPasskey(Serializer $serializer): void
    {
        $data = $this->validate(['answer' => ['required', 'json']]);

        $this->ensureIsNotRateLimited();

        try {
            $publicKeyCredential = $serializer->fromJson($data['answer'], PublicKeyCredential::class);

            if (! $publicKeyCredential->response instanceof AuthenticatorAssertionResponse) {
                $this->dispatch('toast', status: 'danger', message: __('Invalid Passkey'));

                return;
            }

            $rawId = json_decode($data['answer'], true)['rawId'];

            $passkey = Passkey::query()->where('credential_id', $rawId)->where('owner_type', User::class)->first();

            if (! $passkey) {
                $this->dispatch('toast', status: 'danger', message: __('Invalid Passkey'));

                return;
            }

            $publicKeyCredentialSource = $serializer->fromJson(json_encode($passkey->data),
                PublicKeyCredentialSource::class);

            $options = Session::get('passkey-authentication-options');

            if (! $options) {
                $this->dispatch('toast', status: 'danger', message: __('Invalid Passkey'));

                return;
            }

            $publicKeyCredentialRequestOptions = $serializer->fromJson($options,
                PublicKeyCredentialRequestOptions::class);

            AuthenticatorAssertionResponseValidator::create(new CeremonyStepManagerFactory()->requestCeremony())
                ->check(
                    publicKeyCredentialSource: $publicKeyCredentialSource,
                    authenticatorAssertionResponse: $publicKeyCredential->response,
                    publicKeyCredentialRequestOptions: $publicKeyCredentialRequestOptions,
                    host: request()->getHost(),
                    userHandle: null
                );
        } catch (SerializerExceptionInterface|AuthenticatorResponseVerificationException) {
            $this->dispatch('toast', status: 'danger', message: __('Invalid Passkey'));

            return;
        }

        $passkey->update([
            'last_used_at' => now(),
        ]);

        Auth::loginUsingId(id: $passkey->owner_id, remember: true);

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->dispatch('toast', status: 'success', message: __('Login successful!'));

        $this->redirectIntended(route('root', absolute: false), navigate: true);
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
};
?>

@assets
@vite('resources/ts/webauthn.ts')
@endassets

@script
<script>
    Alpine.data('authLoginPage', () => ({
        passkey: {
            optionEndpoint: $wire.optionEndpoint
        },
        browserSupportsWebAuthn,
        async loginWithPasskey() {
            if (!this.browserSupportsWebAuthn()) {
                this.$wire.$dispatch('toast', {
                    status: 'danger',
                    message: 'WebAuthn not supported'
                });

                return;
            }

            const response = await fetch(this.passkey.optionEndpoint);
            const optionsJSON = await response.json();

            try {
                this.$wire.answer = JSON.stringify(await startAuthentication({
                    optionsJSON
                }));
            } catch (error) {
                this.$wire.$dispatch('toast', {
                    status: 'danger',
                    message: 'Login failed, please try again later'
                });

                return;
            }

            this.$wire.loginWithPasskey();
        }
    }));
</script>
@endscript

<x-layouts.auth x-data="authLoginPage">
    <div class="fixed top-5 left-5">
        <a
            class="flex items-center text-2xl transition duration-150 ease-in text-zinc-400 dark:text-zinc-400 dark:hover:text-zinc-50 hover:text-zinc-600"
            href="{{ route('root') }}"
            wire:navigate
        >
            <x-icons.arrow-left-circle class="w-6" />
            <span class="ml-2">{{ __('Return to article list') }}</span>
        </a>
    </div>

    <div class="container mx-auto">
        <div class="flex flex-col justify-center items-center px-4 min-h-screen">
            {{-- page title --}}
            <div class="flex items-center text-2xl fill-current text-zinc-700 dark:text-zinc-50">
                <x-icons.door-open class="w-6" />
                <span class="ml-4">{{ __('Login') }}</span>
            </div>

            {{-- Login form --}}
            <x-card class="overflow-hidden mt-4 w-full sm:max-w-md">
                {{-- Session status message --}}
                <x-auth-session-status
                    class="mb-6"
                    :status="session('status')"
                />

                {{-- validate error message --}}}
                <x-auth-validation-errors
                    class="mb-6"
                    :errors="$errors"
                />

                <form
                    id="login"
                    wire:submit="login"
                >
                    {{-- mailbox --}}
                    <x-floating-label-input
                        id="email"
                        type="text"
                        placeholder="{{ __('Email') }}"
                        wire:model="email"
                        required
                        autofocus
                    />

                    {{-- Password --}}
                    <x-floating-label-input
                        class="mt-6"
                        id="password"
                        type="password"
                        placeholder="{{ __('Password') }}"
                        wire:model="password"
                        required
                    />

                    <div class="flex justify-between items-center mt-6">
                        <x-checkbox
                            id="remember"
                            name="remember"
                            wire:model="remember"
                        >
                            {{ __('Remember me') }}
                        </x-checkbox>

                        @if (Route::has('password.request'))
                            <a
                                class="text-zinc-400 dark:hover:text-zinc-50 hover:text-zinc-700"
                                href="{{ route('password.request') }}"
                                wire:navigate
                            >
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>

                    <x-button class="mt-6 w-full">{{ __('Login') }}</x-button>
                </form>

                {{-- Passkey login --}}
                <div class="relative mt-6">
                    <div
                        class="flex absolute inset-0 items-center"
                        aria-hidden="true"
                    >
                        <div class="w-full border-t border-zinc-200 dark:border-zinc-500"></div>
                    </div>
                    <div class="flex relative justify-center text-base font-medium">
                        <span class="px-6 bg-zinc-50 text-zinc-900 dark:bg-zinc-800 dark:text-zinc-50">{{ __('Or') }}</span>
                    </div>
                </div>

                <div class="mt-6">
                    <button
                        class="flex gap-3 justify-center items-center py-2 px-4 w-full rounded-xl ring-1 ring-inset cursor-pointer focus-visible:ring-transparent shadow-xs bg-zinc-50 text-zinc-900 ring-zinc-300 dark:bg-zinc-800 dark:text-zinc-50 dark:ring-zinc-700 dark:hover:bg-zinc-700 dark:active:bg-zinc-800 hover:bg-zinc-100 active:bg-zinc-50"
                        type="button"
                        x-on:click="loginWithPasskey"
                    >
                        <x-icons.fingerprint class="size-5" />
                        <span>{{ __('Use Passkeys') }}</span>
                    </button>
                </div>
            </x-card>
        </div>
    </div>
</x-layouts.auth>
