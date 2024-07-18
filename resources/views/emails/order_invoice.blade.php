@component('mail::message')
Hi {{ $order->user->first_name }} {{ $order->user->last_name }}, we hope your delivery item is successfully delivered to you.
# Thank you for choosing gogo20.

{{-- The body of your message. --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent