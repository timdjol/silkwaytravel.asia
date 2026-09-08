<?php
/**
 * Blog index / posts page.
 *
 * @package silkway
 */
get_header();
?>
<section class="page-hero" style="background-image:url(<?php echo esc_url( silkway_img( 'serv3.jpg' ) ); ?>);">
	<div class="container">
		<div class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> · <span>Blog</span></div>
		<h1>Blog</h1>
		<p>Routes, seasons, packing tips and stories from the road across Kyrgyzstan and Central Asia.</p>
	</div>
</section>
<section class="page-section">
	<div class="container">
		<div class="row">
			<?php
			$i = 0;
			$imgs = array( 'serv1.jpg', 'serv3.jpg', 'partner.jpg', 'serv2.jpg', 'serv4.jpg', 'bg.jpg' );
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					$img = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'silkway-card' ) : silkway_img( $imgs[ $i % count( $imgs ) ] );
					?>
					<div class="col-lg-4 col-md-6" style="margin-bottom:24px;" data-aos="fade-up">
						<article class="blog-card">
							<div class="blog-card__media" style="background-image:url(<?php echo esc_url( $img ); ?>);"></div>
							<div class="blog-card__body">
								<span class="meta"><?php echo esc_html( get_the_date() ); ?></span>
								<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
							</div>
						</article>
					</div>
					<?php
					$i++;
				endwhile;
			endif;
			?>
		</div>
	</div>
</section>
<?php get_footer(); ?>
