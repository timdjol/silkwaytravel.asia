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
