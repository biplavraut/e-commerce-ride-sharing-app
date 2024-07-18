<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width" />
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Bootstrap core CSS -->
<link href="{{adminAssetsUrl('css/bootstrap.min.css')}}" rel="stylesheet" />
<!-- Material Dashboard CSS -->
<link href="{{adminAssetsUrl('css/material-dashboard.css?v=1.2.0')}}" rel="stylesheet" />
<!-- Fonts and icons -->
<link href="{{ myAsset('/css/font-awesome.min.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
      href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons" />
{{-- <link rel="stylesheet"
      href="{{ adminAssetsUrl('iconfont/material-icons.css') }}"> --}}

<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<!-- Favicon-->
<link rel="icon" href="{{ $company->cropImage(32,32,'logo') }}">
<title>@yield('title') - {{ $company->name }} Admin</title>

<!-- My css -->
<link href="{{myAsset('/css/asdh_admin.css')}}" rel="stylesheet">


<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.15.3/xlsx.full.min.js"></script>