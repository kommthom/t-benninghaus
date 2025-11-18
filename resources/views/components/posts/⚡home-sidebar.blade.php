<?php

declare(strict_types=1);

use App\Models\Link;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

new class extends Component {
    public function render()
    {
        $popularTags = Cache::remember('popularTags', now()->addDay(), function () {
            // 取出標籤使用次數前 20 名
            return Tag::withCount('posts')->orderByDesc('posts_count')->limit(20)->get();
        });

        $links = Cache::remember('links', now()->addDay(), function () {
            return Link::all();
        });

        return $this->view([
            'popularTags' => $popularTags,
            'links' => $links,
        ]);
    }
};
?>

@script
  <script>
    Alpine.data('postsHomeSidebarPart', () => ({
      rssLinkLabel: 'Subscribe to RSS',
      copyWebFeedUrl() {
        navigator.clipboard.writeText(this.$el.getAttribute('href')).then(
          () => this.rssLinkLabel = 'Copied successfully',
          () => this.rssLinkLabel = 'Copy failed'
        );

        setTimeout(() => this.rssLinkLabel = 'Copy RSS URL', 2000);
      }
    }));
  </script>
@endscript

<div
  class="space-y-6"
  x-data="postsHomeSidebarPart"
>
  {{-- 介紹 --}}
  <x-card class="group dark:text-zinc-50">
    <p
      class="bg-linear-to-r font-jetbrains-mono w-full from-green-500 via-emerald-500 to-teal-500 bg-clip-text text-center text-xl font-semibold text-transparent dark:border-white dark:from-indigo-500 dark:via-violet-500 dark:to-purple-500">
      echo 'Hello World';
    </p>

    <hr class="my-4 h-0.5 border-0 bg-zinc-300 dark:bg-zinc-700">

    <span class="group-gradient-underline-grow leading-relaxed">
      {{ __('This is a blog developed using the TALL Stack, used to record my learning process and the big and small things in life. ??') }}
    </span>

    <div class="mt-8 flex items-center justify-center">
      <a
        class="before:bg-lividus-600 dark:bg-lividus-700 group relative flex w-full items-center justify-center overflow-hidden rounded-lg bg-emerald-600 px-4 py-2 [transform:translateZ(0)] before:absolute before:left-1/2 before:top-1/2 before:size-8 before:-translate-x-1/2 before:-translate-y-1/2 before:scale-0 before:rounded-full before:opacity-0 before:transition before:duration-700 before:ease-in-out hover:before:scale-[10] hover:before:opacity-100 dark:before:bg-emerald-700"
        href="{{ route('posts.create') }}"
        wire:navigate
      >
        <div class="relative z-0 flex items-center text-zinc-200 transition duration-500 ease-in-out">
          <x-icons.pencil class="w-5" />
          <span class="ml-2">{{ __('Add New Article') }}</span>
        </div>
      </a>
    </div>
  </x-card>

  <x-card class="dark:text-zinc-50">
    <div class="flex items-center justify-center">
      <x-icons.rss class="w-5" />
      <span class="ml-2">{{ __('RSS Subscription') }}</span>
    </div>

    <hr class="my-4 h-0.5 border-0 bg-zinc-300 dark:bg-zinc-700">

    <span class="group-gradient-underline-grow leading-relaxed">
      {{ __('Get notified of the latest articles! ??') }}
    </span>

    <div class="mt-8 flex items-center justify-center">
      <a
        class="focus:outline-hidden focus:ring-3 inline-flex w-full items-center justify-center rounded-lg border border-transparent bg-zinc-500 px-4 py-2 tracking-widest text-zinc-50 ring-zinc-300 transition duration-150 ease-in-out hover:bg-zinc-600 focus:border-zinc-700 active:bg-zinc-500 dark:bg-zinc-600 dark:ring-zinc-800 dark:hover:bg-zinc-500 dark:active:bg-zinc-600"
        href="{{ route('feeds.main') }}"
        x-on:click.prevent="copyWebFeedUrl"
      >
        <span
          class="ml-2 h-[1lh]"
          x-text="rssLinkLabel"
        ></span>
      </a>
    </div>
  </x-card>

  {{-- Popular Tags --}}
  @if ($popularTags->count())
    <x-card class="dark:text-zinc-50">
      <div class="flex items-center justify-center">
        <x-icons.tags class="w-5" />
        <span class="ml-2">{{ __('Popular Tags') }}</span>
      </div>

      <hr class="my-4 h-0.5 border-0 bg-zinc-300 dark:bg-zinc-700">

      <div class="flex flex-wrap">
        @foreach ($popularTags as $popularTag)
          <x-tag :href="route('tags.show', ['id' => $popularTag->id])">
            {{ $popularTag->name }}
          </x-tag>
        @endforeach
      </div>
    </x-card>
  @endif

  {{-- Learning Resources Recommendation --}}
  @if ($links->count())
    <x-card class="dark:text-zinc-50">
      <div class="flex items-center justify-center">
        <x-icons.file-earmark-code class="w-5" />
        <span class="ml-2">{{ __('Learning Resources Recommendation') }}</span>
      </div>

      <hr class="my-4 h-0.5 border-0 bg-zinc-300 dark:bg-zinc-700">

      <div class="flex flex-col">
        @foreach ($links as $link)
          <a
            class="flex items-center rounded-md p-2 hover:bg-zinc-200 dark:text-zinc-50 dark:hover:bg-zinc-700"
            href="{{ $link->url }}"
            target="_blank"
            rel="nofollow noopener noreferrer"
          >
            <span class="mr-2 flex h-[1lh] items-center">
              <x-icons.link-45deg class="w-5" />
            </span>
            {{ $link->title }}
          </a>
        @endforeach
      </div>
    </x-card>
  @endif
</div>
