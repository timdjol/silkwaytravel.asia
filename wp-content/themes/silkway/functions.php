<?php
/**
 * Silk Way Travel theme functions.
 *
 * @package silkway
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SILKWAY_VERSION', '1.1.0' );

require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/cpt.php';
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/polylang.php';

add_action( 'wp_enqueue_scripts', function () {
	$uri = get_template_directory_uri();

	wp_enqueue_style(
		'silkway-main',
		$uri . '/assets/css/main.min.css',
		array(),
		SILKWAY_VERSION
	);

	wp_enqueue_style(
		'silkway-theme',
		$uri . '/assets/css/theme.css',
		array( 'silkway-main' ),
		SILKWAY_VERSION
	);

	wp_enqueue_script(
		'silkway-scripts',
		$uri . '/assets/js/scripts.min.js',
		array(),
		SILKWAY_VERSION,
		true
	);

	$theme_deps = array( 'silkway-scripts' );
	if ( is_singular( 'tour' ) && empty( $_GET['program'] ) ) {
		wp_enqueue_style(
			'silkway-leaflet',
			'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
			array(),
			'1.9.4'
		);
		wp_enqueue_script(
			'silkway-leaflet',
			'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
			array(),
			'1.9.4',
			true
		);
		$theme_deps[] = 'silkway-leaflet';
	}

	wp_enqueue_script(
		'silkway-theme',
		$uri . '/assets/js/theme.js',
		$theme_deps,
		SILKWAY_VERSION,
		true
	);

	wp_localize_script(
		'silkway-theme',
		'silkwayTheme',
		array(
			'whatsappUrl' => silkway_whatsapp_url(),
			'i18n'        => array(
				'requestTitle' => silkway__( 'Silk Way Travel enquiry', 'Заявка Silk Way Travel' ),
				'tour'         => silkway__( 'Tour', 'Тур' ),
				'name'         => silkway__( 'Name', 'Имя' ),
				'phone'        => silkway__( 'Phone', 'Телефон' ),
				'guests'       => silkway__( 'Guests', 'Гости' ),
				'message'      => silkway__( 'Message', 'Сообщение' ),
			),
		)
	);
} );

add_filter( 'excerpt_length', function () {
	return 22;
} );

add_filter( 'excerpt_more', function () {
	return '…';
} );

/**
 * Long-cache theme static assets.
 */
add_action( 'init', function () {
	if ( is_admin() ) {
		return;
	}
	$uri = $_SERVER['REQUEST_URI'] ?? '';
	if ( strpos( $uri, '/wp-content/themes/silkway/assets/' ) === false ) {
		return;
	}
	if ( ! headers_sent() ) {
		header( 'Cache-Control: public, max-age=31536000, immutable' );
	}
}, 1 );

/**
 * Printable tour program (Save as PDF from browser).
 */
add_filter( 'template_include', function ( $template ) {
	if ( is_singular( 'tour' ) && isset( $_GET['program'] ) && $_GET['program'] === 'print' ) {
		$print = get_template_directory() . '/tour-print.php';
		if ( file_exists( $print ) ) {
			return $print;
		}
	}
	return $template;
} );
