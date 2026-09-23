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

	function digitsOnly(value) {
		return String(value || "").replace(/\D/g, "");
	}

	function isValidPhone(value) {
		var raw = String(value || "").trim();
		if (!raw) return false;
		// Allow +, spaces, dashes, parentheses — validate by digit count.
		if (!/^[+\d][\d\s()\-./]*$/.test(raw)) return false;
		var digits = digitsOnly(raw);
		// Local KG without country code (9–10) or international E.164 (10–15).
		if (digits.length >= 9 && digits.length <= 15) return true;
		return false;
	}

	function setPhoneError($input, message) {
		var $field = $input.closest(".form-field");
		var $err = $field.find(".field-error");
		if (!$err.length) {
			$err = $('<span class="field-error" role="alert"></span>').appendTo($field);
		}
		if (message) {
			$input.addClass("is-invalid").attr("aria-invalid", "true");
			$err.text(message).prop("hidden", false);
			try {
				$input[0].setCustomValidity(message);
			} catch (err) {}
		} else {
			$input.removeClass("is-invalid").attr("aria-invalid", "false");
			$err.text("").prop("hidden", true);
			try {
				$input[0].setCustomValidity("");
			} catch (err) {}
		}
	}

	function bindEnquiryForm() {
		var phoneError =
			(cfg.i18n && cfg.i18n.phoneInvalid) ||
			"Enter a valid phone number, e.g. +996 XXX XXX XXX";

		$(document).off("submit.silkwayEnquiry input.silkwayEnquiry blur.silkwayEnquiry", ".enquiry__form");
		$(document).on("input.silkwayEnquiry blur.silkwayEnquiry", '.enquiry__form [name="phone"]', function () {
			var $input = $(this);
			var val = $.trim($input.val() || "");
			if (!val) {
				setPhoneError($input, "");
				return;
			}
			setPhoneError($input, isValidPhone(val) ? "" : phoneError);
		});

		$(document).on("submit.silkwayEnquiry", ".enquiry__form", function (e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			var $form = $(this);
			var $phone = $form.find('[name="phone"]');
			var name = $.trim($form.find('[name="name"]').val() || "");
			var phone = $.trim($phone.val() || "");
			var email = $.trim($form.find('[name="email"]').val() || "");
			var guests = $.trim($form.find('[name="guests"]').val() || "");
			var message = $.trim($form.find('[name="message"]').val() || "");
			var tour = $.trim($form.data("tour") || "");
			var $agree = $form.find('.agree input[type="checkbox"]');

			if (!name) {
				$form.find('[name="name"]').trigger("focus");
				return;
			}
			if (!isValidPhone(phone)) {
				setPhoneError($phone, phoneError);
				$phone.trigger("focus");
				return;
			}
			setPhoneError($phone, "");
			if ($agree.length && !$agree.is(":checked")) {
				$agree.trigger("focus");
				return;
			}

			var lines = [];
			lines.push(cfg.i18n && cfg.i18n.requestTitle ? cfg.i18n.requestTitle : "Silk Way Travel enquiry");
			if (tour) lines.push((cfg.i18n && cfg.i18n.tour ? cfg.i18n.tour : "Tour") + ": " + tour);
			if (name) lines.push((cfg.i18n && cfg.i18n.name ? cfg.i18n.name : "Name") + ": " + name);
			lines.push((cfg.i18n && cfg.i18n.phone ? cfg.i18n.phone : "Phone") + ": " + phone);
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
