@component('mail::message')
# Verify Email

You have successfully created your account with us. Please click on the button below to verify your email.

@component('mail::button', ['url' => route('vendor.verify', $emailToken), 'color' => 'blue'])
Verify
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent