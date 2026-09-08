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
		<div class="breadcrumb">
			<a href="<?php echo esc_url( silkway_home_url() ); ?>"><?php silkway_e( 'Home', 'Главная' ); ?></a>
			· <span><?php silkway_e( 'Reviews', 'Отзывы' ); ?></span>
		</div>
		<h1><?php silkway_e( 'Reviews', 'Отзывы' ); ?></h1>
		<p><?php silkway_e( 'Opinions from travelers and corporate groups after tours with Silk Way Travel.', 'Мнения путешественников и корпоративных групп после туров с Silk Way Travel.' ); ?></p>
	</div>
</section>

<section class="reviews-section reviews-archive">
	<div class="container">
		<div class="row reviews-grid">
			<?php if ( have_posts() ) : ?>
				<?php
				$i = 0;
				while ( have_posts() ) :
					the_post();
					$tone = array( 'is-blue', 'is-teal', 'is-lime' );
					?>
					<div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo esc_attr( ( $i % 3 ) * 80 ); ?>">
						<div class="review-card-wrap <?php echo esc_attr( $tone[ $i % 3 ] ); ?>">
							<?php get_template_part( 'template-parts/review', 'card' ); ?>
						</div>
					</div>
					<?php
					$i++;
				endwhile;
				?>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php
get_footer();
