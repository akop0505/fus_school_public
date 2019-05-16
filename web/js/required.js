// Javascripts / Vendors / drawer.js
// ---------------------------------

// Function
(function($) {
	$.fn.extend ({
		drawer: function() {
			return this.each(function() {

				// Variables
				var container   = $("body"),
					active      = "active",
					expand      = $(".drawer .expand"),
					current     = $(".drawer .navigation").children(),
					drawer      = $(this);

				// Activate drawer
				drawer.on("click", function(e) {

					// Toggle class active
					container.toggleClass(active);

					// Remove class active
					expand.removeClass(active);

					// Remove class active
					current.removeClass("current");

					// Hide part
					expand.find(".part").hide();

					// Disable default behaviour
					e.preventDefault();

				});

			});
		}
	});
})(jQuery);

// Javascripts / vendors.js
// ------------------------

$(document).ready(function() {

	// Drawer
	$("a.menu").drawer();

	// Select 2
	$("select").not('.skipSelect2').select2();
	$(document).on('pjax:success', function(data) {
		$("select", data.target).not('.skipSelect2').select2();
	});

	// Perfect scrollbar
	$(".scrollbar").perfectScrollbar();

	// Flexslider slider
	$("#header .slider").flexslider({
		controlNav          : true,
		directionNav		: false,
		customDirectionNav  : $(".arrows a"),
		after               : function(slider) {
			var curSlide    = slider.find(".slides li.flex-active-slide"),
				prevImage   = curSlide.prev().css("background-image"),
				nextImage   = curSlide.next().css("background-image"),
				firstImage  = slider.find(".slides li:first-child").css("background-image"),
				lastImage   = slider.find(".slides li:last-child").css("background-image");

			if (prevImage !== undefined) {
				$("#header .flex-arrow.previous .image").css("background-image", prevImage);
			} else {
				$("#header .flex-arrow.previous .image").css("background-image", lastImage);
			}

			if (nextImage !== undefined) {
				$("#header .flex-arrow.next .image").css("background-image", nextImage);
			} else {
				$("#header .flex-arrow.next .image").css("background-image", firstImage);
			}
		},
		start               : function(slider) {
			var curSlide    = slider.find(".slides li.flex-active-slide"),
				lastImage   = slider.find(".slides li:last-child").css("background-image"),
				nextImage   = curSlide.next().css("background-image");

			$("#header .flex-arrow.previous .image").css("background-image", lastImage);
			$("#header .flex-arrow.next .image").css("background-image", nextImage);
		}
	});

	$("#header .flex-arrow.previous").on("click", function() {
		$("#header .slider").flexslider("prev");
	});

	$("#header .flex-arrow.next").on("click", function() {
		$("#header .slider").flexslider("next");
	});

	// Expand
	var expand = $(".drawer .expand");
	// Hide expand
	function hideExpand() {

		// Remove class active
		$(".drawer .expand").removeClass("active");

		// Hide part
		$(".drawer .expand .part").hide();

		// Remove current class
		$(".drawer .navigation").children().removeClass("current");

	}

	// Cover toggle
	$("body").on("click", ".cover .toggle", function(e) {
		var $hasClose = $(this).hasClass('close'), $rel = $(this).attr('rel');
		// Toggle play video
		$("body").toggleClass("play-video").toggleClass("stopped-video");
		// Toggle player
		var $player = $(".cover .player");
		$player.css('visibility', $hasClose ? 'hidden' : 'visible');
		$player.css('max-height', $hasClose ? '0' : 'none');
		if(!$player.hasClass('fist-start'))
		{
			$player.toggle();
			$player.addClass('fist-start');
		}
		setTimeout(function() { jwplayer($rel).play($hasClose ? false : true); }, $hasClose ? 1 : 300);
		return false;
	})
	// Drawer expand
	.on("click", ".drawer .navigation a", function(e) {

		if($(this).hasClass('skipSubmenu')) return;
		if ($(window).width() > "767") {

			// Toggle current class
			$(this).parent().toggleClass("current").siblings().removeClass("current");

			// Toggle part
			expand.find($(".part[data-part='" + $(this).data("part") + "']")).toggle().siblings().hide();

			// Toggle active class on expand
			if (expand.find(".part:visible").length !== 0) {
				expand.addClass("active");
			} else {
				expand.removeClass("active");
			}

			e.preventDefault();

		}

	})
	// Close expand
	.on("click", ".drawer .expand .close", function(e) {

		// Initialize hide expand
		hideExpand();

		e.preventDefault();

	});

	// Expand on resize
	$(window).on("resize", function() {

		if ($(window).width() < "768") {

			// Initialize hide expand
			hideExpand();

		}

	});

	// Add class scrolled on scroll
	function scrolled() {

		if ($(window).scrollTop() > "0") {

			$("body").addClass("scrolled");

		} else {

			$("body").removeClass("scrolled");

		}

	}

	// Initialize scrolled
	scrolled();

	// Scrolled
	$(window).on("scroll", function() {

		// Initialize scrolled
		scrolled();

	});

});