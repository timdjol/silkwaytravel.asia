<?php
/**
 * Silk Way Travel theme functions.
 *
 * @package silkway
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SILKWAY_VERSION', '1.0.0' );

require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/cpt.php';
require_once get_template_directory() . '/inc/helpers.php';

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
} );

add_filter( 'excerpt_length', function () {
	return 22;
} );

add_filter( 'excerpt_more', function () {
	return '…';
} );
