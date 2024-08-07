<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <title>Admin Login</title>

  <!-- Favicon-->
  {{-- <link rel="icon" href="{{ $company->cropImage(32,32,'logo') }}">
  <title>@yield('title') - {{ $company->name }} Admin</title> --}}

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
    type="text/css">
  {{--<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">--}}
  <link rel="stylesheet" href="{{ adminAssetsUrl('iconfont/material-icons.css') }}">
  <!-- Bootstrap Core Css -->
  <link href="{{adminAssetsUrl('login/plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet">
  <!-- Waves Effect Css -->
  <link href="{{adminAssetsUrl('login/plugins/node-waves/waves.css')}}" rel="stylesheet" />
  <!-- Animation Css -->
  <link href="{{adminAssetsUrl('login/plugins/animate-css/animate.css')}}" rel="stylesheet" />
  <!-- Custom Css -->
  <link href="{{adminAssetsUrl('login/css/style.min.css')}}" rel="stylesheet">
</head>

<body class="login-page">
  <div class="login-box">
    <div class="card">

      @if(count($errors))
      <div class="alert alert-danger alert-dismissible" role="alert">{{$errors->first()}}</div>
      @endif
      @if(session("success_message"))
      <div class="alert alert-success alert-dismissible" role="alert">{{session("success_message")}}</div>
      @endif

      <div class="body">
        <form id="sign_in" role="form" method="POST" action="{{ route('admin.login') }}">
          @csrf
          <div class="msg">Admin Login</div>
          <div class="input-group">
            <span class="input-group-addon">
              <i class="material-icons">email</i>
            </span>
            <div class="form-line">
              <input type="email" class="form-control" name="email" placeholder="Email" required autofocus
                value="{{ old('email') }}">
            </div>
          </div>
          <div class="input-group">
            <span class="input-group-addon">
              <i class="material-icons">lock</i>
            </span>
            <div class="form-line">
              <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-8 p-t-5">
              <input type="checkbox" name="remember" id="rememberme" class="filled-in chk-col-pink"
                {{ old('remember') ? 'checked' : '' }}>
              <label for="rememberme">Remember Me</label>
            </div>
            <div class="col-xs-4">
              <button class="btn btn-block bg-pink waves-effect" type="submit">SIGN IN
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Jquery Core Js -->
  <script src="{{adminAssetsUrl('login/plugins/jquery/jquery.min.js')}}"></script>
  <!-- Bootstrap Core Js -->
  <script src="{{adminAssetsUrl('login/plugins/bootstrap/js/bootstrap.js')}}"></script>
  <!-- Waves Effect Plugin Js -->
  <script src="{{adminAssetsUrl('login/plugins/node-waves/waves.js')}}"></script>
  <!-- Validation Plugin Js -->
  <script src="{{adminAssetsUrl('login/plugins/jquery-validation/jquery.validate.js')}}"></script>
  <!-- Custom Js -->
  <script src="{{adminAssetsUrl('login/js/admin.js')}}"></script>
  <script src="{{adminAssetsUrl('login/js/pages/examples/sign-in.js')}}"></script>
</body>

</html>