/**
 * Theme front-end extras.
 */
(function ($) {
	var offsetTimer;
	var cfg = window.silkwayTheme || {};

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

	function openWhatsApp(text) {
		var base = cfg.whatsappUrl || "https://wa.me/996772020222";
		var url = text ? base + (base.indexOf("?") > -1 ? "&" : "?") + "text=" + encodeURIComponent(text) : base;
		window.open(url, "_blank", "noopener");
	}

	function bindEnquiryForm() {
		$(document).off("submit", ".enquiry__form");
		$(document).on("submit", ".enquiry__form", function (e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			var $form = $(this);
			var name = $.trim($form.find('[name="name"]').val() || "");
			var phone = $.trim($form.find('[name="phone"]').val() || "");
			var email = $.trim($form.find('[name="email"]').val() || "");
			var guests = $.trim($form.find('[name="guests"]').val() || "");
			var message = $.trim($form.find('[name="message"]').val() || "");
			var tour = $.trim($form.data("tour") || "");
			var lines = [];
			lines.push(cfg.i18n && cfg.i18n.requestTitle ? cfg.i18n.requestTitle : "Silk Way Travel enquiry");
			if (tour) lines.push((cfg.i18n && cfg.i18n.tour ? cfg.i18n.tour : "Tour") + ": " + tour);
			if (name) lines.push((cfg.i18n && cfg.i18n.name ? cfg.i18n.name : "Name") + ": " + name);
			if (phone) lines.push((cfg.i18n && cfg.i18n.phone ? cfg.i18n.phone : "Phone") + ": " + phone);
			if (email) lines.push("Email: " + email);
			if (guests) lines.push((cfg.i18n && cfg.i18n.guests ? cfg.i18n.guests : "Guests") + ": " + guests);
			if (message) lines.push((cfg.i18n && cfg.i18n.message ? cfg.i18n.message : "Message") + ": " + message);
			openWhatsApp(lines.join("\n"));
		});
	}

	function bindCookies() {
		var key = "silkway_cookie_ok";
		var $banner = $(".cookie-banner");
		if (!$banner.length) return;
		try {
			if (localStorage.getItem(key) === "1") {
				$banner.remove();
				return;
			}
		} catch (err) {}
		$banner.addClass("is-visible");
		$banner.on("click", "[data-cookie-accept]", function () {
			try {
				localStorage.setItem(key, "1");
			} catch (err) {}
			$banner.removeClass("is-visible").remove();
		});
	}

	function initTourMap() {
		var el = document.getElementById("tour-map");
		if (!el || typeof window.L === "undefined") return;
		var points = [];
		try {
			points = JSON.parse(el.getAttribute("data-points") || "[]");
		} catch (err) {
			points = [];
		}
		if (!points.length) return;

		var map = L.map(el, { scrollWheelZoom: false });
		L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
			maxZoom: 18,
			attribution: "&copy; OpenStreetMap",
		}).addTo(map);

		var bounds = [];
		points.forEach(function (p, i) {
			var marker = L.marker([p.lat, p.lng]).addTo(map);
			if (p.label) marker.bindPopup((i + 1) + ". " + p.label);
			bounds.push([p.lat, p.lng]);
		});
		if (bounds.length === 1) {
			map.setView(bounds[0], 8);
		} else {
			map.fitBounds(bounds, { padding: [30, 30] });
		}
	}

	$(window).on("scroll resize", scheduleOffset);
	$(function () {
		scheduleOffset();
		bindEnquiryForm();
		bindCookies();
		initTourMap();
	});
})(jQuery);
