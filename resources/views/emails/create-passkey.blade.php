@component('mail::message')
  # {{ __('Successfully created a new passkey') }}
A new password key "{{ $passkeyName }}" has been successfully created for your account.
 {{ __('A new passkey ') . $passkeyName . __('has been successfully created for your account.') }}

  {{ __('Thank you,') }}<br>
  {{ config('app.name') }}
@endcomponent
