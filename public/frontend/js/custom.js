$(document).ready(function() {
    $('.carousel--single').slick({
        infinite: true,
        lazyLoad: 'ondemand',
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        autoplay: true,
        dots: false,
        speed: 3000,
        autoplaySpeed: 3000,
        pauseOnFocus:false,
        responsive: [{
                breakpoint: 600,
                settings: {
                    speed: 300,
                }
            },

        ]
    });
    $('.testimonials .carousel').slick({
        infinite: true,
        lazyLoad: 'ondemand',
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        autoplay: true,
        dots: true,
        prevArrow: `<button class='slick-prev'><svg xmlns="http://www.w3.org/2000/svg" width="16" height="10" viewBox="0 0 16 10">
		<path id="Path_23" data-name="Path 23" d="M3.2,6,5.7,8.3A.99.99,0,0,1,4.3,9.7L.4,6.1A2.1,2.1,0,0,1,0,5,2.1,2.1,0,0,1,.4,3.9L4.3.3A.967.967,0,0,1,5.7.3a.967.967,0,0,1,0,1.4L3.2,4H15a.945.945,0,0,1,1,1,.945.945,0,0,1-1,1Z" fill="#fff" fill-rule="evenodd"/>
		</svg>
		</button>`,
        nextArrow: `<button class='slick-next'><svg width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path fill-rule="evenodd" clip-rule="evenodd" d="M12.8 6L10.3 8.3C9.9 8.7 9.9 9.3 10.3 9.7C10.7 10.1 11.3 10.1 11.7 9.7L15.6 6.1C15.8 5.8 16 5.4 16 5C16 4.6 15.8 4.2 15.6 3.9L11.7 0.3C11.3 -0.1 10.7 -0.1 10.3 0.3C9.9 0.7 9.9 1.3 10.3 1.7L12.8 4H1C0.4 4 0 4.4 0 5C0 5.6 0.4 6 1 6H12.8Z" fill="#fff"></path>
		</svg></button>`,
    });

    $('.service .carousel').slick({
        infinite: false,
        lazyLoad: 'ondemand',
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: true,
        autoplay: false,
        dots: false,
        prevArrow: `<button class='slick-prev hide'><svg xmlns="http://www.w3.org/2000/svg" width="16" height="10" viewBox="0 0 16 10">
		<path id="Path_23" data-name="Path 23" d="M3.2,6,5.7,8.3A.99.99,0,0,1,4.3,9.7L.4,6.1A2.1,2.1,0,0,1,0,5,2.1,2.1,0,0,1,.4,3.9L4.3.3A.967.967,0,0,1,5.7.3a.967.967,0,0,1,0,1.4L3.2,4H15a.945.945,0,0,1,1,1,.945.945,0,0,1-1,1Z" fill="#fff" fill-rule="evenodd"/>
		</svg>
		</button>`,
        nextArrow: `<button class='slick-next'><svg width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path fill-rule="evenodd" clip-rule="evenodd" d="M12.8 6L10.3 8.3C9.9 8.7 9.9 9.3 10.3 9.7C10.7 10.1 11.3 10.1 11.7 9.7L15.6 6.1C15.8 5.8 16 5.4 16 5C16 4.6 15.8 4.2 15.6 3.9L11.7 0.3C11.3 -0.1 10.7 -0.1 10.3 0.3C9.9 0.7 9.9 1.3 10.3 1.7L12.8 4H1C0.4 4 0 4.4 0 5C0 5.6 0.4 6 1 6H12.8Z" fill="#fff"></path>
		</svg></button>`,
        responsive: [{
                breakpoint: 1025,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 836,
                settings: {
                    slidesToShow: 2.3,
                }
            },
            {
                breakpoint: 601,
                settings: {
                    slidesToShow: 1.4,
                }
            },
        ]
    });



    $('.live .carousel').on('afterChange', function(event, slick, currentSlide) {
        let singleN = $(this).find("article").length - 4;
        if ($(window).width() < 1025) {
            ++singleN;

        }


        $(this).find('.card').css('opacity', 1)
        if (currentSlide === singleN) {
            $(this).find('.slick-next').addClass('hide');
            $(this).find('.slick-prev').removeClass('hide');
        } else if (currentSlide === 0) {
            $(this).find('.slick-prev').addClass('hide');
            $(this).find('.slick-next').removeClass('hide');
        } else {
            $(this).find('.slick-prev').removeClass('hide');
            $(this).find('.slick-next').removeClass('hide');

        }

    });

    var access = $('.access .text__wrapper').outerHeight() + 'px';
    $('.access .card__img').css('height', access);

    if ($(window).width() < 835) {
        $('.app .text-right').removeClass('text-right');
        $('.app .card__title br').remove();
    }

    // $('.app .clipping--image img').scrollIntoView();


});



// window.addEventListener('load', (event) => {
//     (function() {

//         document.querySelector(".screen").addEventListener("mousemove", parallax);
//         document.querySelector(".screen").addEventListener("touchmove", parallax2);

//         const elems = document.querySelectorAll(".screen .item  img");

//         function parallax(e) {
//             // const elem = document.querySelector(".screen .item.slick-active img");

//             const speed = 30;
//             let x = (window.innerWidth - e.pageX * speed) / 100
//             let y = (window.innerHeight - e.pageY * speed) / 100
//                 // console.log(x)
//             if (x > 0) {
//                 x = 0;
//             } else if (x < -400) {
//                 x = -400;
//             }
//             elems.forEach((elem) => {
//                 elem.style.transform = `translateX(${x}px) `

//             })
//         }

//         function parallax2(e) {


//             const speed = 30;
//             const x = (window.innerWidth - e.touches[0].clientX * speed) / 100
//             const y = (window.innerHeight - e.touches[0].clientY * speed) / 100

//             elems.forEach((elem) => {
//                 elem.style.transform = `translateX(${x}px) `
//             })
//         }

//         // scroll into view

//         let appTitle = document.querySelectorAll('.app aside .card__title');
//         let appImage = document.querySelectorAll('.app .clipping--image img');

//         appTitle.forEach((elem, index) => {
//             elem.addEventListener('mouseover', (event) => {
//                 event.preventDefault();
//                 imgaeScroll(event, index);
//             })
//             const imgaeScroll = (event, index) => {
//                 appImage[index + 1].scrollIntoView({ behavior: "smooth", block: "center" });
//             }
//         })

//         if (window.innerWidth < 1290) {
//             appTitle.forEach((elem, index) => {
//                 elem.addEventListener('click', (event) => {
//                     event.preventDefault();
//                     imgaeScroll(event, index);
//                 })
//                 const imgaeScroll = (event, index) => {
//                     appImage[index + 1].scrollIntoView({ behavior: "smooth", block: "center" });
//                 }
//             })
//         }


//     })()
// });