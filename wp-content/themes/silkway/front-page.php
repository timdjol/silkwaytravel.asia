<?php
/**
 * Front page template.
 *
 * @package silkway
 */

get_header();

$tours = new WP_Query( array(
	'post_type'      => 'tour',
	'posts_per_page' => 8,
	'orderby'        => 'menu_order title',
	'order'          => 'ASC',
) );

$reviews = new WP_Query( array(
	'post_type'      => 'review',
	'posts_per_page' => 6,
) );

$fallbacks = array( 'serv1.jpg', 'serv2.jpg', 'serv3.jpg', 'serv4.jpg', 'partner.jpg', 'bg.jpg' );
?>

<section class="hero">
	<div class="owl-carousel owl-hero">
		<div class="hero__slide" style="background-image: url(<?php echo esc_url( silkway_img( 'bg.jpg' ) ); ?>);">
			<div class="hero__content" data-aos="fade-up">
				<p class="eyebrow" style="color:#b7e35d;">Silk Way Travel</p>
				<h1>Travel for memories across Central Asia</h1>
				<p>Author tours in Kyrgyzstan and beyond — lakes, mountains, Silk Road cities and nomadic culture with full local support.</p>
				<div class="btn-group">
					<a class="btn btn-accent" href="<?php echo esc_url( get_post_type_archive_link( 'tour' ) ); ?>">Choose a tour</a>
					<a class="btn btn-outline" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About us</a>
				</div>
			</div>
		</div>
		<div class="hero__slide" style="background-image: url(<?php echo esc_url( silkway_img( 'serv1.jpg' ) ); ?>);">
			<div class="hero__content">
				<p class="eyebrow" style="color:#b7e35d;">Issyk-Kul & mountains</p>
				<h1>Kyrgyzstan highlights with comfort and character</h1>
				<p>From alpine lakes to yurt stays — flexible itineraries for couples, families and groups.</p>
				<div class="btn-group">
					<a class="btn btn-accent" href="<?php echo esc_url( get_post_type_archive_link( 'tour' ) ); ?>">View tours</a>
					<a class="btn btn-outline" href="#enquiry">Send enquiry</a>
				</div>
			</div>
		</div>
		<div class="hero__slide" style="background-image: url(<?php echo esc_url( silkway_img( 'serv3.jpg' ) ); ?>);">
			<div class="hero__content">
				<p class="eyebrow" style="color:#b7e35d;">Adventure & culture</p>
				<h1>Horse riding, trekking and Silk Road discoveries</h1>
				<p>Trusted DMC connections since 2016 — transparent planning, strong logistics, memorable experiences.</p>
				<div class="btn-group">
					<a class="btn btn-accent" href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Our services</a>
					<a class="btn btn-outline" href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>">Gallery</a>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="tours-section" id="tours">
	<div class="container">
		<div class="section-head" data-aos="fade-up">
			<p class="eyebrow">Our tours</p>
			<h2>Featured journeys</h2>
			<p>Ready-made programs with clear duration, route focus and flexible departure options.</p>
		</div>
		<?php if ( $tours->have_posts() ) : ?>
			<div class="owl-carousel owl-tours">
				<?php
				$i = 0;
				while ( $tours->have_posts() ) :
					$tours->the_post();
					get_template_part( 'template-parts/tour', 'card', array( 'fallback' => $fallbacks[ $i % count( $fallbacks ) ] ) );
					$i++;
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php endif; ?>
		<div class="text-center" style="margin-top:40px;" data-aos="fade-up">
			<a class="btn btn-accent" href="<?php echo esc_url( get_post_type_archive_link( 'tour' ) ); ?>">All tours</a>
		</div>
	</div>
</section>

<section class="soft-band">
	<div class="container">
		<div class="soft-band__card" data-aos="fade-up">
			<div>
				<p class="eyebrow">About Silk Way Travel</p>
				<h2>Local expertise, flexible planning, genuine hospitality</h2>
				<p>We have been operating since 2016 as part of Silk Way Group, building strong relationships with hotels, guides and DMCs across Central Asia. Whether you need a private itinerary or a corporate trip, we keep logistics clear and experiences authentic.</p>
				<a class="btn btn-accent" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">Learn more</a>
			</div>
			<div class="soft-band__visual" style="background-image:url(<?php echo esc_url( silkway_img( 'bg.jpg' ) ); ?>);"></div>
		</div>
	</div>
</section>

<section class="why" id="why">
	<div class="container">
		<div class="section-head" data-aos="fade-up">
			<p class="eyebrow">Why choose us</p>
			<h2>What makes the journey better</h2>
		</div>
		<div class="row">
			<?php
			$why = array(
				array( 'why1.png', 'Local expertise and experienced guides' ),
				array( 'why2.png', 'Personalized service and flexible itineraries' ),
				array( 'why3.png', 'Commitment to sustainable tourism' ),
				array( 'why4.png', 'Genuine hospitality and authentic experiences' ),
			);
			foreach ( $why as $item ) :
				?>
				<div class="col-lg-3 col-md-6" data-aos="fade-up">
					<div class="why-item">
						<img src="<?php echo esc_url( silkway_img( $item[0] ) ); ?>" alt="">
						<h5><?php echo esc_html( $item[1] ); ?></h5>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="reviews-section">
	<div class="container">
		<div class="section-head" data-aos="fade-up">
			<p class="eyebrow">Reviews</p>
			<h2>What travelers say</h2>
			<p>Feedback from guests and corporate groups after journeys across Kyrgyzstan and Central Asia.</p>
		</div>
		<?php if ( $reviews->have_posts() ) : ?>
			<div class="owl-carousel owl-reviews">
				<?php
				while ( $reviews->have_posts() ) :
					$reviews->the_post();
					get_template_part( 'template-parts/review', 'card' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php endif; ?>
		<div class="text-center" style="margin-top:36px;">
			<a class="btn btn-accent" href="<?php echo esc_url( get_post_type_archive_link( 'review' ) ); ?>">All reviews</a>
		</div>
	</div>
</section>

<section class="moments">
	<div class="container">
		<div class="section-head" data-aos="fade-up">
			<p class="eyebrow">Moments</p>
			<h2>Atmosphere of the road</h2>
		</div>
		<div class="moments__grid" data-aos="fade-up">
			<video src="<?php echo esc_url( silkway_img( 'video.mp4' ) ); ?>" loop autoplay muted playsinline></video>
			<div class="moments__side">
				<a class="moments__tile" href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>" style="background-image:url(<?php echo esc_url( silkway_img( 'serv1.jpg' ) ); ?>);">
					<span>Gallery</span>
					<strong>Lakes & peaks</strong>
				</a>
				<a class="moments__tile" href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>" style="background-image:url(<?php echo esc_url( silkway_img( 'serv4.jpg' ) ); ?>);">
					<span>Gallery</span>
					<strong>Nomadic trails</strong>
				</a>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
