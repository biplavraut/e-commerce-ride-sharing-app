@extends('frontend.layouts.master')

<?php $currentpage = 'home';  ?>

@push('style')
<link rel="stylesheet" href="{{ frontendAsset('/frontend/css/otherstyle.css') }}">
@endpush

@section('title', 'Delivery Pilot')

@section('content')

<main class="signin-page form-page gogocares--form">
	<section class="screen signin-page form-page">
		<div class="item">
			<img src="{{ frontendAsset('/frontend/gallery/bg.png') }}" alt="">
		</div>

		<!-- absolute part -->
		<article class="gogocares--form__main">
			<div class="text__wrapper">
				<h2 class="section__title section__title--sm">Become our <br>Biker / Taxi Rider / Delivery Pilot</h2>
				<form action="./account-page.php">
					<article class="card__intro">
						<div class="img__wrapper">
							<div class="card__img">
								<img src="{{ frontendAsset('/frontend/gallery/avataaar.png') }}" alt="sanjay"
									id="ppImage">
								<input type="file" class="custom-file-input" id="ppImageInput" accept="image/*">
							</div>
						</div>
						<label for="ppImageInput"> Upload photo </label>
					</article>
					<div class="form-row">
						<div class="form-group col-6">
							<input placeholder="First Name" class="form-control" type="text">
						</div>
						<div class="form-group col-6">
							<input placeholder="Last Name" class="form-control" type="text">
						</div>
					</div>
					<div class="form-group">
						<select class="form-select">
							<option selected>Gender</option>
							<option value="1">Male </option>
							<option value="2">Female </option>
							<option value="3">Other </option>
						</select>
						<i class="material-icons ">unfold_more</i>
					</div>

					<div class="form-group">
						<div class="input-groups">
							<input placeholder="Password" class="form-control" type="password">
							<a href="#!" class="visibility">
								<span class="material-icons">
									visibility
								</span>
							</a>
						</div>
					</div>
					<div class="form-group">
						<input placeholder="Mobile Number" class="form-control" type="tel">
					</div>
					<div class="form-row box__wrapper">
						<label class="text-muted col-12">Intrested in</label>

						<div class="form-group col-6">
							<div class="form-check ">
								<input name="VeRadio" class="form-check-input" type="radio" value="" id="selectV1">
								<label class="list-group-item list-group-item-action form-check-label" for="selectV1">
									Bike
								</label>
							</div>
						</div>
						<div class="form-group col-6">
							<div class="form-check">
								<input name="VeRadio" class="form-check-input" type="radio" value="" id="selectV2">
								<label class="list-group-item list-group-item-action form-check-label" for="selectV2">
									Car
								</label>
							</div>
						</div>
					</div>

					<label class="text-muted"> How did you hear about gogo20?</label>
					<div class="form-group">

						<select class="form-select">
							<option selected="">Friends</option>
							<option value="1">Advertisement</option>
							<option value="2">Facebook</option>
							<option value="3">Other</option>
						</select>
						<i class="material-icons ">unfold_more</i>

					</div>




					<!-- <div class="form-row box__wrapper">
						<div class="form-group col-6">
							<div class="list-group-item">
								<div class="card__img">
									<img src="./gallery/car.png"  class="ppImage__label">
									<input type="file" class="custom-file-input" id="VecImageInput" accept="image/*">
								</div>
								<label for="VecImageInput">Upload Vehicle Picture</label>
							</div>
						</div>
						<div class="form-group col-6">

							<div class="list-group-item">

								<div class="card__img">
									<img src="./gallery/lisence.png" alt="sanjay" id="ppImage__label">
									<input type="file" class="custom-file-input" id="LicImageInput" accept="image/*">
								</div>
								<label for="LicImageInput">Upload License</label>
							</div>
						</div>
					</div> -->

					<!-- <div class="form-group">

						<select class="form-select" >
							<option selected>Color </option>
							<option value="1" >White  </option>
							<option value="2">Silver  </option>
							<option value="3">Black  </option>
							<option value="4">Med. Dark Blue   </option>
							<option value="5">Med. Dark Gray    </option>
							<option value="6">Med. Red   </option>
							<option value="7">Med. Dark Green   </option>
							<option value="8">Light Brown   </option>

						</select>
						<i class="material-icons ">unfold_more</i>

					</div>

					<div class="form-group">
						<div class="form-control labelBoth" >
							<label for="regNumber">Registration Number</label>
							<input  type="number" id="regNumber">
							
						</div>
					</div>

					<div class="form-group">
						<div class="form-control labelBoth" >
							<label for="Fuel">Fuel Sharing/Km</label>
							<input  type="number" id="Fuel">
							
						</div>
					</div>

					<div class="form-group">
						<div class="form-control labelBoth" >
							<label for="Checkpoint">Fuel Check Point Table</label>
							<input  type="number" id="Checkpoint">
							
						</div>
					</div>

					<div class="form-row box__wrapper">
						<label >Offering Seats</label>
						<div class="form-group col-auto">
							<div class="form-check ">
								<input name="offeringSeats" class="form-check-input" type="radio" value="" id="seats1">
								<label class="list-group-item list-group-item-action form-check-label" for="seats1">
									1
								</label>
							</div>
						</div>
						<div class="form-group col-auto">
							<div class="form-check">
								<input name="offeringSeats" class="form-check-input" type="radio" value="" id="seats2">
								<label class="list-group-item list-group-item-action form-check-label" for="seats2">
									2
								</label>
							</div>
						</div>
						<div class="form-group col-auto">
							<div class="form-check">
								<input name="offeringSeats" class="form-check-input" type="radio" value="" id="seats3">
								<label class="list-group-item list-group-item-action form-check-label" for="seats3">
									3
								</label>
							</div>
						</div>
						<div class="form-group col-auto">
							<div class="form-check">
								<input name="offeringSeats" class="form-check-input" type="radio" value="" id="seats4">
								<label class="list-group-item list-group-item-action form-check-label" for="seats4">
									4
								</label>
							</div>
						</div>
						<div class="form-group col-auto">
							<div class="form-check">
								<input name="offeringSeats" class="form-check-input" type="radio" value="" id="seats5">
								<label class="list-group-item list-group-item-action form-check-label" for="seats5">
									5
								</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<input placeholder="Features (eg. AC, Music, Video, WiFi etc.)" class="form-control" type="text">
					</div>
					<div class="form-group">
						<input placeholder="Remarks (If any)" class="form-control" type="text">
					</div>

					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="" id="accept" checked>
						<label class="form-check-label" for="accept">
							Mark as default vechicle
						</label>
					</div> -->
					<div class="form-group">
						<button type="submit" class="mb-0">Apply</button>
					</div>

				</form>

			</div>
			<div class="back">
				<button onclick="window.location.href='/'">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x"
						viewBox="0 0 16 16">
						<path
							d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
					</svg>
				</button>
			</div>
			</div>

		</article>

	</section>

</main>

@endsection

@push('script')
<script src="{{ frontendAsset('/frontend/node_modules/scroll-out/dist/scroll-out.js') }}"></script>
<script type="text/javascript" src="{{ frontendAsset('/frontend/js/jquery.js') }}"></script>
<script src="https://kit.fontawesome.com/021b940a03.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ frontendAsset('/frontend/js/commonjs.js') }}"></script>

<script>
	$(document).ready(function(e){
		// update image change
		function readURL(input) {
			console.log(input)
			let elem = input.previousElementSibling;
			console.log(elem);
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				
				reader.onload = function(e) {
					elem.setAttribute("src", e.target.result);

				}
				reader.readAsDataURL(input.files[0]); 
    			// convert to base64 string
    		}
    	}

    	$(".custom-file-input").change(function() {
    		readURL(this);
    	});

    	var i=0;
    	$('.input-groups a.visibility ').click(function(){
    		if(i===0){
    			$(this).find('span').html('visibility');
    			$(this).parent().find('input').attr('type','text');

    			i=1;

    		}
    		else{
    			$(this).find('span').html('visibility_off');
    			$(this).parent().find('input').attr('type','password');
    			i = 0;


    		}

    	});
    })
</script>

@endpush