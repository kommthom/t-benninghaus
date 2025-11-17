<?php

declare(strict_types=1);

use App\Models\Comment;
use App\Traits\MarkdownConverter;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    use MarkdownConverter;

    public Comment $comment;

    public function mount(int $id): void
    {
        $this->comment = Comment::query()
            ->with(['user', 'post', 'children'])
            ->findOr($id, fn() => abort(404));
    }

    #[On('update-comment-in-comments-show-page')]
    public function updateComment(int $id, string $body, string $updatedAt): void
    {
        $this->comment->id = $id;
        $this->comment->body = $body;
        $this->comment->updated_at = $updatedAt;
    }

    public function destroyComment(int $id): void
    {
        $comment = Comment::find(id: $id, columns: ['id', 'user_id', 'post_id']);

        // Check a comment is not deleted
        if (is_null($comment)) {
            $this->dispatch(event: 'toast', status: 'danger', message: __('This comment has been deleted!'));

            $this->redirect(url: route('root'), navigate: true);

            return;
        }

        $this->authorize('destroy', $comment);

        $comment->delete();

        $this->dispatch(event: 'update-comments-count');

        $this->dispatch(event: 'toast', status: 'success', message: __('Successfully deleted comment!'));

        $this->redirect(url: route('root'), navigate: true);
    }

    public function render()
    {
        $user = $this->comment->user_id ? $this->comment->user->name : __('Visitor');

        return $this->view()->title($user . __('\'s comment'));
    }
};
?>

@assets
  {{-- highlight code block style --}}
  @vite('node_modules/highlight.js/styles/atom-one-dark.css')
  {{-- highlight code block --}}
  @vite('resources/ts/highlight.ts')
@endassets

@script
  <script>
    Alpine.data('commentsShowPage', () => ({
      observers: [],
      openEditCommentModal() {
        this.$dispatch('open-edit-comment-modal', {
          comment: {
            groupName: this.$el.dataset.commentGroupName,
            id: this.$el.dataset.commentId,
            body: this.$el.dataset.commentBody
          }
        });
      },
      openCreateCommentModal() {
        this.$dispatch('open-create-comment-modal', {
          parentId: this.$el.dataset.commentId,
          replyTo: this.$el.dataset.commentUserName
        });
      },
      init() {
        let commentsObserver = highlightObserver(this.$root)
        this.observers.push(commentsObserver);

        hljs.highlightAll();
      },
      destroy() {
        this.observers.forEach((observer) => {
          observer.disconnect();
        });
      }
    }));
  </script>
@endscript

{{-- 文章列表 --}}
<x-layouts.main>
  <div
    class="container mx-auto grow"
    x-data="commentsShowPage"
  >
    <div class="flex items-stretch justify-center">
      <div class="flex w-full max-w-3xl flex-col items-center justify-start px-2 xl:px-0">
        <div class="flex w-full items-center justify-end text-zinc-500 md:justify-between dark:text-zinc-400">
          <span class="hidden md:inline">{{ $comment->post->title . __('\'s message') }}</span>

          <div class="flex gap-2 hover:text-zinc-600 hover:dark:text-zinc-300">
            <x-icons.file-earmark-richtext class="w-4" />
            <a href="{{ route('posts.show', ['id' => $comment->post->id, 'slug' => $comment->post->slug]) }}">{{ __('Return to article') }}</a>
          </div>
        </div>

        <x-dashed-card class="mt-6 w-full">
          <div class="flex flex-col">
            <div class="flex items-center space-x-4 text-base">
              @if (!is_null($comment->user_id))
                <a
                  href="{{ route('users.show', ['id' => $comment->user_id]) }}"
                  wire:navigate
                >
                  <img
                    class="size-10 rounded-full hover:ring-2 hover:ring-blue-400"
                    src="{{ $comment->user->gravatar_url }}"
                    alt="{{ $comment->user->name }}"
                  >
                </a>

                <span class="dark:text-zinc-50">{{ $comment->user->name }}</span>
              @else
                <x-icons.question-circle-fill class="size-10 text-zinc-300 dark:text-zinc-500" />

                <span class="dark:text-zinc-50">{{ __('Visitor') }}</span>
              @endif

              <time
                class="hidden text-zinc-400 md:block"
                datetime="{{ date('d-m-Y', strtotime($comment->created_at)) }}"
              >{{ date(__('Y year m month d day'), strtotime($comment->created_at)) }}</time>

              @if ($comment->created_at->toString() !== $comment->updated_at->toString())
                <span class="text-zinc-400">{{ __('(Edited)') }}</span>
              @endif
            </div>

            <div class="rich-text">
              {!! $this->convertToHtml($comment->body) !!}
            </div>

            <div class="flex items-center justify-end gap-6 text-base text-zinc-400">
              @auth
                @if (auth()->id() === $comment->user_id)
                  <button
                    class="flex cursor-pointer items-center hover:text-zinc-500 dark:hover:text-zinc-300"
                    data-comment-group-name="comments-show-page"
                    data-comment-id="{{ $comment->id }}"
                    data-comment-body="{{ $comment->body }}"
                    type="button"
                    x-on:click="openEditCommentModal"
                  >
                    <x-icons.pencil class="w-4" />
                    <span class="ml-2">{{ __('Edit') }}</span>
                  </button>
                @endif

                @if (in_array(auth()->id(), [$comment->user_id, $comment->post->user_id]))
                  <button
                    class="flex cursor-pointer items-center hover:text-zinc-500 dark:hover:text-zinc-300"
                    type="button"
                    wire:click="destroyComment({{ $comment->id }})"
                    wire:confirm="{{ __('Are you sure you want to delete this comment?') }}"
                  >
                    <x-icons.trash class="w-4" />
                    <span class="ml-2">{{ __('Delete') }}</span>
                  </button>
                @endif
              @endauth

              @if ($comment->hierarchy->level < config('comments.max_level'))
                <button
                  class="flex cursor-pointer items-center hover:text-zinc-500 dark:hover:text-zinc-300"
                  data-comment-id="{{ $comment->id }}"
                  data-comment-user-name="{{ is_null($comment->user_id) ? __('Visitor') : $comment->user->name }}"
                  type="button"
                  x-on:click="openCreateCommentModal"
                >
                  <x-icons.reply-fill class="w-4" />
                  <span class="ml-2">{{ __('Reply') }}</span>
                </button>
              @endif
            </div>
          </div>
        </x-dashed-card>

        @if ($comment->hierarchy->level < config('comments.max_level'))
          <div
            class="relative w-full pl-4 before:absolute before:bottom-0 before:left-0 before:top-6 before:w-1 before:rounded-full before:bg-emerald-400/20 before:contain-none md:pl-8 dark:before:bg-indigo-500/20"
          >
            {{-- new root comment will show here --}}
            <livewire:comments.group
              :post-id="$comment->post->id"
              :post-user-id="$comment->post->user_id"
              :parent-id="$comment->id"
              :current-level="$comment->hierarchy->level + 1"
              :comment-group-name="$comment->id . '-new-comment-group'"
            />

            @if ($comment->children->count() > 0)
              {{-- root comment list --}}
              <livewire:comments.list
                :post-id="$comment->post->id"
                :post-user-id="$comment->post->user_id"
                :parent-id="$comment->id"
                :current-level="$comment->hierarchy->level + 1"
                :comment-list-name="$comment->id . '-comment-list'"
              />
            @endif
          </div>
        @endif
      </div>

      @if ($comment->hierarchy->level < config('comments.max_level'))
        {{-- create comment modal --}}
        <livewire:comments.create-modal :post-id="$comment->post->id" />
      @endif

      @auth
        {{-- edit comment modal --}}
        <livewire:comments.edit-modal />
      @endauth
    </div>
  </div>
</x-layouts.main>
