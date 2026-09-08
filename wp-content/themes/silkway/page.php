<?php
/**
 * Default page.
 *
 * @package silkway
 */
get_header();
while ( have_posts() ) :
	the_post();
	$hero = get_the_post_thumbnail_url( get_the_ID(), 'silkway-hero' ) ?: silkway_img( 'partner.jpg' );
	?>
	<section class="page-hero" style="background-image:url(<?php echo esc_url( $hero ); ?>);">
		<div class="container">
			<div class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> · <span><?php the_title(); ?></span></div>
			<h1><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?><p><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
		</div>
	</section>
	<section class="page-section">
		<div class="container">
			<div class="content-block" data-aos="fade-up">
				<?php the_content(); ?>
			</div>
		</div>
	</section>
	<?php
endwhile;
get_footer();
