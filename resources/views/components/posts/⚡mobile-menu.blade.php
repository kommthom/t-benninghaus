<?php

declare(strict_types=1);

use App\Models\Post;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component {
    #[Locked]
    public int $postId;

    public function destroy(Post $post): void
    {
        $this->authorize('destroy', $post);

        $post->withoutTimestamps(fn() => $post->delete());

        $this->dispatch('toast', status: 'success', message: __('Successfully deleted the article!'));

        $this->redirect(
            route('users.show', [
                'id' => auth()->id(),
                'tab' => 'posts',
                'current-posts-year' => $post->created_at->format('Y'),
            ]),
        );
    }
};
?>

<div class="isolate mb-6 inline-flex w-full items-center justify-end gap-0.5 rounded-md text-sm text-zinc-400 xl:hidden">
  <a
    class="relative inline-flex items-center rounded-l-xl bg-zinc-50 px-4 py-2 text-zinc-400 hover:bg-zinc-100 focus:z-10 dark:bg-zinc-800 dark:hover:bg-zinc-700"
    href="{{ route('posts.edit', ['id' => $postId]) }}"
  >
    <x-icons.pencil class="w-4" />
    <span class="ml-2">{{ __('Edit') }}</span>
  </a>

  <button
    class="relative -ml-px inline-flex cursor-pointer items-center rounded-r-xl bg-zinc-50 px-4 py-2 text-zinc-400 hover:bg-zinc-100 focus:z-10 dark:bg-zinc-800 dark:hover:bg-zinc-700"
    type="button"
    wire:confirm="{{ __('Are you sure you want to delete the article? (It can be restored within 7 days)') }}"
    wire:click="destroy({{ $postId }})"
  >
    <x-icons.trash class="w-4" />
    <span class="ml-2">{{ __('Delete') }}</span>
  </button>
</div>
