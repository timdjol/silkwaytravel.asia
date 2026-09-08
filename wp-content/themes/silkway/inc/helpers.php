<?php
/**
 * Theme helpers.
 *
 * @package silkway
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function silkway_asset( $path ) {
	return get_template_directory_uri() . '/assets/' . ltrim( $path, '/' );
}

function silkway_img( $file ) {
	return silkway_asset( 'img/' . ltrim( $file, '/' ) );
}

function silkway_lines( $text ) {
	$lines = array_filter( array_map( 'trim', preg_split( "/\r\n|\n|\r/", (string) $text ) ) );
	return array_values( $lines );
}

function silkway_tour_image_url( $post_id = null, $fallback = 'serv1.jpg' ) {
	$post_id = $post_id ?: get_the_ID();
	if ( has_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail_url( $post_id, 'silkway-card' );
	}
	return silkway_img( $fallback );
}

function silkway_phone() {
	return '+996 772 02 02 22';
}

function silkway_email() {
	return 'info@silkwaytravel.asia';
}

function silkway_address() {
	return silkway__( '91 Chyngyz Aitmatov street, Bishkek, Kyrgyzstan', 'ул. Чынгыза Айтматова 91, Бишкек, Кыргызстан' );
}

function silkway_render_stars( $rating = 5 ) {
	$rating = max( 1, min( 5, (int) $rating ) );
	return str_repeat( '★', $rating );
}
