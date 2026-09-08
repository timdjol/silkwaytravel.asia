<?php
/**
 * Reviews archive.
 *
 * @package silkway
 */
get_header();
?>
<section class="page-hero" style="background-image:url(<?php echo esc_url( silkway_img( 'serv2.jpg' ) ); ?>);">
	<div class="container">
		<div class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> · <span>Reviews</span></div>
		<h1>Reviews</h1>
		<p>Opinions from travelers and corporate groups after tours with Silk Way Travel.</p>
	</div>
</section>
<section class="page-section">
	<div class="container">
		<div class="row">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<div class="col-lg-4 col-md-6" style="margin-bottom:24px;" data-aos="fade-up">
					<?php get_template_part( 'template-parts/review', 'card' ); ?>
				</div>
			<?php endwhile; endif; ?>
		</div>
	</div>
</section>
<?php get_footer(); ?>
