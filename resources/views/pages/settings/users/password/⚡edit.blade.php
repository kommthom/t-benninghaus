<?php

declare(strict_types=1);

use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Member Center - Change Password')] class extends Component {
    public User $user;

    public string $current_password = '';

    public string $new_password = '';

    public string $new_password_confirmation = '';

    public function mount(int $id): void
    {
        $this->user = User::findOrFail($id);

        $this->authorize('update', $this->user);
    }

    protected function rules(): array
    {
        $passwordRule = Password::min(8)->letters()->mixedCase()->numbers();

        return [
            'current_password' => ['required', new MatchOldPassword()],
            'new_password' => ['required', 'confirmed', $passwordRule],
        ];
    }

    protected function messages(): array
    {
        return [
            'current_password.required' => __('Please enter current password'),
            'new_password.required' => __('Please enter new password'),
            'new_password.confirmed' => __('New password and confirm new password do not match'),
        ];
    }

    public function update(User $user): void
    {
        $this->authorize('update', $user);

        $this->validate();

        $user->update(['password' => $this->new_password]);

        $this->dispatch('toast', status: 'success', message: __('Password updated successfully!'));

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
    }
};
?>

<x-layouts.main>
  <div class="container mx-auto grow">
    <div class="flex flex-col items-start justify-center gap-6 px-4 md:flex-row">
      <x-users.member-center-side-menu />

      <x-card class="flex w-full flex-col justify-center gap-6 md:max-w-2xl">
        <div class="space-y-4">
          <h1 class="w-full text-center text-2xl dark:text-zinc-50">{{ __('Change Password') }}</h1>
          <hr class="h-0.5 border-0 bg-zinc-300 dark:bg-zinc-700">
        </div>

        {{-- validate error message --}}
        <x-auth-validation-errors :errors="$errors" />

        <form
          class="w-full space-y-6"
          wire:submit="update({{ $user->id }})"
        >
          {{-- Old Password --}}
          <x-floating-label-input
            id="current_password"
            type="password"
            placeholder="{{ __('Old Password') }}"
            wire:model="current_password"
            required
          />

           {{-- new password --}}
          <x-floating-label-input
            id="new_password"
            type="password"
            placeholder="{{ __('New Password') }}"
            wire:model="new_password"
            required
          />

          {{-- Confirm New Password --}}
          <x-floating-label-input
            id="new_password_confirmation"
            type="password"
            placeholder="{{ __('Confirm New Password') }}"
            wire:model="new_password_confirmation"
            required
          />

          <div class="flex items-center justify-end">
            {{-- save button --}}
            <x-button>
              <x-icons.save class="w-5" />
              <span class="ml-2">{{ __('Change Password') }}</span>
            </x-button>
          </div>
        </form>
      </x-card>
    </div>
  </div>
</x-layouts.main>
