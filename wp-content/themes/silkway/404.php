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
		<h1><?php silkway_e( 'Page not found', 'Страница не найдена' ); ?></h1>
		<p><?php silkway_e( 'The page you are looking for does not exist.', 'Страница, которую вы ищете, не существует.' ); ?></p>
		<a class="btn btn-accent" href="<?php echo esc_url( silkway_home_url() ); ?>"><?php silkway_e( 'Back home', 'На главную' ); ?></a>
	</div>
</section>
<?php get_footer(); ?>
