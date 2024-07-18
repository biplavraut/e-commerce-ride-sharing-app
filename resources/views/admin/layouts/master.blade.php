<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
  @include('admin.layouts.header')

  @stack('style')
</head>

<body>
  @include('common.flash_message_ajax')

  @yield('content')


  <script src="https://www.gstatic.com/firebasejs/4.5.0/firebase.js"></script>
  <script>
    // Initialize Firebase
        var config = {
          apiKey: "{{ env('FIREBASE_APIKEY') }}",
          authDomain: "{{ env('FIREBASE_AUTHDOMAIN') }}",
          databaseURL: "{{ env('FIREBASE_DATABASEURL') }}",
          storageBucket: "{{ env('FIREBASE_STORAGEBUCKET') }}",
        };
        firebase.initializeApp(config);
        const database = firebase.database();
  </script>


  @include('admin.layouts.footer')

  @stack('script')

</body>

</html>