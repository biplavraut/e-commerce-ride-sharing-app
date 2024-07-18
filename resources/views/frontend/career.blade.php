@extends('frontend.layouts.master')

<?php $currentpage = 'home';  ?>

@push('style')
<link rel="stylesheet" href="{{ frontendAsset('/frontend/css/otherstyle.css') }}">
@endpush

@section('title', 'Career')

@section('content')

<main class="signin-page form-page gogocares--form career-page">
	<section class="screen signin-page form-page">
		<div class="item">
			<img src="{{ frontendAsset('/frontend/gallery/bg.png') }}" alt="">
		</div>

		<!-- absolute part -->
		<article class="gogocares--form__main">
			<div class="section__rule">
				<h2 class="section__title ">Careers</h2>

				<form action="./index.php">

					<div class="steps active">
						<h2 class="section__title section__title--sm">Personal details</h2>
						<div class="form-row ">
							<div class="form-group col-sm-6">
								<input placeholder="First Name *" class="form-control" type="text">
							</div>
							<div class="form-group col-sm-6">
								<input placeholder="Last Name *" class="form-control" type="text">
							</div>
							<div class="form-group col-sm-6">
								<input placeholder="Mob No *" class="form-control" type="tel">
							</div>
							<div class="form-group col-sm-6">
								<input placeholder="Home No*" class="form-control" type="tel">
							</div>
							<div class="form-group col-sm-6">
								<select class="form-select form-control">
									<option value="1" selected="">Marital status *</option>
									<option value="2">Married</option>
									<option value="3">Single</option>

								</select>
								<i class="material-icons ">unfold_more</i>
							</div>
						</div>


						<hr>
						<h2 class="section__title section__title--sm">Address details</h2>
						<div class="form-row ">
							<div class="form-group col-sm-4">
								<input placeholder="Current City *" class="form-control" type="text">
							</div>
							<div class="form-group col-sm-4">
								<input placeholder="Permanent Address *" class="form-control" type="text">
							</div>
							<div class="form-group col-sm-4">
								<select id="Nationality" class="form-select form-control">
									<option value="5" disabled="" selected="">Country</option>
								</select>
								<i class="material-icons ">unfold_more</i>
							</div>

						</div>

						<hr>
						<h2 class="section__title section__title--sm">Professional details</h2>
						<div class="form-row ">
							<div class="form-group col-sm-6">
								<input placeholder="Current Business Title *" class="form-control" type="text">
							</div>
							<div class="form-group col-sm-6">
								<input placeholder="Current Organization Name (opt)" class="form-control" type="text">
							</div>
							<div class="form-group col-sm-6">
								<input placeholder="Current Salary *" class="form-control" type="text">
							</div>
							<div class="form-group col-sm-6">
								<input placeholder="Expected Salary *" class="form-control" type="text">
							</div>

						</div>

						<hr>

						<h2 class="section__title section__title--sm">Educational details</h2>
						<div class="form-row ">
							<div class="form-group col-sm-6">
								<input placeholder="Passed Level *" class="form-control" type="text">
							</div>
							<div class="form-group col-sm-6">
								<input placeholder="Passed Year *" class="form-control" type="text">
							</div>
							<div class="form-group col-sm-6">
								<input placeholder="University/ Board *" class="form-control" type="text">
							</div>
							<div class="form-group col-sm-6">
								<input placeholder="Persentage / Grade *" class="form-control" type="text">
							</div>
							<div class="form-group col-sm-6">
								<input placeholder="Name of Degree *" class="form-control" type="text">
							</div>
						</div>

					</div>



					<div class="steps">
						<h2 class="section__title section__title--sm">Do you have relevant experience into ride hailing,
							on-demand and last mile delivery or e-commerce related works? If yes, </h2>
						<div class="form-row ">
							<div class="form-group col-sm-4">
								<input placeholder="Company Name" class="form-control" type="text">
							</div>
							<div class="form-group col-sm-4">
								<input placeholder="Years of Experience" class="form-control" type="number">
							</div>
							<div class="form-group col-sm-4">
								<input placeholder="Job Title" class="form-control" type="text">
							</div>
						</div>
						<hr>
						<h2 class="section__title section__title--sm">You have license of : </h2>
						<div class="form-row box__wrapper">
							<div class="form-group col-4">
								<div class="form-check ">
									<input name="VeRadio" class="form-check-input" type="radio" value="" id="selectV1">
									<label class="list-group-item list-group-item-action form-check-label"
										for="selectV1">
										Two Wheeler
									</label>
								</div>
							</div>
							<div class="form-group col-4">
								<div class="form-check">
									<input name="VeRadio" class="form-check-input" type="radio" value="" id="selectV2">
									<label class="list-group-item list-group-item-action form-check-label"
										for="selectV2">
										Four Wheeler
									</label>
								</div>
							</div>
							<div class="form-group col-4">
								<div class="form-check">
									<input name="VeRadio" class="form-check-input" type="radio" value="" id="selectV3">
									<label class="list-group-item list-group-item-action form-check-label"
										for="selectV3">
										Both
									</label>
								</div>
							</div>

						</div>
						<hr>
						<h2 class="section__title section__title--sm">How soon can you join us if select you? </h2>
						<div class="form-row ">
							<div class="form-group col-sm-3">
								<select class="form-select form-control">
									<option value='1' selected>1 day </option>
									<?php
									for ($x = 2; $x <= 45; $x++) {
										echo "<option value='$x'>$x days </option>";
									}
									?>
								</select>
								<i class="material-icons ">unfold_more</i>
							</div>
						</div>

					</div>

					<div class="steps">
						<div class="form-row ">
							<div class="form-group col-sm-6">
								<div class="custom-file form-control">
									<input type="file" class="custom-file-input" id="upload--resume"
										accept="image/*,application/pdf">
									<label class="custom-file-label" for="upload--resume" id="resume__label">Resume (Max
										5mb) </label>
								</div>
							</div>
							<div class="form-group col-sm-6">
								<div class="custom-file form-control">
									<img src="{{ frontendAsset('/frontend/gallery/avataaar.png') }}" id="ppImage__label"
										class="ppImage__label">
									<input type="file" class="custom-file-input upload--pp--input" id="upload--pp"
										accept="image/*,application/pdf">
									<label class="custom-file-label upload--pp--label" for="upload--pp"
										id="upload--pp--label">Recent Photo </label>
								</div>
							</div>

						</div>
					</div>


					<div class="form-row mb-0 mt-4 justify-content-center">

						<button class="prev mb-0 ">Prev</button>
						<button class="mb-0 next">Next</button>

					</div>

				</form>

			</div>
			</div>

		</article>

	</section>

</main>

@endsection

@push('script')
<script src="{{ frontendAsset('/frontend/node_modules/scroll-out/dist/scroll-out.js') }}"></script>
<script type="text/javascript" src="{{ frontendAsset('/frontend/js/jquery.js') }}"></script>
<script>
	$(document).ready(function(e){
		// update image change
		$.ajax({
			url: "https://restcountries.eu/rest/v2/all"
		}).then(function(data) {
			$.each( data, function( key, elem ) {
				$("#Nationality").append($('<option>', {
					value: elem.alpha2Code,
					text: elem.name
				}));
			});
		});



		// $('.addEducation').click(function(e){
		// 	$('#collapseExample').toggle(300);
		// })

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
    		// readURL(this);
    		$(this).next().text(this.files.item(0).name);

    	});
    	$(".custom-file-input.upload--pp--input").change(function() {
    		readURL(this);
    	});

    	$('button.next').click(function(e){
    		e.preventDefault();
    		let activeElem = $('.steps:visible');
    		$('button.prev').show()
    		if(activeElem.next().hasClass('steps')){

    			activeElem.next().show('300');
    			activeElem.hide('300');
    			if(!activeElem.next().next().hasClass('steps')){
    				$(this).attr('type','submit');
    				$(this).text('submit')
    			}

    		}
    		
    	})

    	// prev
    	$('button.prev').click(function(e){
    		e.preventDefault();
    		let activeElem = $('.steps:visible');
    		$('button.next').removeAttr('type');
    		$('button.next').text('next');
    		if(activeElem.prev().hasClass('steps')){
    			activeElem.prev().show('300');
    			activeElem.hide('300');
    			if(!activeElem.prev().prev().hasClass('steps')){
    				$(this).hide()
    			}
    		}
    		
    	})

    })
</script>
@endpush