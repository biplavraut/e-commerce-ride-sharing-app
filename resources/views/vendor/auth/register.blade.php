<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <title>Register - GoGo20 Vendor</title>
  <!-- Favicon-->
  <link rel="shortcut icon" href="{{ asset('frontend/images/favicon.png') }}" type="image/x-icon" />


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
        <form id="sign_in" role="form" method="POST" action="{{ route('vendor.register') }}">
          {{ csrf_field() }}
          <div class="msg">Register as a Vendor</div>

          <div class="input-group">
            <span class="input-group-addon">
              <i class="material-icons">business</i>
            </span>
            <div class="form-line">
              <input type="text" class="form-control" name="businessName" placeholder="Business Name" required autofocus
                value="{{ old('businessName') }}">
            </div>
          </div>

          <div class="input-group">
            <span class="input-group-addon">
              <i class="material-icons">shopping_bag</i>
            </span>
            <div class="form-line">
              <select class="form-control" name="type" title="Type/Category" required>
                <option disabled selected>Choose Business Type/Category</option>
                <option value="food">Food</option>
                <option value="mart">Mart</option>
                <option value="meat">Meat</option>
                <option value="drink">Drink</option>
                <option value="health">Health</option>
                <option value="send">Send</option>
                <option value="clean">Clean</option>
                <option value="style">Style</option>
                <option value="ee">EE</option>
                <option value="pro">Pro</option>
              </select>
            </div>
          </div>

          <div class="input-group">
            <span class="input-group-addon">
              <i class="material-icons">settings_input_component</i>
            </span>
            <div class="form-line">
              <select class="form-control" name="partnershipType" title="Partnership Type" required>
                <option disabled selected>Choose Partnership Type</option>
                <option value="single">Single</option>
                <option value="joint">Joint</option>
                <option value="pvt. ltd.">Pvt. Ltd.</option>
                <option value="limited">Limited</option>
              </select>
            </div>
          </div>

          <div class="input-group">
            <span class="input-group-addon">
              <i class="material-icons">person</i>
            </span>
            <div class="form-line">
              <input type="text" class="form-control" name="firstName" placeholder="First Name" required autofocus
                value="{{ old('firstName') }}">
            </div>
          </div>

          <div class="input-group">
            <span class="input-group-addon">
              <i class="material-icons">person_outline</i>
            </span>
            <div class="form-line">
              <input type="text" class="form-control" name="lastName" placeholder="Last Name" required autofocus
                value="{{ old('lastName') }}">
            </div>
          </div>

          <div class="input-group">
            <span class="input-group-addon">
              <i class="material-icons">email</i>
            </span>
            <div class="form-line">
              <input type="email" class="form-control" name="email" placeholder="E-mail" autofocus
                value="{{ old('email') }}">
            </div>
          </div>
          <div class="input-group">
            <span class="input-group-addon">
              <i class="material-icons">phone</i>
            </span>
            <div class="form-line">
              <input type="tel" class="form-control" name="phone" placeholder="Phone" required maxlength="10"
                minlength="10" autofocus value="{{ old('phone') }}">
            </div>
          </div>

          <div class="input-group">
            <span class="input-group-addon">
              <i class="material-icons">hearing</i>
            </span>
            <div class="form-line">
              <select class="form-control" name="heardFrom" title="How did you hear about gogo20?" required>
                <option disabled selected>How did you hear about gogo20?</option>
                <option value="friends">Friends</option>
                <option value="facebook">Facebook</option>
                <option value="google">Google</option>
                <option value="ads">Ads</option>
              </select>
            </div>
          </div>

          <div class="input-group">
            <span class="input-group-addon">
              <i class="material-icons">location_city</i>
            </span>
            <div class="form-line">
              <input type="text" class="form-control" name="city" placeholder="City" title="City of Business"
                onclick="test(this)" id="city" required>
            </div>
          </div>

          <div class="input-group">
            <span class="input-group-addon">
              <i class="material-icons">location_on</i>
            </span>
            <div class="form-line">
              <input type="text" class="form-control" name="address" placeholder="Full Address" title="Full Address"
                onclick="test(this)" id="location" required>
            </div>
          </div>


          <div class="input-group">
            <span class="input-group-addon">
              <i class="material-icons">lock</i>
            </span>
            <div class="form-line">
              <input type="password" class="form-control" name="password" placeholder="Password" required
                autocomplete="new-password">
            </div>
          </div>


          <div class="input-group">
            <span class="input-group-addon">
              <i class="material-icons">lock</i>
            </span>
            <div class="form-line">
              <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password"
                required autocomplete="new-password">
            </div>
          </div>



          <div class="row">
            <div class="col-xs-4 pull-right">
              <button class="btn btn-block bg-pink waves-effect" type="submit"
                style="color:#fff;font-size:14pt;">Register
              </button>
            </div>
          </div>
          {{-- Normal register --}}
          <div class="row m-t-15 m-b--20">
            <div class="col-xs-12 text-center">
              <a href="{{route('vendor.login')}}">Already have an account. Login from here.</a>
            </div>
            {{-- Forget Password --}}
            {{-- <div class="col-xs-6 align-right">
            <a href="#">Forgot Password?</a>
          </div> --}}
          </div>

        </form>
      </div>
    </div>
  </div>

  <script>
    function test(e){
    let lat, lang;

    if (navigator.geolocation){
      navigator.geolocation.getCurrentPosition(showPosition);
    }
  
  }

  function showPosition(position){ 
      lat=position.coords.latitude;
      lang=position.coords.longitude;


     if(position){
      $.ajax({
        url: 'https://api.opencagedata.com/geocode/v1/json?key=27ddafe76ecf4e9994bb61acf05e0243&q='+lat+'+'+lang,
                success: function(data){
                    let city = data.results[0].components.city;
                    let country = data.results[0].components.country;

                  $("#location").val(city+', '+country);                    
                  // $("#city").val(city);                    
                }
        });
     }else{
       
     }

  }

  </script>

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