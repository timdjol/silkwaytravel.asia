<?php
/**
 * Template Name: Services
 *
 * @package silkway
 */
get_header();
$services = array(
	array( 'serv1.jpg', 'Individual & group tours', 'Travel around Central Asia with comfort and safety — ready-made or fully customized itineraries.' ),
	array( 'serv2.jpg', 'Corporate trips', 'Business tours, conferences and team-building with reliable logistics and local experiences.' ),
	array( 'serv3.jpg', 'Adventure trips', 'Trekking, horse riding, eco-routes and outdoor activities with experienced guides.' ),
	array( 'partner.jpg', 'Flight & hotel bookings', 'We help find practical options for flights and accommodations that match your route.' ),
);
?>
<section class="page-hero" style="background-image:url(<?php echo esc_url( silkway_img( 'serv4.jpg' ) ); ?>);">
	<div class="container">
		<div class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> · <span>Services</span></div>
		<h1><?php the_title(); ?></h1>
		<p>Individual and group tours, corporate trips, adventure programs and booking support across Central Asia.</p>
	</div>
</section>
<section class="page-section">
	<div class="container">
		<div class="row">
			<?php foreach ( $services as $service ) : ?>
				<div class="col-lg-6" style="margin-bottom:24px;" data-aos="fade-up">
					<article class="service-card">
						<div class="service-card__media" style="background-image:url(<?php echo esc_url( silkway_img( $service[0] ) ); ?>);"></div>
						<div class="service-card__body">
							<h3><?php echo esc_html( $service[1] ); ?></h3>
							<p><?php echo esc_html( $service[2] ); ?></p>
						</div>
					</article>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="cta-banner" data-aos="fade-up">
			<h2>Looking for a specific format?</h2>
			<p>Describe your goals and we will propose services that fit budget and timeline.</p>
			<a class="btn btn-light" href="#enquiry">Send enquiry</a>
		</div>
	</div>
</section>
<?php get_footer(); ?>
