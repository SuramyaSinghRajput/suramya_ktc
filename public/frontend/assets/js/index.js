
window.addEventListener('resize', function(){
    addRequiredClass();
})


function addRequiredClass() {
    if (window.innerWidth < 860) {
        document.body.classList.add('mobile')
    } else {
        document.body.classList.remove('mobile') 
    }
}

window.onload = addRequiredClass

let hamburger = document.querySelector('.hamburger')
let mobileNav = document.querySelector('.nav-list')

let bars = document.querySelectorAll('.hamburger span')

let isActive = false

hamburger.addEventListener('click', function() {
    mobileNav.classList.toggle('open')
    if(!isActive) {
        bars[0].style.transform = 'rotate(45deg)'
        bars[1].style.opacity = '0'
        bars[2].style.transform = 'rotate(-45deg)'
        isActive = true
		$('body').addClass('menu-open')
    } else {
        bars[0].style.transform = 'rotate(0deg)'
        bars[1].style.opacity = '1'
        bars[2].style.transform = 'rotate(0deg)'
        isActive = false
		$('body').removeClass('menu-open')
    }
    

})

$(document).ready(function () {
$(".slider")
	.slick({
		autoplay: true,
		speed: 800,
		lazyLoad: "progressive",
		arrows: false,
		dots: false,
		autoplay: true,
		prevArrow:
			'<div class="slick-nav prev-arrow"><i></i><svg><use xlink:href="#circle"></svg></div>',
		nextArrow:
			'<div class="slick-nav next-arrow"><i></i><svg><use xlink:href="#circle"></svg></div>',
			responsive: [
				{
				  breakpoint: 767,
				  settings: {
					arrows: false,
				  }
				},
			]
		})});

$(document).ready(function () {
$(".slider1")
	.slick({
		// autoplay: true,
		speed: 800,
		slidesToShow: 3,
		lazyLoad: "progressive",
		arrows: false,
		dots: true,
		// autoplay: true,
		prevArrow:
			'<div class="slick-nav prev-arrow"><i></i><svg><use xlink:href="#circle"></svg></div>',
		nextArrow:
			'<div class="slick-nav next-arrow"><i></i><svg><use xlink:href="#circle"></svg></div>',
			responsive: [
				{
				  breakpoint: 767,
				  settings: {
					slidesToShow: 1,
					arrows: false,
				  }
				},
			]
		})});

$(document).ready(function () {
	$(".slider2")
		.slick({
			autoplay: true,
			speed: 800,
			slidesToShow: 4.5,
			lazyLoad: "progressive",
			arrows: false,
			dots: true,
			autoplay: false,
			infinite: false,
			prevArrow:
				'<div class="slick-nav prev-arrow"><i></i><svg><use xlink:href="#circle"></svg></div>',
			nextArrow:
				'<div class="slick-nav next-arrow"><i></i><svg><use xlink:href="#circle"></svg></div>',
				responsive: [
					{
					  breakpoint: 767,
					  settings: {
						slidesToShow: 1,
					  }
					},
				]
			})});

	$(document).ready(function () {
	$(".slider3")
		.slick({
			autoplay: true,
			speed: 800,
			slidesToShow: 5,
			lazyLoad: "progressive",
			arrows: false,
			dots: true,
			autoplay: false,
			infinite: false,
			prevArrow:
				'<div class="slick-nav prev-arrow"><i></i><svg><use xlink:href="#circle"></svg></div>',
			nextArrow:
				'<div class="slick-nav next-arrow"><i></i><svg><use xlink:href="#circle"></svg></div>',
				responsive: [
					{
					  breakpoint: 767,
					  settings: {
						slidesToShow: 1,
					  }
					},
				]
	})});
	$(document).ready(function () {
	$(".slider4")
		.slick({
			autoplay: true,
			speed: 800,
			slidesToShow: 1,
			lazyLoad: "progressive",
			arrows: false,
			dots: true,
			autoplay: false,
			infinite: false,
			prevArrow:
				'<div class="slick-nav prev-arrow"><i></i><svg><use xlink:href="#circle"></svg></div>',
			nextArrow:
				'<div class="slick-nav next-arrow"><i></i><svg><use xlink:href="#circle"></svg></div>',
				responsive: [
					{
					  breakpoint: 767,
					  settings: {
						slidesToShow: 1,
					  }
					},
				]
	})});
	$(window).scroll(function(){
		if ($(this).scrollTop() > 500) {
		   $('.float-container').addClass('flati-icon');
		} else {
		   $('.float-container').removeClass('flati-icon');
		}
	});
	$(document).ready(function () {
		$(".health-slider")
			.slick({
				autoplay: true,
				speed: 800,
				slidesToShow: 3,
				lazyLoad: "progressive",
				arrows: true,
				dots: false,
				autoplay: true,
				infinite: true,
				prevArrow:
					'<div class="slick-nav prev-arrow"><i></i><svg><use xlink:href="#circle"></svg></div>',
				nextArrow:
					'<div class="slick-nav next-arrow"><i></i><svg><use xlink:href="#circle"></svg></div>',
					responsive: [
						{
						  breakpoint: 767,
						  settings: {
							slidesToShow: 1,
						  }
						},
					]
		})});
