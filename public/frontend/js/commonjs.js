$(document).ready(function(){

	$('header .dropdown').hover(function(){
		$(this).find('.dropdown-menu').addClass('show');
	},
	function(){
		$(this).find('.dropdown-menu').removeClass('show');
	});

	// $('[data-toggle="tooltip"]').tooltip()
	const currentLocation = location.href;
	const menuItem = $('footer .nav .nav__item ');
	// console.log(menuItem)
	const menuLength = menuItem.length;
	for (let i = 0; i < menuLength; i++) {
		if (menuItem[i].children[0].href === currentLocation) {
			// console.log(menuItem[menuItem[i].children[0].href]);
			menuItem[i].classList.add('active')
		}
	}

	$('ul.nav .nav-item').click(function(e){
		this.scrollIntoView({behavior: "smooth", block: "center", inline: "start"});
	})


	
	

	// mobinput
	if($(window).width()<835){
		$('input:not([type="checkbox"]):not([type="radio"])').focusin(  
			function(){  
				$('footer').addClass('relative');  
			}).focusout(  
			function(){  
				$('footer').removeClass('relative');  
			});

			$('.hamburger-menu, header .nav__link').click(function(e){
				// e.preventDefault();
				$('header').toggleClass('active');
				$('.hamburger-menu').find('span').toggleClass('hide');
			})

			$('main').click(function(e){
				// e.preventDefault();
				if($('header').hasClass('active')){
					$('.hamburger-menu').find('span').toggleClass('hide');
				}
				$('header').removeClass('active');
				
			})



		}
		if($(window).width()>835){

			ScrollOut({
				targets:".service , .features .card, .green .card, .about-us .animated",
				once:true,
				threshold:.5,
				onShown(el){
					el.classList.add('fadeInUp');
				},
				onHidden(el){
					el.classList.remove('fadeInUp');
				}

			})


			ScrollOut({
				targets:".service .card",
				once:true,
				threshold:.5,
				onShown(el){
					el.classList.add('fadeInUp');
				},
				onHidden(el){
					el.classList.remove('fadeInUp');
				}

			})
			ScrollOut({
				targets:".app .left .card__title, .gogocares .left .img",
				once:true,
				threshold:.5,
				onShown(el){
					el.classList.add('fadeInLeft');
				},
				onHidden(el){
					el.classList.remove('fadeInLeft');
				}

			})
			ScrollOut({
				targets:".app .right .card__title, .gogocares .right .img",
				once:true,
				threshold:.5,
				onShown(el){
					el.classList.add('fadeInRight');
				},
				onHidden(el){
					el.classList.remove('fadeInRight');
				}

			})
		}







		$('.hamburger').click(function(e){
			if(!($('.main-menu').hasClass('active'))){
				$('.main-menu').addClass('active')
				$(this).find('span').text('close')
			}
			else{
				$('.main-menu').removeClass('active')
				$(this).find('span').text('menu')
			}
		})

		delete Hammer.defaults.cssProps.userSelect;

	});



