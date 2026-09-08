/**
 * Theme front-end extras.
 */
(function ($) {
	var offsetTimer;

	function headerOffset() {
		var $header = $(".site-header");
		if (!$header.length) return;
		if ($header.hasClass("is-scrolled")) {
			$("body").addClass("header-compact").css("--silkway-header-offset", $header.outerHeight() + "px");
		} else {
			$("body").removeClass("header-compact").css("--silkway-header-offset", "0px");
		}
	}

	function scheduleOffset() {
		headerOffset();
		clearTimeout(offsetTimer);
		offsetTimer = setTimeout(headerOffset, 280);
	}

	$(window).on("scroll resize", scheduleOffset);
	$(function () {
		scheduleOffset();
	});
})(jQuery);
