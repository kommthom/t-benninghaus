@component('mail::message')
  # {{ __('Successfully created a new passkey') }}

 {{ __('A new passkey ') }}}{{ __('has been successfully created for your account.') }}

  {{ __('Thank you,') }}<br>
  {{ config('app.name') }}
@endcomponent
