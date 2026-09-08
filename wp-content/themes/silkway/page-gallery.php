<?php
/**
 * Template Name: Gallery
 *
 * @package silkway
 */
get_header();
?>
<section class="page-hero" style="background-image:url(<?php echo esc_url( silkway_img( 'bg.jpg' ) ); ?>);">
	<div class="container">
		<div class="breadcrumb">
			<a href="<?php echo esc_url( silkway_home_url() ); ?>"><?php silkway_e( 'Home', 'Главная' ); ?></a>
			· <span><?php silkway_e( 'Gallery', 'Галерея' ); ?></span>
		</div>
		<h1><?php the_title(); ?></h1>
		<p><?php silkway_e( 'Lakes, mountains, yurt camps and Silk Road atmospheres from our journeys.', 'Озёра, горы, юртовые лагеря и атмосфера Шёлкового пути из наших путешествий.' ); ?></p>
	</div>
</section>
<section class="page-section">
	<div class="container">
		<div class="gallery gallery-grid" data-aos="fade-up">
			<?php foreach ( array( 'bg.jpg', 'serv1.jpg', 'serv2.jpg', 'serv3.jpg', 'serv4.jpg', 'partner.jpg' ) as $img ) : ?>
				<a href="<?php echo esc_url( silkway_img( $img ) ); ?>" style="background-image:url(<?php echo esc_url( silkway_img( $img ) ); ?>);"></a>
			<?php endforeach; ?>
		</div>
		<?php if ( get_the_content() ) : ?>
			<div class="content-block" style="margin-top:40px;"><?php the_content(); ?></div>
		<?php endif; ?>
	</div>
</section>
<?php get_footer(); ?>
