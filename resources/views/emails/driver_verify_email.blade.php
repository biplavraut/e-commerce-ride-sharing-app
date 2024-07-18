@component('mail::message')
# Verify Email

You have successfully created your account with us. Please click on the button below to verify your email.

Your E-mail Verify Token is {{ $emailToken }}

@component('mail::button', ['url' => route('driver-verify', $emailToken), 'color' => 'green'])
Verify
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent