<?php
/**
 * Footer template.
 *
 * @package silkway
 */
?>
<section class="enquiry" id="enquiry">
	<div class="container">
		<div class="enquiry__grid">
			<div class="enquiry__intro" data-aos="fade-up">
				<p class="eyebrow">Plan your trip</p>
				<h2>Leave a request</h2>
				<p>Our managers will contact you and help craft the right Central Asia itinerary.</p>
			</div>
			<form class="enquiry__form" action="#" method="post" data-aos="fade-up" data-aos-delay="100">
				<div class="form-row">
					<input type="text" name="name" placeholder="Your name" required>
					<input type="tel" name="phone" placeholder="Phone number" required>
				</div>
				<div class="form-row">
					<input type="email" name="email" placeholder="Email">
					<input type="number" name="guests" min="1" value="2" placeholder="Guests">
				</div>
				<textarea name="message" rows="4" placeholder="Tell us about your travel plans"></textarea>
				<label class="agree">
					<input type="checkbox" required>
					<span>I agree to the processing of personal data according to the <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">privacy policy</a></span>
				</label>
				<button class="btn btn-accent" type="submit">Send request</button>
			</form>
		</div>
	</div>
</section>

<footer class="site-footer">
	<div class="container">
		<div class="footer-grid">
			<div class="footer-brand">
				<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img src="<?php echo esc_url( silkway_img( 'logo.png' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>">
				</a>
				<p>Silk Way Travel — part of Silk Way Group. Discover Central Asia with local expertise since 2016.</p>
				<p class="footer-contacts">
					<a href="tel:+996772020222"><?php echo esc_html( silkway_phone() ); ?></a><br>
					<a href="mailto:<?php echo esc_attr( silkway_email() ); ?>"><?php echo esc_html( silkway_email() ); ?></a><br>
					<?php echo esc_html( silkway_address() ); ?>
				</p>
			</div>
			<div>
				<h4>Tours</h4>
				<ul>
					<?php
					$tour_q = new WP_Query( array(
						'post_type'      => 'tour',
						'posts_per_page' => 6,
						'orderby'        => 'menu_order title',
						'order'          => 'ASC',
					) );
					if ( $tour_q->have_posts() ) :
						while ( $tour_q->have_posts() ) :
							$tour_q->the_post();
							?>
							<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
							<?php
						endwhile;
						wp_reset_postdata();
					endif;
					?>
					<li><a href="<?php echo esc_url( home_url( '/corporate-tours/' ) ); ?>">Corporate Tours</a></li>
				</ul>
			</div>
			<div>
				<h4>Company</h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li>
					<li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a></li>
					<li><a href="<?php echo esc_url( get_post_type_archive_link( 'review' ) ); ?>">Reviews</a></li>
					<li><a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>">Gallery</a></li>
					<li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Services</a></li>
					<li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Privacy Policy</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="copy">
		<div class="container">
			<p>All rights reserved &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> silkwaytravel.asia</p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
