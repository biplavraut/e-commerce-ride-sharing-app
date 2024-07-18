<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
  @include('vendor.layouts.header')

  @stack('style')
</head>

<body>
  @include('common.flash_message_ajax')

  @yield('content')

  @include('vendor.layouts.footer')

  @stack('script')
</body>

</html>