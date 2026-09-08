<?php
/**
 * Tours archive.
 *
 * @package silkway
 */

get_header();
$fallbacks = array( 'serv1.jpg', 'serv2.jpg', 'serv3.jpg', 'serv4.jpg', 'partner.jpg', 'bg.jpg' );
?>
<section class="page-hero" style="background-image:url(<?php echo esc_url( silkway_img( 'bg.jpg' ) ); ?>);">
	<div class="container">
		<div class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> · <span>Tours</span></div>
		<h1>Tours in Kyrgyzstan & Central Asia</h1>
		<p>Author programs with transparent logistics: lakes, mountains, cultural cities, horse riding and corporate journeys.</p>
	</div>
</section>

<section class="page-section">
	<div class="container">
		<div class="row tour-grid">
			<?php
			$i = 0;
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					?>
					<div class="col-lg-4 col-md-6" data-aos="fade-up">
						<?php get_template_part( 'template-parts/tour', 'card', array( 'fallback' => $fallbacks[ $i % count( $fallbacks ) ] ) ); ?>
					</div>
					<?php
					$i++;
				endwhile;
			endif;
			?>
			<div class="col-lg-4 col-md-6" data-aos="fade-up">
				<a class="tour-card" href="<?php echo esc_url( home_url( '/corporate-tours/' ) ); ?>">
					<div class="tour-card__media" style="background-image:url(<?php echo esc_url( silkway_img( 'bg.jpg' ) ); ?>);">
						<span class="tour-card__badge">Custom</span>
					</div>
					<div class="tour-card__body">
						<h3>Corporate Tours</h3>
						<p>Team trips, incentives and business travel with tailored pacing.</p>
						<div class="tour-card__meta"><span class="tour-card__price">on request</span><span class="tour-card__more">Details →</span></div>
					</div>
				</a>
			</div>
		</div>

		<div class="content-block" style="margin-top:50px;" data-aos="fade-up">
			<h2>Important information</h2>
			<div class="faq">
				<button class="accordion is-open" type="button">What is usually included</button>
				<div class="accordion-content" style="max-height:220px;">
					<ul>
						<li>Airport meet & greet / transfers according to the program</li>
						<li>Transportation along the route</li>
						<li>Accommodation as per itinerary</li>
						<li>Breakfasts and English/Russian speaking guide</li>
					</ul>
				</div>
				<button class="accordion" type="button">What is usually not included</button>
				<div class="accordion-content">
					<ul>
						<li>International flights</li>
						<li>Lunches and dinners (unless stated)</li>
						<li>Travel insurance</li>
						<li>Personal expenses and optional activities</li>
					</ul>
				</div>
				<button class="accordion" type="button">Do I need a visa for Kyrgyzstan?</button>
				<div class="accordion-content">
					<p>Citizens of many countries can visit Kyrgyzstan visa-free for a limited period. We confirm requirements for your nationality before booking.</p>
				</div>
			</div>
		</div>

		<div class="cta-banner" data-aos="fade-up">
			<h2>Need a custom route?</h2>
			<p>Tell us your dates, group size and interests — we will prepare options within one business day.</p>
			<a class="btn btn-light" href="#enquiry">Send enquiry</a>
		</div>
	</div>
</section>
<?php get_footer(); ?>
