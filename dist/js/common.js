// Logo mark ---
anime({
	targets: '.preload-bg',
	easing: 'easeInOutExpo',
	delay: 6000,
	scale: 0,
	opacity: [1, 0]
});

anime({
	targets: '.logo .lines path',
	strokeDashoffset: [anime.setDashoffset, 0],
	easing: 'cubicBezier(0.785, 0.135, 0.15, 0.86);',
	duration: 4000,
	delay: function (el, i) {
		return i * 1250
	},
	direction: 'alternate',
	endDelay: 1200,
	loop: false,
	delay: 1300
});

anime({
	targets: '.logo .lines path',
	opacity: [0, 1],
	duration: 2600,
	easing: 'linear',
	delay: 1400
});

// Button ---
anime({
	targets: '#button-loading',
	opacity: [0, 1],
	duration: 2000,
	easing: 'linear',
	delay: 6000
});


// Wrap every letter in a span and animate - Improving customer understanding
$('.ml10').each(function () {
	$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='letter'>$&</span>"));
});

anime.timeline({
		loop: false
	})
	.add({
		targets: '.ml10 .letter',
		translateX: [20, 0],
		translateZ: 0,
		opacity: [0, 1],
		easing: "easeOutExpo",
		duration: 2000,
		delay: function (el, i) {
			return 2600 + 30 * i;
		}
	});

// Wrap every letter in a span and animate - Improving customer understanding
$('.ml12').each(function () {
	$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='letter'>$&</span>"));
});

anime.timeline({
		loop: false
	})
	.add({
		targets: '.ml12 .letter',
		translateX: [40, 0],
		translateZ: 0,
		opacity: [0, 1],
		easing: "easeOutExpo",
		duration: 4000,
		delay: function (el, i) {
			return 4000 + 30 * i;
		}
	});



// benefits

//anime({
//  targets: '.benefits-divider',
//	easing: 'easeInOutSine',
//	delay: anime.stagger(500, {from: 'last'}),
//	duration: 3000,
//	translateX: 1000,
//	direction: 'reverse'
//});
//
//anime({
//	targets: '.benefits',
//	duration: 6000,
//	opacity: [0, 1],
//	delay: anime.stagger(500, {start: 2900})
//});
//
//anime({
//	targets: '.benefits-title',
//	easing: 'easeInOutSine',
//	opacity: [0, 1],
//	delay: 2000
//})




jQuery(document).ready(function ($) {
	var isLateralNavAnimating = false;

	//open/close lateral navigation
	$('.carsen-nav-trigger').on('click', function (event) {
		event.preventDefault();
		//stop if nav animation is running
		if (!isLateralNavAnimating) {
			if ($(this).parents('.csstransitions').length > 0) isLateralNavAnimating = true;

			$('body').toggleClass('navigation-is-open');
			$('.carsen-navigation-wrapper').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function () {
				//animation is over
				isLateralNavAnimating = false;
			});
		}
	});
});