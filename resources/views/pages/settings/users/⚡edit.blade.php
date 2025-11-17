<?php

declare(strict_types=1);

use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Member Center - Edit Profile')] class extends Component {
    public string $name;

    public ?string $introduction;

    public User $user;

    public function mount(int $id): void
    {
        $this->user = User::findOrFail($id);

        // 會員只能進入自己的頁面，規則寫在 UserPolicy
        $this->authorize('update', $this->user);

        $this->name = $this->user->name;
        $this->introduction = $this->user->introduction;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'regex:/^[A-Za-z0-9\-\_]+$/u', 'between:3,25', 'unique:users,name,' . $this->user->id],
            'introduction' => ['max:120'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => __('Please fill in the member name'),
            'name.string' => __('Member name must be a string'),
            'name.regex' => __('Member name only supports English, numbers, hyphens and underscores'),
            'name.between' => __('Member name must be between 3 - 25 characters.'),
            'name.unique' => __('Member name has been used, please refill'),
            'introduction.max' => __('Personal profile up to 120 characters'),
        ];
    }

    public function update(User $user): void
    {
        $this->authorize('update', $user);

        $this->validate();

        // 更新會員資料
        $user->update([
            'name' => $this->name,
            'introduction' => $this->introduction,
        ]);

        $this->dispatch('toast', status: 'success', message: __('Personal information updated successfully'));
    }
};
?>

<x-layouts.main>
  <div class="container mx-auto grow">
    <div class="flex flex-col items-start justify-center gap-6 px-4 md:flex-row">
      <x-users.member-center-side-menu />

      <x-card class="flex w-full flex-col justify-center gap-6 md:max-w-2xl">
        <div class="space-y-4">
          <h1 class="w-full text-center text-2xl dark:text-zinc-50">{{ __('Edit Profile') }}</h1>
          <hr class="h-0.5 border-0 bg-zinc-300 dark:bg-zinc-700">
        </div>

        <div class="flex flex-col items-center justify-center gap-4">
          {{-- 大頭貼照片 --}}
          <img
            class="size-48 rounded-full"
            src="{{ $user->gravatar_url }}"
            alt="{{ $name }}"
          >

          <div class="flex dark:text-zinc-50">
            <span class="mr-2">{{ __('Personal Image by') }}</span>
            <a
              class="text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-50"
              href="https://zh-tw.gravatar.com/"
              target="_blank"
              rel="nofollow noopener noreferrer"
            >Gravatar</a>
            <span class="ml-2">{{ __('Provided by') }}</span>
          </div>
        </div>

        {{-- 驗證錯誤訊息 --}}
        <x-auth-validation-errors :errors="$errors" />

        <form
          class="w-full space-y-6"
          wire:submit="update({{ $user->id }})"
        >
          @php
            $emailLength = strlen($user->email);
            $startToMask = round($emailLength / 4);
            $maskLength = ceil($emailLength / 2);
          @endphp

          <x-floating-label-input
            id="email"
            type="text"
            value="{{ str()->mask($user->email, '*', $startToMask, $maskLength) }}"
            placeholder="{{ __('Email') }}"
            disabled
          />

          <x-floating-label-input
            id="name"
            type="text"
            value="{{ old('name', $name) }}"
            wire:model.blur="name"
            placeholder="{{ __('Your Name (Only English, numbers, _, or - are allowed)') }}"
            required
            autofocus
          />

          <x-floating-label-textarea
            id="introduction"
            name="introduction"
            wire:model.blur="introduction"
            placeholder="{{ __('Introduce yourself! (Maximum 80 characters)') }}"
            rows="5"
          >{{ old('introduction', $introduction) }}</x-floating-label-textarea>

          <div class="flex items-center justify-end">
            {{-- Save Button --}}
            <x-button>
              <x-icons.save class="w-5" />
              <span class="ml-2">{{ __('Save') }}</span>
            </x-button>
          </div>
        </form>
      </x-card>
    </div>
  </div>
</x-layouts.main>
