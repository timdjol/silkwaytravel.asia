<?php
/**
 * Fallback 404.
 *
 * @package silkway
 */
get_header();
?>
<section class="page-hero" style="background-image:url(<?php echo esc_url( silkway_img( 'bg.jpg' ) ); ?>);">
	<div class="container">
		<h1>Page not found</h1>
		<p>The page you are looking for does not exist.</p>
		<a class="btn btn-accent" href="<?php echo esc_url( home_url( '/' ) ); ?>">Back home</a>
	</div>
</section>
<?php get_footer(); ?>
