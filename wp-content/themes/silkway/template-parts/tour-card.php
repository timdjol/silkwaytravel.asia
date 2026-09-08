<?php
/**
 * Tour card partial.
 *
 * @package silkway
 */

$fallback = $args['fallback'] ?? 'serv1.jpg';
$badge    = get_post_meta( get_the_ID(), '_tour_badge', true );
$price    = get_post_meta( get_the_ID(), '_tour_price', true );
$old      = get_post_meta( get_the_ID(), '_tour_old_price', true );
$image    = silkway_tour_image_url( get_the_ID(), $fallback );
?>
<a class="tour-card" href="<?php the_permalink(); ?>">
	<div class="tour-card__media" style="background-image:url(<?php echo esc_url( $image ); ?>);">
		<?php if ( $badge ) : ?>
			<span class="tour-card__badge"><?php echo esc_html( $badge ); ?></span>
		<?php endif; ?>
	</div>
	<div class="tour-card__body">
		<h3><?php the_title(); ?></h3>
		<p><?php echo esc_html( wp_trim_words( get_the_excerpt() ?: wp_strip_all_tags( get_the_content() ), 18 ) ); ?></p>
		<div class="tour-card__meta">
			<span class="tour-card__price">
				<?php if ( $old ) : ?><del><?php echo esc_html( $old ); ?></del><?php endif; ?>
				<?php echo esc_html( $price ?: silkway__( 'on request', 'по запросу' ) ); ?>
			</span>
			<span class="tour-card__more"><?php echo esc_html( silkway__( 'Details →', 'Подробнее →' ) ); ?></span>
		</div>
	</div>
</a>
