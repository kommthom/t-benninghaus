<?php

declare(strict_types=1);

use App\Mail\DestroyUserMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Member Center - Delete Account')] class extends Component {
    public User $user;

    public function mount(int $id): void
    {
        $this->user = User::findorFail($id);

        $this->authorize('update', $this->user);
    }

    public function sendDestroyEmail(User $user): void
    {
        $this->authorize('update', $user);

        // 生成一次性連結，並設定 5 分鐘後失效
        $destroyUserLink = URL::temporarySignedRoute('users.destroy', now()->addMinutes(5), ['user' => $user->id]);

        Mail::to($user)->queue(new DestroyUserMail($destroyUserLink));

        $this->dispatch('toast', status: 'success', message: __('Letter sent!'));
    }
};
?>

<x-layouts.main>
  <div class="container mx-auto grow">
    <div class="flex flex-col items-start justify-center gap-6 px-4 md:flex-row">
      <x-users.member-center-side-menu />

      <x-card class="flex w-full flex-col justify-center gap-6 md:max-w-2xl">
        <div class="space-y-4">
          <h1 class="w-full text-center text-2xl dark:text-zinc-50">{{ __('Delete Account') }}</h1>
          <hr class="h-0.5 border-0 bg-zinc-300 dark:bg-zinc-700">
        </div>

        <x-quotes.danger class="flex">
          <x-icons.exclamation-triangle class="w-5" />
          <span class="ml-2">{{ __('Please note! The articles and comments you have written will be deleted together and cannot be recovered!') }}</span>
        </x-quotes.danger>

        {{-- 說明 --}}
        <div class="flex flex-col items-start justify-center">
          <span class="dark:text-zinc-50">{{ __('We\'re sorry to see you go...') }}</span>
          <span class="dark:text-zinc-50">{{ __('If you are sure you want to delete your account, please click the button below and receive the email') }}</span>
        </div>

        {{-- Send account deletion email --}}
        <div class="w-full">
          <button
            class="focus:outline-hidden focus:ring-3 inline-flex items-center justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 uppercase tracking-widest text-zinc-50 ring-red-300 transition duration-150 ease-in-out hover:bg-red-700 focus:border-red-900 active:bg-red-900 disabled:opacity-25"
            type="button"
            wire:confirm="{{ __('Are you sure you want to send the account deletion email?') }}"
            wire:click="sendDestroyEmail({{ $user->id }})"
          >
            {{ __('Send account deletion email') }}
          </button>
        </div>
      </x-card>
    </div>
  </div>
</x-layouts.main>
