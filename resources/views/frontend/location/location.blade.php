@extends('frontend.layouts.master')

<?php $currentpage = 'home';  ?>

@push('style')
<link rel="stylesheet" href="{{ frontendAsset('/frontend/css/otherstyle.css') }}">

<style>
  #map {
    height: 600px;
    /* The height is 400 pixels */
    width: 100%;
    /* The width is the width of the web page */
  }

  .inner__screen {
    background-image: url("/frontend/gallery/bg.png");
  }

  b {
    color: #4e4e4e;
  }
</style>

@endpush

@section('title', 'Delivery Location')

@section('content')

<main class=" terms-page inner-page ">
  <section class="faq" id="faq">
    <div class="section__rule">
      <center>
        <h6>Delivery Location</h6>
      </center>
      <div id="map">
        Loading Map...
      </div>
    </div>
  </section>
</main>

@endsection

@push('script')
<script>
  // Initialize and add the map
      function initMap() {
        // The location of Uluru
        const uluru = { lat: <?php echo $lat; ?>, lng: <?php echo $lang; ?> };
        // The map, centered at Uluru
        const map = new google.maps.Map(document.getElementById("map"), {
          zoom: 12,
          center: uluru,
        });
        // The marker, positioned at Uluru
        const marker = new google.maps.Marker({
          position: uluru,
          map: map,
        });
      };
</script>
<script src="{{ frontendAsset('/frontend/node_modules/scroll-out/dist/scroll-out.js') }}"></script>
<script type="text/javascript" src="{{ frontendAsset('/frontend/js/jquery.js') }}"></script>
<script type="text/javascript" src="{{ frontendAsset('/frontend/js/parallax.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDlaqrwW03Gup9jizkxZ0zsvZrwcXiEAVw&callback=initMap&libraries=&v=weekly" async></script>
@endpush