<x-mail::message>
  # {{ __('Account Deletion Confirmation') }}

  {{ __('If you are sure you want to delete your account, please click on the button link below (the link will expire in 5 minutes).') }}

  <x-mail::button
    :url="$destroyLink"
    color="error"
  >
    {{ __('Confirm Account Deletion') }}
  </x-mail::button>

  {{ __('Thank you,') }}<br>
  {{ config('app.name') }}
</x-mail::message>
