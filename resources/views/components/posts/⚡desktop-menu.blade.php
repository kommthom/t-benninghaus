<?php

declare(strict_types=1);

use App\Models\Post;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component {
    #[Locked]
    public int $postId;

    public string $postTitle;

    #[Locked]
    public int $authorId;

    public function destroy(Post $post): void
    {
        $this->authorize('destroy', $post);

        $post->withoutTimestamps(fn() => $post->delete());

        $this->dispatch('toast', status: 'success', message: __('Successfully deleted post!'));

        $this->redirectRoute(
            name: 'users.show',
            parameters: [
                'id' => auth()->id(),
                'tab' => 'posts',
                'current-posts-year' => $post->created_at->format('Y'),
            ],
            // @pest-mutate-ignore
            navigate: true,
        );
    }
};
?>

<div class="sticky top-1/2 flex -translate-y-1/2 flex-col space-y-2">
  {{-- Home --}}
  <x-tooltip
    :tooltip-text="'{{ __('Return to homepage') }}'"
    :tooltip-position="'right'"
  >
    <a
      class="group flex h-14 w-14 cursor-pointer items-center justify-center text-zinc-500 dark:text-zinc-400"
      href="{{ route('posts.index') }}"
      role="button"
      wire:navigate
    >
      <x-icons.home class="w-6 text-2xl transition duration-150 ease-in group-hover:rotate-12 group-hover:scale-125" />
    </a>
  </x-tooltip>

  <!-- Facebook share button -->
  <x-tooltip
    :tooltip-text="'{{ __('Share to FB') }}'"
    :click-text="'{{ __('Yay!') }}'"
    :tooltip-position="'right'"
  >
    <button
      class="group flex h-14 w-14 cursor-pointer items-center justify-center text-zinc-500 dark:text-zinc-400"
      data-sharer="facebook"
      data-hashtag="{{ config('app.name') }}"
      data-url="{{ request()->fullUrl() }}"
      type="button"
    >
      <x-icons.facebook
        class="w-6 text-2xl transition duration-150 ease-in group-hover:rotate-12 group-hover:scale-125" />
    </button>
  </x-tooltip>

  <!-- x share button -->
  <x-tooltip
    :tooltip-text="'{{ __('Share to X') }}'"
    :click-text="'{{ __('Just a moment...') }}'"
    :tooltip-position="'right'"
  >
    <button
      class="group flex h-14 w-14 cursor-pointer items-center justify-center text-zinc-500 dark:text-zinc-400"
      data-sharer="x"
      data-title="{{ $postTitle }}"
      data-hashtags="{{ config('app.name') }}"
      data-url="{{ request()->fullUrl() }}"
      type="button"
    >
      <x-icons.twitter-x
        class="w-6 text-2xl transition duration-150 ease-in group-hover:rotate-12 group-hover:scale-125"
      />
    </button>
  </x-tooltip>

  <!-- Copy link button -->
  <x-tooltip
    :tooltip-text="'{{ __('Copy link') }}'"
    :click-text="'{{ __('Got it!') }}'"
    :tooltip-position="'right'"
  >
    <button
      class="group flex h-14 w-14 cursor-pointer items-center justify-center text-zinc-500 dark:text-zinc-400"
      data-clipboard="{{ urldecode(request()->fullUrl()) }}"
      type="button"
    >
      <x-icons.link-45deg
        class="w-6 text-2xl transition duration-150 ease-in group-hover:rotate-12 group-hover:scale-125"
      />
    </button>
  </x-tooltip>

  {{-- Edit Article --}}
  @if (auth()->id() === $authorId)
    <div class="h-[2px] w-14 bg-zinc-300 dark:bg-zinc-600"></div>

    <x-tooltip
      :tooltip-text="'{{ __('Edit Article') }}'"
      :tooltip-position="'right'"
    >
      <a
        class="group flex h-14 w-14 cursor-pointer items-center justify-center text-zinc-500 dark:text-zinc-400"
        href="{{ route('posts.edit', ['id' => $postId]) }}"
        role="button"
        wire:navigate
      >
        <x-icons.pencil-square
          class="w-6 text-2xl transition duration-150 ease-in group-hover:rotate-12 group-hover:scale-125"
        />
      </a>
    </x-tooltip>

    {{-- 刪除 --}}
    <x-tooltip
      :tooltip-text="'{{ __('Delete Article') }}'"
      :tooltip-position="'right'"
    >
      <button
        class="group flex h-14 w-14 cursor-pointer items-center justify-center text-zinc-500 dark:text-zinc-400"
        type="button"
        title="{{ __('Delete Article') }}"
        wire:confirm="{{ __('Are you sure you want to delete the article? (It can be restored within 7 days)') }}"
        wire:click="destroy({{ $postId }})"
      >
        <x-icons.trash
          class="w-6 text-2xl transition duration-150 ease-in group-hover:rotate-12 group-hover:scale-125" />
      </button>
    </x-tooltip>
  @endif
</div>
