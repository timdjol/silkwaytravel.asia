<?php
/**
 * Custom post types.
 *
 * @package silkway
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_post_type( 'tour', array(
		'labels' => array(
			'name'          => __( 'Tours', 'silkway' ),
			'singular_name' => __( 'Tour', 'silkway' ),
			'add_new_item'  => __( 'Add New Tour', 'silkway' ),
			'edit_item'     => __( 'Edit Tour', 'silkway' ),
		),
		'public'       => true,
		'has_archive'  => true,
		'rewrite'      => array( 'slug' => 'tours' ),
		'menu_icon'    => 'dashicons-palmtree',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'show_in_rest' => true,
	) );

	register_post_type( 'review', array(
		'labels' => array(
			'name'          => __( 'Reviews', 'silkway' ),
			'singular_name' => __( 'Review', 'silkway' ),
		),
		'public'       => true,
		'has_archive'  => true,
		'rewrite'      => array( 'slug' => 'reviews' ),
		'menu_icon'    => 'dashicons-star-filled',
		'supports'     => array( 'title', 'editor' ),
		'show_in_rest' => true,
	) );

	register_taxonomy( 'tour_type', 'tour', array(
		'label'        => __( 'Tour Types', 'silkway' ),
		'public'       => true,
		'hierarchical' => true,
		'rewrite'      => array( 'slug' => 'tour-type' ),
		'show_in_rest' => true,
	) );
} );

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'silkway_tour_meta', __( 'Tour Details', 'silkway' ), 'silkway_tour_meta_box', 'tour', 'normal', 'high' );
	add_meta_box( 'silkway_review_meta', __( 'Review Details', 'silkway' ), 'silkway_review_meta_box', 'review', 'side', 'default' );
} );

function silkway_tour_meta_box( $post ) {
	wp_nonce_field( 'silkway_tour_meta', 'silkway_tour_nonce' );
	$duration = get_post_meta( $post->ID, '_tour_duration', true );
	$price    = get_post_meta( $post->ID, '_tour_price', true );
	$old      = get_post_meta( $post->ID, '_tour_old_price', true );
	$start    = get_post_meta( $post->ID, '_tour_start', true );
	$type     = get_post_meta( $post->ID, '_tour_type_label', true );
	$badge    = get_post_meta( $post->ID, '_tour_badge', true );
	$included = get_post_meta( $post->ID, '_tour_included', true );
	$excluded = get_post_meta( $post->ID, '_tour_excluded', true );
	$program  = get_post_meta( $post->ID, '_tour_program', true );
	$dates    = get_post_meta( $post->ID, '_tour_dates', true );
	?>
	<p><label>Duration<br><input type="text" name="tour_duration" value="<?php echo esc_attr( $duration ); ?>" class="widefat" placeholder="8 days / 7 nights"></label></p>
	<p><label>Price<br><input type="text" name="tour_price" value="<?php echo esc_attr( $price ); ?>" class="widefat" placeholder="$890"></label></p>
	<p><label>Old price<br><input type="text" name="tour_old_price" value="<?php echo esc_attr( $old ); ?>" class="widefat" placeholder="$980"></label></p>
	<p><label>Start<br><input type="text" name="tour_start" value="<?php echo esc_attr( $start ); ?>" class="widefat" placeholder="Bishkek"></label></p>
	<p><label>Type label<br><input type="text" name="tour_type_label" value="<?php echo esc_attr( $type ); ?>" class="widefat" placeholder="Combined"></label></p>
	<p><label>Badge<br><input type="text" name="tour_badge" value="<?php echo esc_attr( $badge ); ?>" class="widefat" placeholder="8 days"></label></p>
	<p><label>Included (one per line)<br><textarea name="tour_included" rows="6" class="widefat"><?php echo esc_textarea( $included ); ?></textarea></label></p>
	<p><label>Not included (one per line)<br><textarea name="tour_excluded" rows="6" class="widefat"><?php echo esc_textarea( $excluded ); ?></textarea></label></p>
	<p><label>Program (Day title | description per line)<br><textarea name="tour_program" rows="10" class="widefat"><?php echo esc_textarea( $program ); ?></textarea></label></p>
	<p><label>Dates (Date | Status | Price per line)<br><textarea name="tour_dates" rows="6" class="widefat"><?php echo esc_textarea( $dates ); ?></textarea></label></p>
	<?php
}

function silkway_review_meta_box( $post ) {
	wp_nonce_field( 'silkway_review_meta', 'silkway_review_nonce' );
	$city   = get_post_meta( $post->ID, '_review_city', true );
	$rating = get_post_meta( $post->ID, '_review_rating', true ) ?: '5';
	?>
	<p><label>City<br><input type="text" name="review_city" value="<?php echo esc_attr( $city ); ?>" class="widefat"></label></p>
	<p><label>Rating<br><input type="number" min="1" max="5" name="review_rating" value="<?php echo esc_attr( $rating ); ?>" class="widefat"></label></p>
	<?php
}

add_action( 'save_post_tour', function ( $post_id ) {
	if ( ! isset( $_POST['silkway_tour_nonce'] ) || ! wp_verify_nonce( $_POST['silkway_tour_nonce'], 'silkway_tour_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	$fields = array(
		'tour_duration'   => '_tour_duration',
		'tour_price'      => '_tour_price',
		'tour_old_price'  => '_tour_old_price',
		'tour_start'      => '_tour_start',
		'tour_type_label' => '_tour_type_label',
		'tour_badge'      => '_tour_badge',
		'tour_included'   => '_tour_included',
		'tour_excluded'   => '_tour_excluded',
		'tour_program'    => '_tour_program',
		'tour_dates'      => '_tour_dates',
	);
	foreach ( $fields as $key => $meta ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $meta, sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
} );

add_action( 'save_post_review', function ( $post_id ) {
	if ( ! isset( $_POST['silkway_review_nonce'] ) || ! wp_verify_nonce( $_POST['silkway_review_nonce'], 'silkway_review_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( isset( $_POST['review_city'] ) ) {
		update_post_meta( $post_id, '_review_city', sanitize_text_field( wp_unslash( $_POST['review_city'] ) ) );
	}
	if ( isset( $_POST['review_rating'] ) ) {
		update_post_meta( $post_id, '_review_rating', (int) $_POST['review_rating'] );
	}
} );
