<footer
  class="mt-6 bg-zinc-800 pt-4 dark:bg-zinc-950"
  id="footer"
>
  <div class="justify-left m-auto flex max-w-6xl flex-wrap text-zinc-800">

    {{-- Col-1 --}}
    <div class="w-1/2 p-5 sm:w-1/3">
      {{-- Title --}}
      <div class="mb-6 text-lg font-semibold uppercase text-zinc-50">
        {{ __('About') }}
      </div>
      {{-- Links --}}
      <a
        class="my-3 block font-medium text-zinc-400 duration-300 hover:text-zinc-50"
        href="https://github.com/kommthom/t-benninghaus/"
        target="_blank"
        rel="nofollow noopener noreferrer"
      >
        {{ __('Website Source Code') }}
      </a>
      <a
        class="my-3 block font-medium text-zinc-400 duration-300 hover:text-zinc-50"
        href="https://docfunc.com/"
        target="_blank"
        rel="nofollow noopener noreferrer"
      >
        {{ __('DocFunc (Original Website)') }}
      </a>
      <a
        class="my-3 block font-medium text-zinc-400 duration-300 hover:text-zinc-50"
        href="https://de.gravatar.com/blissfulc67086990b.card"
        target="_blank"
        rel="nofollow noopener noreferrer"
      >
        {{ __('Author') }}
      </a>
    </div>

    {{-- Col-2 --}}
    <div class="w-1/2 p-5 sm:w-1/3">
      {{-- Title --}}
      <div class="mb-6 text-lg font-semibold uppercase text-zinc-50">
        {{ __('Learning') }}
      </div>

      {{-- Links --}}
      <a
        class="my-3 block font-medium text-zinc-400 duration-300 hover:text-zinc-50"
        href="https://www.freecodecamp.org/"
        target="_blank"
        rel="nofollow noopener noreferrer"
      >
        {{ __('freeCodeCamp') }}
      </a>

      <a
        class="my-3 block font-medium text-zinc-400 duration-300 hover:text-zinc-50"
        href="https://laracasts.com/"
        target="_blank"
        rel="nofollow noopener noreferrer"
      >
        {{ __('Laracasts') }}
      </a>
    </div>

    {{-- Col-3 --}}
    <div class="w-1/2 p-5 sm:w-1/3">
      {{-- Title --}}
      <div class="mb-6 text-lg font-semibold uppercase text-zinc-50">
        {{ __('Special Thanks') }}
      </div>

      {{-- Links --}}
      <a
        class="my-3 block font-medium text-zinc-400 duration-300 hover:text-zinc-50"
        href="https://laravel.com/"
        target="_blank"
        rel="nofollow noopener noreferrer"
      >
        {{ __('Laravel') }}
      </a>
      <a
        class="my-3 block font-medium text-zinc-400 duration-300 hover:text-zinc-50"
        href="https://livewire.laravel.com/"
        target="_blank"
        rel="nofollow noopener noreferrer"
      >
        {{ __('Laravel Livewire') }}
      </a>
      <a
        class="my-3 block font-medium text-zinc-400 duration-300 hover:text-zinc-50"
        href="https://tailwindcss.com/"
        target="_blank"
        rel="nofollow noopener noreferrer"
      >
        {{ __('Tailwind CSS') }}
      </a>
      <a
        class="my-3 block font-medium text-zinc-400 duration-300 hover:text-zinc-50"
        href="https://getbootstrap.com/"
        target="_blank"
        rel="nofollow noopener noreferrer"
      >
        {{ __('Bootstrap') }}
      </a>
      <a
        class="my-3 block font-medium text-zinc-400 duration-300 hover:text-zinc-50"
        href="https://alpinejs.dev/"
        target="_blank"
        rel="nofollow noopener noreferrer"
      >
        {{ __('Alpine.js') }}
      </a>
    </div>
  </div>

  {{-- Copyright Bar --}}
  <div class="pt-2">
    <div class="m-auto flex max-w-6xl flex-col border-t border-zinc-500 px-3 pb-5 pt-5 md:flex-row">
      <div class="mb-2 flex items-center justify-center text-sm text-zinc-400 md:mb-0">
        © Copyright 2020-{{ date('Y') . __('. All Rights Reserved.') }}
      </div>

      <div class="flex flex-row items-center justify-center space-x-4 md:flex-auto md:justify-end">
        <a
          class="text-2xl text-zinc-400 duration-300 hover:text-zinc-50"
          href="https://github.com/kommthom/"
          aria-label="GitHub"
          target="_blank"
          rel="nofollow noopener noreferrer"
        >
          <x-icons.github class="w-6" />
        </a>
        <a
          class="text-2xl text-zinc-400 duration-300 hover:text-zinc-50"
          href="https://x.com/ThomasBenn99622/"
          aria-label="Twitter"
          target="_blank"
          rel="nofollow noopener noreferrer"
        >
          <x-icons.twitter-x class="w-6" />
        </a>
        <a
          class="text-2xl text-zinc-400 duration-300 hover:text-zinc-50"
          href="https://www.facebook.com/profile.php?id=61584721160915"
          aria-label="Facebook"
          target="_blank"
          rel="nofollow noopener noreferrer"
        >
          <x-icons.facebook class="w-6" />
        </a>
      </div>
    </div>
  </div>
</footer>
