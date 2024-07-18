@extends('frontend.layouts.master')

<?php $currentpage = 'home';  ?>

@push('style')
<link rel="stylesheet" href="{{ frontendAsset('/frontend/css/otherstyle.css') }}">

<style>
	.inner__screen {
		background-image: url("/frontend/gallery/bg.png");
	}

	b {
		color: #4e4e4e;
	}
</style>

@endpush

@section('title', 'FAQ - GoGo20')

@section('content')

<main class=" terms-page inner-page ">
	<section class="inner__screen">
		<div class="section__rule">
			<h2 class="section__title">Frequently Asked Questions (FAQ) </h2>
		</div>
	</section>
	<section class="faq" id="faq">
		<div class="section__rule">
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
			</div>
		</div>
	</section>
</main>

@endsection

@push('script')
<script src="{{ frontendAsset('/frontend/node_modules/scroll-out/dist/scroll-out.js') }}"></script>
<script type="text/javascript" src="{{ frontendAsset('/frontend/js/jquery.js') }}"></script>
<script type="text/javascript" src="{{ frontendAsset('/frontend/js/parallax.js') }}"></script>

@endpush