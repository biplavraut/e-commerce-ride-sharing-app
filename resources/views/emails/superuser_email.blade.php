@component('mail::message')
# Hello {{ $user->name }}

You are now gogo20 System User.

Use following credentials to access your dashboard

<div>
  Login URL: <a
    href="{{ env('APP_URL', 'https://gogo20.com') }}/admin">{{ env('APP_URL', 'https://gogo20.com') }}/admin</a>
</div>
<div>
  E-mail: {{ $user->email }}
</div>
<div>
  Password: {{ $password }}
</div>
<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent