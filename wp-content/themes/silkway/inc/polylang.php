<?php
/**
 * Polylang integration.
 *
 * @package silkway
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register CPTs and taxonomies with Polylang.
 */
add_filter( 'pll_get_post_types', function ( $post_types, $is_settings ) {
	$post_types['tour']   = 'tour';
	$post_types['review'] = 'review';
	return $post_types;
}, 10, 2 );

add_filter( 'pll_get_taxonomies', function ( $taxonomies, $is_settings ) {
	$taxonomies['tour_type'] = 'tour_type';
	return $taxonomies;
}, 10, 2 );

/**
 * Current language slug (en/ru).
 */
function silkway_lang() {
	if ( function_exists( 'pll_current_language' ) ) {
		$lang = pll_current_language( 'slug' );
		if ( $lang ) {
			return $lang;
		}
	}
	return 'en';
}

/**
 * Whether current language is Russian.
 */
function silkway_is_ru() {
	return silkway_lang() === 'ru';
}

/**
 * Translated string helper.
 *
 * @param string $en English.
 * @param string $ru Russian.
 */
function silkway__( $en, $ru = '' ) {
	if ( silkway_is_ru() && $ru !== '' ) {
		return $ru;
	}
	return $en;
}

/**
 * Echo translated string.
 */
function silkway_e( $en, $ru = '' ) {
	echo esc_html( silkway__( $en, $ru ) );
}

/**
 * Language-aware page URL by slug (English slug base).
 *
 * @param string $en_slug English page slug.
 */
function silkway_page_url( $en_slug ) {
	$page_id = 0;

	// Prefer English source page, then map to current language via Polylang.
	if ( function_exists( 'pll_get_post' ) ) {
		$en_pages = get_posts( array(
			'name'             => $en_slug,
			'post_type'        => 'page',
			'post_status'      => 'publish',
			'posts_per_page'   => 1,
			'lang'             => 'en',
			'suppress_filters' => false,
		) );
		if ( ! empty( $en_pages ) ) {
			$page_id = (int) $en_pages[0]->ID;
			$translated_id = pll_get_post( $page_id, silkway_lang() );
			if ( $translated_id ) {
				return get_permalink( $translated_id );
			}
			return get_permalink( $page_id );
		}
	}

	$page = get_page_by_path( $en_slug );
	if ( $page ) {
		if ( function_exists( 'pll_get_post' ) ) {
			$translated_id = pll_get_post( $page->ID, silkway_lang() );
			if ( $translated_id ) {
				return get_permalink( $translated_id );
			}
		}
		return get_permalink( $page );
	}

	return home_url( '/' . trim( $en_slug, '/' ) . '/' );
}

/**
 * Home URL for current language.
 */
function silkway_home_url() {
	if ( function_exists( 'pll_home_url' ) ) {
		return pll_home_url();
	}
	return home_url( '/' );
}

/**
 * Render Polylang language switcher.
 */
function silkway_language_switcher() {
	if ( ! function_exists( 'pll_the_languages' ) ) {
		?>
		<div class="lang-switch">
			<a class="is-active" href="<?php echo esc_url( home_url( '/' ) ); ?>">EN</a>
			<a href="#">RU</a>
		</div>
		<?php
		return;
	}

	$langs = pll_the_languages( array(
		'raw'           => 1,
		'hide_if_empty' => 0,
		'display_names_as' => 'slug',
	) );

	if ( empty( $langs ) || ! is_array( $langs ) ) {
		return;
	}

	echo '<div class="lang-switch">';
	foreach ( $langs as $lang ) {
		$classes = ! empty( $lang['current_lang'] ) ? 'is-active' : '';
		printf(
			'<a class="%1$s" href="%2$s" hreflang="%3$s" lang="%3$s">%4$s</a>',
			esc_attr( $classes ),
			esc_url( $lang['url'] ),
			esc_attr( $lang['slug'] ),
			esc_html( strtoupper( $lang['slug'] ) )
		);
	}
	echo '</div>';
}
