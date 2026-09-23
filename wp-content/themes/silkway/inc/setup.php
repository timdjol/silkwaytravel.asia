<?php
/**
 * Theme setup.
 *
 * @package silkway
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 80,
		'width'       => 240,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	register_nav_menus( array(
		'primary'   => __( 'Primary Menu', 'silkway' ),
		'tours'     => __( 'Tours Dropdown', 'silkway' ),
		'footer'    => __( 'Footer Menu', 'silkway' ),
		'footer_tours' => __( 'Footer Tours', 'silkway' ),
	) );

	add_image_size( 'silkway-card', 800, 520, true );
	add_image_size( 'silkway-hero', 1920, 1080, true );
} );

/**
 * Brand favicon from theme assets (logo mark).
 */
add_action( 'wp_head', function () {
	$ver = defined( 'SILKWAY_VERSION' ) ? SILKWAY_VERSION : '1.0.0';
	$ico = esc_url( silkway_img( 'favicon.ico' ) );
	$png32 = esc_url( silkway_img( 'favicon-32x32.png' ) );
	$png16 = esc_url( silkway_img( 'favicon-16x16.png' ) );
	$apple = esc_url( silkway_img( 'apple-touch-icon.png' ) );

	echo '<link rel="icon" href="' . $ico . '?v=' . esc_attr( $ver ) . '" sizes="any">' . "\n";
	echo '<link rel="icon" type="image/png" href="' . $png32 . '?v=' . esc_attr( $ver ) . '" sizes="32x32">' . "\n";
	echo '<link rel="icon" type="image/png" href="' . $png16 . '?v=' . esc_attr( $ver ) . '" sizes="16x16">' . "\n";
	echo '<link rel="apple-touch-icon" href="' . $apple . '?v=' . esc_attr( $ver ) . '">' . "\n";
}, 2 );

add_action( 'admin_head', function () {
	$ver = defined( 'SILKWAY_VERSION' ) ? SILKWAY_VERSION : '1.0.0';
	echo '<link rel="icon" href="' . esc_url( silkway_img( 'favicon.ico' ) ) . '?v=' . esc_attr( $ver ) . '" sizes="any">' . "\n";
}, 2 );
