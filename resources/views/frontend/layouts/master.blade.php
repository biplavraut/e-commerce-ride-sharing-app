@include('frontend.layouts.header')

@if (request()->is('delivery-pilot') || request()->is('register') || request()->is('login'))
{{-- ------ --}}
@else
@include('frontend.layouts.nav')
@endif

@include('common.flash_message')

@yield('content')

@include('frontend.layouts.footer')