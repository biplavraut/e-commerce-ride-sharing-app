<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @stack('meta')

  <link rel="icon" href="{{ $company->cropImage(50,50,'logo') }}">
  <title>@yield('title') - {{$company->name}} | Everyday solution</title>

  <!-- materailizeicon -->
  <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round"
    rel="stylesheet">

  <!-- slick -->
  <link rel="stylesheet" href="{{ frontendAsset('/frontend/css/slick/slick-theme.css') }}">
  <link rel="stylesheet" href="{{ frontendAsset('/frontend/css/slick/slick.css' ) }}">

  <!-- bootstrap -->
  <link rel="stylesheet" href="{{ frontendAsset('/frontend/css/bootstrap/css/bootstrap-grid.min.css' ) }}">
  <link rel="stylesheet" href="{{ frontendAsset('/frontend/css/bootstrap/css/bootstrap-reboot.min.css' ) }}">
  <link rel="stylesheet" href="{{ frontendAsset('/frontend/css/bootstrap/css/bootstrap.min.css' ) }}">
  <link rel="stylesheet" href="{{ frontendAsset('/frontend/css/slick/slick.css' ) }}">
  <link rel="stylesheet" href="{{ frontendAsset('/frontend/css/animate.css' ) }}">


  <link rel="stylesheet" href="{{myAsset('css/asdh.css')}}">
  @stack('style')

  <?php 
	if(  $currentpage== 'home'){
		echo '<link rel="stylesheet" href="/frontend/css/style.css">'; 
	}
	else{
		echo '<link rel="stylesheet" href="/frontend/css/otherstyle.css">';
	}
	?>
</head>

<body>