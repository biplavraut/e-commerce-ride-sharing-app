$(document).ready(()=>{
	const progressBar = $(".js-progress-bar");
	// console.log(progressBar)
	progressBar.each(function(index, elem){
		let newElem = $(elem);
		// console.log(newElem);
		let percentageComplete = newElem.attr('profile-complete');
		// console.log(percentageComplete)
		let strokeDashasetValue = 100 - percentageComplete ;
		newElem.css("stroke-dasharray", `110 ${strokeDashasetValue}`);
	})


	const pageName = (function () {
		var a = window.location.href,
		b = a.lastIndexOf("/");
		return a.substr(b + 1);
	}());

	
	$('.dropdown-toggle').dropdown()

	$(`.left .dash_nav .nav-item .nav-link[href="./${pageName}"]`).addClass('active')
	

	// carousel
	$('.courses--for--you .carousel, .courses--for--you--2 .carousel').slick({
		infinite: false,
		lazyLoad: 'ondemand',
		slidesToShow: 2.5,
		slidesToScroll: 1,
		arrows:true,
		autoplay:false,	
		dots:false,
		prevArrow:`<button class='slick-prev'><svg width="11" height="18" viewBox="0 0 11 18"><path d="M9.00802 18C9.27059 18 9.53315 17.899 9.73513 17.697C10.1391 17.2931 10.1391 16.6468 9.73513 16.2496L2.4843 8.99874L9.72839 1.74791C10.1323 1.34397 10.1323 0.697653 9.72839 0.30044C9.32445 -0.0967734 8.67813 -0.103506 8.28092 0.30044L0.302991 8.27837C-0.100955 8.68232 -0.100955 9.32863 0.302991 9.72584L8.28092 17.7038C8.48289 17.9057 8.74546 18 9.00802 18Z" /></svg></button>`,
		nextArrow:`<button class='slick-next active'> <svg width="11" height="18" viewBox="0 0 11 18" xmlns="http://www.w3.org/2000/svg">
		<path d="M1.03006 18C0.767497 18 0.504932 17.899 0.302959 17.697C-0.100986 17.2931 -0.100986 16.6468 0.302959 16.2496L7.55379 8.99874L0.309692 1.74791C-0.094254 1.34397 -0.094254 0.697653 0.309692 0.30044C0.713638 -0.0967734 1.35995 -0.103506 1.75716 0.30044L9.7351 8.27837C10.139 8.68232 10.139 9.32863 9.7351 9.72584L1.75716 17.7038C1.55519 17.9057 1.29263 18 1.03006 18Z" />
		</svg>
		</button>`,
		responsive: [
		{
			breakpoint: 836,
			settings: {
				slidesToShow: 1.3,
			}
		},
		]
	});
	$('.courses--for--you--lg .carousel').slick({
		infinite: true,
		lazyLoad: 'ondemand',
		slidesToShow: 4,
		slidesToScroll: 1,
		arrows:true,
		autoplay:false,	
		dots:false,
		prevArrow:`<button class='slick-prev'><svg width="11" height="18" viewBox="0 0 11 18"><path d="M9.00802 18C9.27059 18 9.53315 17.899 9.73513 17.697C10.1391 17.2931 10.1391 16.6468 9.73513 16.2496L2.4843 8.99874L9.72839 1.74791C10.1323 1.34397 10.1323 0.697653 9.72839 0.30044C9.32445 -0.0967734 8.67813 -0.103506 8.28092 0.30044L0.302991 8.27837C-0.100955 8.68232 -0.100955 9.32863 0.302991 9.72584L8.28092 17.7038C8.48289 17.9057 8.74546 18 9.00802 18Z" /></svg>Previous</button>`,
		nextArrow:`<button class='slick-next active'>Next<svg width="11" height="18" viewBox="0 0 11 18" xmlns="http://www.w3.org/2000/svg">
		<path d="M1.03006 18C0.767497 18 0.504932 17.899 0.302959 17.697C-0.100986 17.2931 -0.100986 16.6468 0.302959 16.2496L7.55379 8.99874L0.309692 1.74791C-0.094254 1.34397 -0.094254 0.697653 0.309692 0.30044C0.713638 -0.0967734 1.35995 -0.103506 1.75716 0.30044L9.7351 8.27837C10.139 8.68232 10.139 9.32863 9.7351 9.72584L1.75716 17.7038C1.55519 17.9057 1.29263 18 1.03006 18Z" />
		</svg>
		</button>`,
		responsive: [
		{
			breakpoint: 836,
			settings: {
				slidesToShow: 1.3,
			}
		},
		]
	});

	var coursesN= $( ".courses--for--you .slick-track article").length - $( ".courses--for--you .slick-track article.slick-active").length;
	// console.log(coursesN)
	$('.courses--for--you .carousel').on('afterChange', function (event, slick, currentSlide) {
		// console.log(currentSlide)
		if(currentSlide === coursesN) {
			$(this).find('.slick-next').removeClass('active');
			$(this).find('.slick-prev').addClass('active');
			$('.courses--for--you .slick-list').addClass('active');
			$('.courses--for--you .slick-next').click(()=>{
				$('.courses--for--you .carousel').slick("slickGoTo", 0);
			})
		}
		else{
			$(this).find('.slick-next').addClass('active');
			$(this).find('.slick-prev').removeClass('active');	
			$('.courses--for--you .slick-list').removeClass('active');
		}
		
	});

	var singleN= $( ".courses--for--you--2 .slick-track article").length - $( ".courses--for--you--2 .slick-track article.slick-active").length;
	$('.courses--for--you--2 .carousel').on('afterChange', function (event, slick, currentSlide) {
		
		if(currentSlide === singleN) {
			$(this).find('.slick-next').removeClass('active');
			$(this).find('.slick-prev').addClass('active');
			$('.courses--for--you--2 .slick-list').addClass('active');
			$('.courses--for--you--2 .slick-next').click(()=>{
				$('.courses--for--you--2 .carousel').slick("slickGoTo", 0);
			})
		}
		else{
			$(this).find('.slick-next').addClass('active');
			$(this).find('.slick-prev').removeClass('active');	
			$('.courses--for--you--2 .slick-list').removeClass('active');

		}
		
	});
})