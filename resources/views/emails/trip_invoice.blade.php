@component('mail::message')
Hi {{ $trip->user->first_name }} {{ $trip->user->last_name }}
# Thank you for choosing gogo20 {{ $trip->vehicle_type }}.

{{-- The body of your message. --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent