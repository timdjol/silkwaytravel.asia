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

/**
 * Prefer WebP when a sibling .webp file exists.
 */
function silkway_img_url( $file ) {
	$file = ltrim( $file, '/' );
	$path = get_template_directory() . '/assets/img/' . $file;
	$webp = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $file );
	$webp_path = get_template_directory() . '/assets/img/' . $webp;
	if ( $webp !== $file && file_exists( $webp_path ) ) {
		return silkway_img( $webp );
	}
	if ( file_exists( $path ) ) {
		return silkway_img( $file );
	}
	return silkway_img( $file );
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
	return silkway_img_url( $fallback );
}

function silkway_phone() {
	return '+996 772 02 02 22';
}

function silkway_whatsapp_number() {
	return '996772020222';
}

function silkway_whatsapp_url( $text = '' ) {
	$url = 'https://wa.me/' . silkway_whatsapp_number();
	if ( $text !== '' ) {
		$url .= '?text=' . rawurlencode( $text );
	}
	return $url;
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

/**
 * Default map points for Kyrgyzstan tours (lat,lng|label).
 */
function silkway_default_map_points() {
	if ( silkway_is_ru() ) {
		return array(
			'42.8746,74.5698|Бишкек',
			'42.4907,78.3941|Иссык-Куль',
			'41.835,75.15|Сон-Куль',
			'40.5283,72.7985|Ош',
		);
	}
	return array(
		'42.8746,74.5698|Bishkek',
		'42.4907,78.3941|Issyk-Kul',
		'41.835,75.15|Son-Kul',
		'40.5283,72.7985|Osh',
	);
}

function silkway_tour_map_points( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$raw     = get_post_meta( $post_id, '_tour_map_points', true );
	$lines   = silkway_lines( $raw );
	if ( empty( $lines ) ) {
		$lines = silkway_default_map_points();
	}
	$points = array();
	foreach ( $lines as $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		$coords = array_map( 'trim', explode( ',', $parts[0] ) );
		if ( count( $coords ) < 2 ) {
			continue;
		}
		$points[] = array(
			'lat'   => (float) $coords[0],
			'lng'   => (float) $coords[1],
			'label' => $parts[1] ?? '',
		);
	}
	return $points;
}
