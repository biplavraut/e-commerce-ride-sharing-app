@extends('frontend.layouts.master')

<?php $currentpage = 'home';  ?>

@section('title', 'Home')

@push('style')
<style>
	.company--colab .tab-content .d-flex {
		text-align: center;
		justify-content: center;
		flex-wrap: wrap;
		width: 70%;
		margin: 0 auto;
	}

	.company--colab .tab-content .d-flex img {
		height: 65px;
		width: auto;
		object-fit: contain;
		filter: grayscale(1);
		margin: 0 24px;
		margin-bottom: 24px;
		transform: translate3d(0, 0, 0);
		transition: none;
		width: 100px;
		height: 100px;
		border-radius: 10px;
	}
</style>
@endpush


@section('content')
<main>
	<!-- Notice Board Modal for roadblock notice showing on web -->
	<?php if(!empty($road_block_notice)){ ?>
	<div class="modal fade" id="RoadBlockModal" tabindex="-1" role="dialog" aria-labelledby="RoadBlockModalTitle"
		aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="RoadBlockModalTitle">{{ $road_block_notice['title'] }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<?php if ($road_block_notice['show_image_on_top'] == 1) { ?>
					<img class="img img-fluid" style="width: 100%;"
						src="{{ frontendAsset($road_block_notice['image']) }}" alt="">
					<div class="modal_desciption" style="font-size: 1rem;font-family: initial;color: #1289ef;">
						{{ $road_block_notice['description'] }}
					</div>
					<?php }else{ ?>
					<div class="modal_desciption" style="font-size: 1rem;font-family: initial;color: #1289ef;">
						{{ $road_block_notice['description'] }}
					</div>
					<img class="img img-fluid" style="width: 100%;"
						src="{{ frontendAsset($road_block_notice['image']) }}" alt="">
					<?php } ?>
				</div>
				<!-- <div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		<button type="button" class="btn btn-primary">Save changes</button>
		</div> -->
			</div>
		</div>
	</div>
	<?php } ?>
	<section class="screen">
		<div class="carousel carousel--single">
			@foreach ($sliders as $slider)
			<div class="item">
				<img src="{{ $slider->image }}" alt="">
				@if ($slider->slider_text)
				<article class="absolute__content">
					<div class="text__wrapper">
						<h2 class="section__title">{{ $slider->slider_text }}</h2>
					</div>
				</article>
				@endif
			</div>
			@endforeach
		</div>

		<!-- absolute part -->
		{{-- <div class="download__app absolute__content">
			<aside>
				<h2 class="card__title card__title--sm">User / Customer App</h2>
				<div class="download__button d-flex justify-content-center align-items-center">
					<a href="https://play.google.com/store/apps/details?id=com.gogo20.user" target="_blank"
						title="playstore">
						<img src="{{ frontendAsset('frontend/gallery/google.png') }}" alt="">
		</a>
		<a href="https://play.google.com/store/apps/details?id=com.gogo20.user" target="_blank" title="istore">
			<img src="{{ frontendAsset('frontend/gallery/ios.png') }}" alt="">
		</a>
		</div>
		</aside>
		<aside>
			<h2 class="card__title card__title--sm">Partner App</h2>
			<div class="download__button d-flex justify-content-center align-items-center">
				<a href="https://play.google.com/store/apps/details?id=com.gogo20.driver" target="_blank"
					title="playstore">
					<img src="{{ frontendAsset('frontend/gallery/google.png') }}" alt="">
				</a>
				<a href="https://play.google.com/store/apps/details?id=com.gogo20.driver" target="_blank"
					title="istore">
					<img src="{{ frontendAsset('frontend/gallery/ios.png') }}" alt="">
				</a>
			</div>
		</aside>
		</div> --}}

	</section>

	<section class="about-us" id="nav--about-us">
		<div class="section__rule">
			<div class="absolute__content">
				<h2 class="section__title">We are gogo20</h2>
				<p class="para animated " data-scroll>“Many Needs: It is your time to rest easy and leave all your
					worries to us! From transportation services to food and grocery deliveries, along with package
					deliveries and home repairs, gogo20 is your one-stop solution to your many everyday worries!” “Many
					Needs: It is your time to rest easy and leave all your worries to us! From transportation services
					to food and grocery deliveries, along with package deliveries and home repairs, gogo20 is your
					one-stop solution to your many everyday worries!”</p>
			</div>
			<div class="video__absolute">
				<video autoplay="" muted="" loop>
					<source src="{{ frontendAsset('frontend/gallery/bgvideo.mov') }}" type="video/mp4">
				</video>
			</div>
		</div>
	</section>


	<section class="service">
		<div class="section__rule">

			<h2 class="section__title">Single App for Everyday.Solution</h2>
			<h2 class="section__title section__title--sm">Ride Hailing Solutions</h2>
			<p class="title__description">gogo20 is your solution to navigate the complex world of Transportation! We as
				Nepalese working professionals, IIT alumni & IBM certified data scientists, are offering an unmatched
				and outstanding experience through Machine Learning (ML) base algorithms which brings the best match
				while you search for your destination. So you can relax while we put forward our best foot ahead to give
				you the world class services in personalized manners.
			</p>


			<div class="carousel">
				<article class="item">
					<div class="card animated " data-scroll>
						<div class="card__img">
							<img src="{{ frontendAsset('frontend/gallery/service/sv01.png') }}" alt="">
						</div>
						<div class="card__body">
							<h2 class="card__title">gogoCab</h2>
							<p class="para">Conveniently book a taxi or bike with the press of a button,
								at an affordable price, and it will arrive at your location!</p>
							<p class="readmore"><svg width="1em" height="1em" viewBox="0 0 16 16"
									class="bi bi-arrow-up-circle" fill="currentColor"
									xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd"
										d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
									<path fill-rule="evenodd"
										d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z" />
								</svg></p>
						</div>
					</div>
				</article>
				<article class="item">
					<div class="card animated" data-scroll>
						<div class="card__img">
							<div class="card__img">
								<img src="{{ frontendAsset('frontend/gallery/service/sv02.png') }}" alt="">
							</div>

						</div>
						<div class="card__body">
							<h2 class="card__title">gogoPool</h2>
							<p class="para">For our environment-conscious customers who share our beliefs in
								reducing carbon footprints, we offer carpool services where you
								only have to pay for every seat.</p>
							<p class="readmore"><svg width="1em" height="1em" viewBox="0 0 16 16"
									class="bi bi-arrow-up-circle" fill="currentColor"
									xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd"
										d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
									<path fill-rule="evenodd"
										d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z" />
								</svg></p>
						</div>
					</div>
				</article>
				<article class="item">
					<div class="card animated" data-scroll>
						<div class="card__img">
							<img src="{{ frontendAsset('frontend/gallery/service/sv03.png') }}" alt="">
						</div>

						<div class="card__body">
							<h2 class="card__title">gogoRent</h2>
							<p class="para">With thousands of registered vehicles ranging from mountain bikes to
								Jumbo trucks, we have every kind of vehicle you want. Rent it for
								a day, a few weeks or even a few months!</p>
							<p class="readmore"><svg width="1em" height="1em" viewBox="0 0 16 16"
									class="bi bi-arrow-up-circle" fill="currentColor"
									xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd"
										d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
									<path fill-rule="evenodd"
										d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z" />
								</svg></p>
						</div>
					</div>
				</article>


			</div>

			<!-- on demand -->
			<div class="live">
				<h2 class="section__title section__title--sm">On Demand Solutions</h2>
				<p class="title__description">We have developed one of the best solutions to offer you everyday
					goods at an affordable price, with various discounts, cash back and
					many other benefits!</p>
				<div class="carousel">
					<article class="item">
						<div class="card animated ">
							<div class="card__img">
								<img src="{{ frontendAsset('frontend/gallery/service/sv05.png') }}" alt="">
							</div>
							<div class="card__body">
								<h2 class="card__title">gogoEat</h2>
								<p class="para">Select from a wide number of restaurants and satellite kitchens. Come
									to us for a fine dining experience & we will book you a table at your
									favorite restaurant for a dine-out whenever you want!</p>
								<p class="readmore"><svg width="1em" height="1em" viewBox="0 0 16 16"
										class="bi bi-arrow-up-circle" fill="currentColor"
										xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd"
											d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
										<path fill-rule="evenodd"
											d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z" />
									</svg></p>
							</div>
						</div>
					</article>
					<article class="item">
						<div class="card animated ">
							<div class="card__img">
								<img src="{{ frontendAsset('frontend/gallery/service/sv06.png') }}" alt="">
							</div>
							<div class="card__body">
								<h2 class="card__title">gogoMart</h2>
								<p class="para">We bring grocery shopping at your fingertips. Order the most fresh
									vegetables, bakery items and so much more, at a reasonable price
									with free home delivery.</p>
								<p class="readmore"><svg width="1em" height="1em" viewBox="0 0 16 16"
										class="bi bi-arrow-up-circle" fill="currentColor"
										xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd"
											d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
										<path fill-rule="evenodd"
											d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z" />
									</svg></p>
							</div>
						</div>
					</article>
					<article class="item">
						<div class="card animated ">
							<div class="card__img">
								<img src="{{ frontendAsset('frontend/gallery/service/sv07.png') }}" alt="">
							</div>
							<div class="card__body">
								<h2 class="card__title">gogoMeat</h2>
								<p class="para">Choose from an assortment of meat products, with discounted price
									and cashback offers, and enjoy them at home, free of delivery charge.</p>
								<p class="readmore"><svg width="1em" height="1em" viewBox="0 0 16 16"
										class="bi bi-arrow-up-circle" fill="currentColor"
										xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd"
											d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
										<path fill-rule="evenodd"
											d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z" />
									</svg></p>
							</div>
						</div>
					</article>
					<article class="item">
						<div class="card animated ">
							<div class="card__img">
								<img src="{{ frontendAsset('frontend/gallery/service/sv08.png') }}" alt="">
							</div>
							<div class="card__body">
								<h2 class="card__title">gogoDrink</h2>
								<p class="para">We offer a medley of drinks from carbonated beverages, coffee,
									milkshakes
									to wines & craft beers, and everything in between! Pick your drink of choice and
									we’ll get them for you.</p>
								<p class="readmore"><svg width="1em" height="1em" viewBox="0 0 16 16"
										class="bi bi-arrow-up-circle" fill="currentColor"
										xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd"
											d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
										<path fill-rule="evenodd"
											d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z" />
									</svg></p>
							</div>
						</div>
					</article>
					<article class="item">
						<div class="card animated ">
							<div class="card__img">
								<img src="{{ frontendAsset('frontend/gallery/service/clean.png') }}" alt="">
							</div>
							<div class="card__body">
								<h2 class="card__title">gogoClean</h2>
								<p class="para">You can depend on us to provide various cleaning services for your
									homes- bringing your dry cleaning, helping with laundry, and many other sorts of
									assistance can be provided as per your need!</p>
								<p class="readmore"><svg width="1em" height="1em" viewBox="0 0 16 16"
										class="bi bi-arrow-up-circle" fill="currentColor"
										xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd"
											d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
										<path fill-rule="evenodd"
											d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z" />
									</svg></p>
							</div>
						</div>
					</article>
					<article class="item">
						<div class="card animated ">
							<div class="card__img">
								<img src="{{ frontendAsset('frontend/gallery/service/style.png') }}" alt="">
							</div>
							<div class="card__body">
								<h2 class="card__title">gogoStyle</h2>
								<p class="para">Buying your choice of garment has never been easier. We will bring you
									your favorite outfits and apparels, at a sensible price!</p>
								<p class="readmore"><svg width="1em" height="1em" viewBox="0 0 16 16"
										class="bi bi-arrow-up-circle" fill="currentColor"
										xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd"
											d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
										<path fill-rule="evenodd"
											d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z" />
									</svg></p>
							</div>
						</div>
					</article>
					<article class="item">
						<div class="card animated ">
							<div class="card__img">
								<img src="{{ frontendAsset('frontend/gallery/service/ee.png') }}" alt="">
							</div>
							<div class="card__body">
								<h2 class="card__title">gogoEE</h2>
								<p class="para">For all of your electrical needs and electronics goods, we are here to
									give you the best offers and discounts for the best quality products.</p>
								<p class="readmore"><svg width="1em" height="1em" viewBox="0 0 16 16"
										class="bi bi-arrow-up-circle" fill="currentColor"
										xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd"
											d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
										<path fill-rule="evenodd"
											d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z" />
									</svg></p>
							</div>
						</div>
					</article>
					<article class="item">
						<div class="card animated ">
							<div class="card__img">
								<img src="{{ frontendAsset('frontend/gallery/service/pro.svg') }}" alt="">
							</div>
							<div class="card__body">
								<h2 class="card__title">gogoPro</h2>
								<p class="para">You are having troubles in your bathroom, home? We help you to fix from
									plumbing to electrical and many more... Just download our app and let us know your
									home related problems.</p>
								<p class="readmore"><svg width="1em" height="1em" viewBox="0 0 16 16"
										class="bi bi-arrow-up-circle" fill="currentColor"
										xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd"
											d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
										<path fill-rule="evenodd"
											d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z" />
									</svg></p>
							</div>
						</div>
					</article>
				</div>
			</div>

			<!-- on last mile delivery -->
			<h2 class="section__title section__title--sm">Last Mile Delivery Solutions</h2>
			<p class="title__description">If you are a local vendor or if you run a business and need a platform to
				expand your services to unreached customers and locations, you can use us through our thousands of
				delivery pilots and directly book our numerous services ranging from food and grocery delivery to parcel
				and goods courier and diverse delivery options. With the help of our simple and easy UI, gogo20 is you
				single one-stop solution to your numerous problems!</p>
			<div class="carousel">
				<article class="item">
					<div class="card animated " data-scroll>
						<div class="card__img">
							<img src="{{ frontendAsset('frontend/gallery/service/sv04.png') }}" alt="">
						</div>
						<div class="card__body">
							<h2 class="card__title">gogoSend</h2>
							<p class="para">Need to deliver a package but too swamped with other things to do so? Rest
								easy and trust us, we will deliver your goods, parcels and courier, all at a single
								click!</p>
							<p class="readmore"><svg width="1em" height="1em" viewBox="0 0 16 16"
									class="bi bi-arrow-up-circle" fill="currentColor"
									xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd"
										d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
									<path fill-rule="evenodd"
										d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z" />
								</svg></p>
						</div>
					</div>
				</article>

			</div>
		</div>
	</section>


	<section class="app" id="download">
		<div class="section__rule p-0">
			<div class="text__wrapper center">
				<h2 class="section__title">App Features</h2>
				<p class="title__description	">gogo20 brings you the unmatched features to offer you the real solution
					in your EVERYDAY NEEDS. On top, you would get various discounts, cash back and exciting offers while
					using the gogo20 app for your EVERYDAY.SOLUTION
				</p>

			</div>
			<div class="d-flex justify-content-center align-items-center">
				<aside class="left text-right col-12 col-lg-3">
					<h2 class="card__title animated " data-scroll>Book Bike/Taxi Instantly <br>or Schedule for future

					</h2>
					<h2 class="card__title animated " data-scroll> Rent the wider range <br>of vehicles</h2>
					<h2 class="card__title animated " data-scroll>Get your accompany and fuel contribution in your car
						while you driving to any places

					</h2>

				</aside>
				<aside class="center  col-lg-6">
					<div class="img">
						<img src="{{ frontendAsset('frontend/gallery/Mobile.png') }}" alt="">
					</div>
					<div class="clipping--image">
						<img src="{{ frontendAsset('frontend/gallery/app/sc01.jpg') }}" alt="">
						<img src="{{ frontendAsset('frontend/gallery/app/sc02.jpg') }}" alt="">
						<img src="{{ frontendAsset('frontend/gallery/app/sc03.jpg') }}" alt="">
						<img src="{{ frontendAsset('frontend/gallery/app/sc04.jpg') }}" alt="">
						<img src="{{ frontendAsset('frontend/gallery/app/sc05.jpg') }}" alt="">
						<img src="{{ frontendAsset('frontend/gallery/app/sc06.jpg') }}" alt="">
						<img src="{{ frontendAsset('frontend/gallery/app/sc07.jpg') }}" alt="">
					</div>

				</aside>
				<aside class="right col-12 col-lg-3">
					<h2 class="card__title animated " data-scroll>Choose the wider range goods and services in your
						finger tips

					</h2>
					<h2 class="card__title animated " data-scroll> Free Delivery<br> at your doorstep</h2>
					<h2 class="card__title animated " data-scroll>AI Based<br>support</h2>
				</aside>
			</div>
			<div class="download__app">
				<aside>
					<h2 class="card__title card__title--sm">User/ Customer App</h2>
					<div class="download__button d-flex justify-content-center align-items-center">
						<a href="https://play.google.com/store/apps/details?id=com.gogo20.user" target="_blank"
							title="playstore">
							<img src="{{ frontendAsset('frontend/gallery/google.png') }}" alt="">
						</a>
						<a href="https://play.google.com/store/apps/details?id=com.gogo20.user" target="_blank"
							title="istore">
							<img src="{{ frontendAsset('frontend/gallery/ios.png') }}" alt="">
						</a>
					</div>
				</aside>
				<aside>
					<h2 class="card__title card__title--sm">Partner App</h2>
					<div class="download__button d-flex justify-content-center align-items-center">
						<a href="https://play.google.com/store/apps/details?id=com.gogo20.driver" target="_blank"
							title="playstore">
							<img src="{{ frontendAsset('frontend/gallery/google.png') }}" alt="">
						</a>
						<a href="https://play.google.com/store/apps/details?id=com.gogo20.driver" target="_blank"
							title="istore">
							<img src="{{ frontendAsset('frontend/gallery/ios.png') }}" alt="">
						</a>
					</div>
				</aside>
			</div>
		</div>
	</section>

	<section class="features">
		<div class="section__rule">
			<div class="row">
				<article class="col-10 col-md-3">
					<div class="card animated " data-scroll>
						<div class="card__img rounded-circle">
							<img src="{{ frontendAsset('frontend/gallery/ft01.png') }}" alt="">
						</div>
						<div class="card__body">
							<h2 class="card__title">Download, <br> Share & Earn </h2>
						</div>
					</div>
				</article>
				<article class="col-10 col-md-3">
					<div class="card animated " data-scroll>
						<div class="card__img  rounded-circle ">
							<img src="{{ frontendAsset('frontend/gallery/ft02.png') }}" alt="">
						</div>
						<div class="card__body">
							<h2 class="card__title">Upto 100% <br> Cashback</h2>
						</div>
					</div>
				</article>
				<article class="col-10 col-md-3">
					<div class="card animated " data-scroll>
						<div class="card__img  rounded-circle ">
							<img src="{{ frontendAsset('frontend/gallery/ft03.png') }}" alt="">
						</div>
						<div class="card__body">
							<h2 class="card__title">Discount upto <br> 60%</h2>
						</div>
					</div>
				</article>
				<article class="col-10 col-md-3">
					<div class="card animated " data-scroll>
						<div class="card__img  rounded-circle ">
							<img src="{{ frontendAsset('frontend/gallery/ft01.png') }}" alt="">
						</div>
						<div class="card__body">
							<h2 class="card__title">Top picks for <br> you</h2>
						</div>
					</div>
				</article>
			</div>

		</div>
	</section>

	<section class="gogocares" id="gogocares">
		<div class="text__wrapper center">
			<h2 class="section__title">gogo20 Cares for you and <br> your business</h2>
		</div>
		<div class="row no-gutters align-items-center">
			<aside class="col-md-6 left col-3">
				<div class="img animated " data-scroll>
					<img src="{{ frontendAsset('frontend/gallery/gogocares03.png') }}" alt="">
				</div>
			</aside>
			<aside class="col-md-6 col-9">
				<div class="text__wrapper">
					<h2 class="section__title section__title--sm">Become our <br>Biker / Taxi Rider / Delivery Pilot
					</h2>
					<ul>
						<li>You can deliver in your city</li>
						<li>You are the boss of your own locality and city</li>
						<li>You can work when you like</li>
						<li>You are freedom fighter</li>
						<li>You can earn upto 50,000/Mon</li>
					</ul>
					<button onclick="window.location.href='/register#tab3'">Apply Now</button>
				</div>

			</aside>
			<aside class="col-md-6 col-9">
				<div class="text__wrapper">
					<h2 class="section__title section__title--sm">Become our Seller / Vendor Now
					</h2>
					<ul>
						<li>Share your basic information through our sign up form
						</li>
						<li>Get it approved from our system</li>
						<li>Attend a short partner induction course and certificate</li>
						<li>Start and grow your business now</li>
						<li>No limit for earning</li>
					</ul>
					<button onclick="window.location.href='/register#tab1'">Apply Now</button>
				</div>
			</aside>
			<aside class="col-md-6 right col-3">
				<div class="img animated " data-scroll>
					<img src="{{ frontendAsset('frontend/gallery/gogocares04.png') }}" alt="">
				</div>
			</aside>
			<aside class="col-md-6 left col-3">
				<div class="img animated " data-scroll>
					<img src="{{ frontendAsset('frontend/gallery/gogocares05.png') }}" alt="">
				</div>
			</aside>
			<aside class="col-md-6 col-9">
				<div class="text__wrapper">
					<h2 class="section__title section__title--sm">Become our <br>gogoFranchisee Now</h2>
					<ul>
						<li>Share your basic information through our sign up form
						</li>
						<li>Get it approved from our system</li>
						<li>Attend a short partner induction course and certificate</li>
						<li>Start and grow your business now by being as an Exclusive Partner in your territory</li>
						<li>No limit for earning</li>
					</ul>
					<button onclick="window.location.href='/register#tab1'">Apply Now</button>
				</div>
			</aside>
		</div>
	</section>

	<section class="ktm-temple-img">
		<div class="section__rule pb-0 w-100">
			<img src="{{ frontendAsset('frontend/gallery/ktm.png') }}" alt="" class="img-fluid">
		</div>

	</section>

	<section class="green">
		<div class="section__rule p-0">
			<div class="text__wrapper center">
				<h2 class="section__title">Journey So Far</h2>
			</div>
			<div class="row justify-content-center">
				<article>
					<div class="card card__hr animated " data-scroll>
						<div class="card__img">
							<img src="{{ frontendAsset('frontend/gallery/gg01.png') }}" alt="">
						</div>
						<div class="card__body">
							<p class="para">Total 10 cities</p>
						</div>
					</div>
				</article>
				<article>
					<div class="card card__hr animated " data-scroll>
						<div class="card__img">
							<img src="{{ frontendAsset('frontend/gallery/gg01.png') }}" alt="">
						</div>
						<div class="card__body">
							<p class="para">
								<p class="para">Kathmandu</p>
						</div>
					</div>
				</article>
				<article>
					<div class="card card__hr animated " data-scroll>
						<div class="card__img">
							<img src="{{ frontendAsset('frontend/gallery/gg01.png') }}" alt="">
						</div>
						<div class="card__body">
							<p class="para">
								<p class="para">Bhaktapur</p>
						</div>
					</div>
				</article>
				<article>
					<div class="card card__hr animated " data-scroll>
						<div class="card__img">
							<img src="{{ frontendAsset('frontend/gallery/gg01.png') }}" alt="">
						</div>
						<div class="card__body">
							<p class="para">
								<p class="para">Lalitpur</p>
						</div>
					</div>
				</article>
				<article>
					<div class="card card__hr animated " data-scroll>
						<div class="card__img">
							<img src="{{ frontendAsset('frontend/gallery/gg01.png') }}" alt="">
						</div>
						<div class="card__body">
							<p class="para">
								<p class="para">Nuwakot</p>
						</div>
					</div>
				</article>
				<article>
					<div class="card card__hr animated " data-scroll>
						<div class="card__img">
							<img src="{{ frontendAsset('frontend/gallery/gg01.png') }}" alt="">
						</div>
						<div class="card__body">
							<p class="para">
								<p class="para">Dhulikhel</p>
						</div>
					</div>
				</article>
			</div>
		</div>
	</section>

	<section class="green">
		<div class="section__rule">
			<div class="text__wrapper center">
				<h2 class="section__title">We Love Green</h2>
			</div>
			<div class="row justify-content-center">
				<article>
					<div class="card card__hr animated " data-scroll>
						<div class="card__img">
							<img src="{{ frontendAsset('frontend/gallery/gg01.png') }}" alt="">
						</div>
						<div class="card__body">
							<p class="para">gogo<b>U</b>sers</p>
							<h2 class="card__title">9K +</h2>
						</div>
					</div>
				</article>
				<article>
					<div class="card card__hr animated " data-scroll>
						<div class="card__img">
							<img src="{{ frontendAsset('frontend/gallery/gg02.png') }}" alt="">
						</div>
						<div class="card__body">
							<p class="para">
								<p class="para">gogo<b>P</b>artners</p>
								<h2 class="card__title">3K + </h2>
						</div>
					</div>
				</article>
				<article>
					<div class="card card__hr animated " data-scroll>
						<div class="card__img">
							<img src="{{ frontendAsset('frontend/gallery/gg01.png') }}" alt="">
						</div>
						<div class="card__body">
							<p class="para">
								<p class="para">gogo<b>O</b>rders</p>
								<h2 class="card__title">25K +</h2>
						</div>
					</div>
				</article>
				<article>
					<div class="card card__hr animated " data-scroll>
						<div class="card__img">
							<img src="{{ frontendAsset('frontend/gallery/gg02.png') }}" alt="">
						</div>
						<div class="card__body">
							<p class="para">
								<p class="para">gogo<b>R</b>ides</p>
								<h2 class="card__title">25K +</h2>
						</div>
					</div>
				</article>
				<article>
					<div class="card card__hr animated " data-scroll>
						<div class="card__img">
							<img src="{{ frontendAsset('frontend/gallery/gg01.png') }}" alt="">
						</div>
						<div class="card__body">
							<p class="para">
								<p class="para">gogo<b>P</b>ools</p>
								<h2 class="card__title">9K +</h2>
						</div>
					</div>
				</article>
				<article>
					<div class="card card__hr animated " data-scroll>
						<div class="card__img">
							<img src="{{ frontendAsset('frontend/gallery/gg01.png') }}" alt="">
						</div>
						<div class="card__body">
							<p class="para">
								<p class="para">CO2 <b>P</b>revented</p>
								<h2 class="card__title">3K +</h2>
						</div>
					</div>
				</article>
			</div>
		</div>
	</section>

	<section class="company--colab">
		<div class="section__rule">
			<div class="text__wrapper center">
				<h2 class="section__title">Company Collaboration</h2>
				<p class="title__description	">We are proud to have most trusted companies as our channel partners
					across Nepal.
				</p>

			</div>
			{{-- <ul class="nav " role="tablist">
				<li class="nav-item">
					<a class="active nav-link card__title" data-toggle="tab" href="#tab1" role="tab">
						All

					</a>
				</li>
				<li class="nav-item">
					<a class="card card__hr nav-link card__title " data-toggle="tab" href="#tab2">
						gogoFood
					</a>
				</li>
				<li class="nav-item">
					<a class="card card__hr nav-link card__title" data-toggle="tab" href="#tab3" role="tab">
						gogoMart
					</a>
				</li>
				<li class="nav-item">
					<a class="card card__hr nav-link card__title" data-toggle="tab" href="#tab4" role="tab">
						gogoStyle
					</a>
				</li>
				<li class="nav-item">
					<a class="card card__hr nav-link card__title" data-toggle="tab" href="#tab4" role="tab">
						gogoHealth
					</a>
				</li>
				<li class="nav-item">
					<a class="card card__hr nav-link card__title" data-toggle="tab" href="#tab4" role="tab">
						gogoDrink
					</a>
				</li>
				<li class="nav-item">
					<a class="card card__hr nav-link card__title" data-toggle="tab" href="#tab4" role="tab">
						gogoEE
					</a>
				</li>
			</ul> --}}
			<div class="tab-content">
				<div class="tab-pane fade show active" id="tab1" role="tabpanel">
					<div class="d-flex ">
						@foreach ($partners as $partner)
						<img src="{{ $partner->image}}" alt="{{ $partner->vendor->business_name }}"
							title="{{ $partner->vendor->business_name }}">
						@endforeach
					</div>

				</div>
				<div class="tab-pane fade" id="tab2" role="tabpanel">
					<div class="d-flex ">
						<img src="{{ frontendAsset('frontend/gallery/brand/gogopartner.png') }}" alt="">
						<img src="{{ frontendAsset('frontend/gallery/brand/gogopartner.png') }}" alt="">
						<img src="{{ frontendAsset('frontend/gallery/brand/gogopartner.png') }}" alt="">
						<img src="{{ frontendAsset('frontend/gallery/brand/gogopartner.png') }}" alt="">
					</div>
				</div>
				<div class="tab-pane fade" id="tab3" role="tabpanel">
					<div class="d-flex ">
						<img src="{{ frontendAsset('frontend/gallery/brand/gogopartner.png') }}" alt="">
					</div>
				</div>
				<div class="tab-pane fade" id="tab4" role="tabpanel">
					<div class="d-flex ">
						<img src="{{ frontendAsset('frontend/gallery/brand/gogopartner.png') }}" alt="">
						<img src="{{ frontendAsset('frontend/gallery/brand/gogopartner.png') }}" alt="">
						<img src="{{ frontendAsset('frontend/gallery/brand/gogopartner.png') }}" alt="">
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="testimonials">
		<div class="section__rule">
			<div class="bg__wrapper">
				<div class="carousel ">
					<div class="item">
						<div class="d-flex">
							<div class="image">
								<img src="{{ frontendAsset('frontend/gallery/gogocares02.png') }}" alt="">
							</div>
							<aside class="right">
								<div class="card">
									<div class="card__img">
										<img src="{{ frontendAsset('frontend/gallery/fingerprint.png') }}">
									</div>
									<h2 class="card__title">
										" I had no idea about rides, food, shopping, and delivery until I knew about
										gogo20. It helped me a lot with everyday problems. "
									</h2>
								</div>
								<div class="float-widget">
									<h4 class="card__title">462+ <br> Rides </h4>
								</div>
							</aside>
						</div>
					</div>
					<div class="item">
						<div class="d-flex">
							<div class="image">
								<img src="{{ frontendAsset('frontend/gallery/gogocares01.png') }}" alt="">
							</div>
							<aside class="right">
								<div class="card">
									<div class="card__img">
										<img src="{{ frontendAsset('frontend/gallery/fingerprint.png') }}">
									</div>
									<h2 class="card__title">
										" I had no idea about rides, food, shopping, and delivery until I knew about
										gogo20. It helped me a lot with everyday problems. "
									</h2>
								</div>
								<div class="float-widget">
									<h4 class="card__title">462+ <br> Rides </h4>
								</div>
							</aside>
						</div>
					</div>
					<div class="item">
						<div class="d-flex">
							<div class="image">
								<img src="{{ frontendAsset('frontend/gallery/gogocares01.png') }}" alt="">
							</div>
							<aside class="right">
								<div class="card">
									<div class="card__img">
										<img src="{{ frontendAsset('frontend/gallery/fingerprint.png') }}">
									</div>
									<h2 class="card__title">
										" I had no idea about rides, food, shopping, and delivery until I knew about
										gogo20. It helped me a lot with everyday problems. "
									</h2>
								</div>
								<div class="float-widget">
									<h4 class="card__title">462+ <br> Rides </h4>
								</div>
							</aside>
						</div>
					</div>
				</div>
			</div>
		</div>

	</section>

	<!-- faq -->
	<section class="faq" id="faq">
		<div class="section__rule">
			<h2 class="section__title">Top FAQ's of gogo20 </h2>
			<!-- donot add collapsed class on a if its active ( theres only one of it at a time) -->
			<div class="accordion" id="accordionExample">
				@foreach ($faqs as $key => $faq)
				<div>
					<!-- here no collapsed class -->
					<a data-toggle="collapse" href="#collapse<?php echo $faq->id; ?>"
						class="card__title <?php if($key > 0){ echo "collapsed"; } ?>"
						<?php if($key > 0){ echo "aria-expanded='false'"; } ?>>
						{{ $faq->faq_title }}<div class="icon"><span>+</span><span>-</span></div>
					</a>
					<!--only  here show class -->
					<div id="collapse<?php echo $faq->id; ?>" class="collapse <?php if($key == 0){ echo "show"; } ?>"
						data-parent="#accordionExample">
						<div class="para">
							{{ $faq->faq_description }}
						</div>
					</div>
				</div>
				@endforeach
				<!-- <div>
					<a data-toggle="collapse" href="#collapse5" class="collapsed card__title" aria-expanded="false">
						What do I get the support in case of any problem?<div class="icon"><span>+</span><span>-</span></div>
					</a>
					<div id="collapse5" class="collapse" data-parent="#accordionExample" style="">
						<div class="para">
							We have enforced the AI system which would help to resolve most of your problems. Besides this, you can
							write your queries to us and even call directly to our call center.
						</div>
					</div>
					<a href="#!" class="card__action active">View all</a>
				</div> -->
			</div>
			<a href="/faq" class="card__action active">View all</a>
		</div>
		</div>
		</div>
	</section>

	<!-- news room -->
	{{-- <section class="news">
		<div class="section__rule pt-0">
			<h2 class="section__title">gogoNewsroom</h2>
			<div class="row">
				<article class="col-md-4 col-10">
					<div class="card">
						<div class="card__img">
							<img src="{{ frontendAsset('frontend/gallery/gogocares01.png') }}" alt="">
	</div>
	<div class="card__body">
		<h2 class="card__title">Media Coverage</h2>
		<p class="para">Lorem ipsum dolor, sit amet consectetur adipisicing, elit. Ad minima, veritatis nostrum! Ut
			magnam distinctio pariatur necessitatibus facilis libero voluptatum. Nobis veritatis incidunt vero, numquam
			vel tempora eveniet pariatur dolores!</p>
		<a href="template-page.php" class="card__action active">View all</a>
	</div>
	</div>
	</article>
	<article class="col-md-4 col-10">
		<div class="card">
			<div class="card__img">
				<img src="{{ frontendAsset('frontend/gallery/gogocares02.png') }}" alt="">
			</div>
			<div class="card__body">
				<h2 class="card__title">News & Press Release</h2>
				<p class="para">Lorem ipsum dolor, sit amet consectetur adipisicing, elit. Ad minima, veritatis nostrum!
					Ut magnam distinctio pariatur necessitatibus facilis libero voluptatum. Nobis veritatis incidunt
					vero, numquam vel tempora eveniet pariatur dolores!</p>
				<a href="template-page.php" class="card__action active">View all</a>
			</div>
		</div>
	</article>
	<article class="col-md-4 col-10">
		<div class="card">
			<div class="card__img">
				<img src="{{ frontendAsset('frontend/gallery/gogocares01.png') }}" alt="">
			</div>
			<div class="card__body">
				<h2 class="card__title">Blogs</h2>
				<p class="para">Lorem ipsum dolor, sit amet consectetur adipisicing, elit. Ad minima, veritatis nostrum!
					Ut magnam distinctio pariatur necessitatibus facilis libero voluptatum. Nobis veritatis incidunt
					vero, numquam vel tempora eveniet pariatur dolores!</p>
				<a href="template-page.php" class="card__action active">View all</a>
			</div>
		</div>
	</article>
	</div>

	</div>
	</section> --}}

</main>
@endsection
@push('script')
<script src="{{ frontendAsset('frontend/node_modules/scroll-out/dist/scroll-out.js') }}"></script>
<script type="text/javascript" src="{{ frontendAsset('frontend/js/custom.js') }}"></script>

<script>
	$(document).ready(function() {
		var today = new Date();
		var dd = String(today.getDate()).padStart(2, '0');
		var mm = String(today.getMonth() + 1).padStart(2, '0');
		var yyyy = today.getFullYear();

		today = mm + '/' + dd + '/' + yyyy;

		if(localStorage.getItem('popState2') != 'shown'){
			$('#RoadBlockModal').modal('toggle');
			localStorage.setItem('popState2','shown')
			
			localStorage.setItem('popStateTime', today)
		}else{
			if(localStorage.getItem('popStateTime') != today){
				$('#RoadBlockModal').modal('toggle');
				localStorage.setItem('popStateTime', today)
			}
		}
		
		window.mobileAndTabletCheck = function() {
			let check = false;
			(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
			return check;
		};
		if(mobileAndTabletCheck() == true){
			window.location.replace('gogo20://');
			//setTimeout(window.location.replace('https://play.google.com/store/apps/details?id=com.gogo20.user&hl=en&gl=US'), 250);
		}	
	});

</script>
@endpush