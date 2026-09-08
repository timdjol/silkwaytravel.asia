<?php
/**
 * Review card partial.
 *
 * @package silkway
 */

$city   = get_post_meta( get_the_ID(), '_review_city', true );
$rating = get_post_meta( get_the_ID(), '_review_rating', true ) ?: 5;
?>
<article class="review-card">
	<span class="review-card__mark" aria-hidden="true">“</span>
	<p><?php echo esc_html( wp_strip_all_tags( get_the_content() ) ); ?></p>
	<div class="review-card__author">
		<div>
			<strong><?php the_title(); ?></strong>
			<?php if ( $city ) : ?><span><?php echo esc_html( $city ); ?></span><?php endif; ?>
		</div>
		<div class="review-card__stars" aria-label="<?php echo esc_attr( $rating ); ?>/5"><?php echo esc_html( silkway_render_stars( $rating ) ); ?></div>
	</div>
</article>
