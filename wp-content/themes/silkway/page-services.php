<?php
/**
 * Template Name: Services
 *
 * @package silkway
 */
get_header();

$services = array(
	array(
		'serv1.jpg',
		silkway__( 'Individual & group tours', 'Индивидуальные и групповые туры' ),
		silkway__( 'Travel around Central Asia with comfort and safety — ready-made or fully customized itineraries.', 'Путешествия по Центральной Азии с комфортом и безопасностью — готовые или полностью индивидуальные маршруты.' ),
	),
	array(
		'serv2.jpg',
		silkway__( 'Corporate trips', 'Корпоративные поездки' ),
		silkway__( 'Business tours, conferences and team-building with reliable logistics and local experiences.', 'Бизнес-туры, конференции и тимбилдинг с надёжной логистикой и локальными впечатлениями.' ),
	),
	array(
		'serv3.jpg',
		silkway__( 'Adventure trips', 'Приключенческие туры' ),
		silkway__( 'Trekking, horse riding, eco-routes and outdoor activities with experienced guides.', 'Треккинг, конные маршруты, эко-туры и активности на природе с опытными гидами.' ),
	),
	array(
		'partner.jpg',
		silkway__( 'Flight & hotel bookings', 'Бронирование авиа и отелей' ),
		silkway__( 'We help find practical options for flights and accommodations that match your route.', 'Помогаем подобрать удобные варианты перелётов и проживания под ваш маршрут.' ),
	),
);
?>
<section class="page-hero" style="background-image:url(<?php echo esc_url( silkway_img( 'serv4.jpg' ) ); ?>);">
	<div class="container">
		<div class="breadcrumb">
			<a href="<?php echo esc_url( silkway_home_url() ); ?>"><?php silkway_e( 'Home', 'Главная' ); ?></a>
			· <span><?php silkway_e( 'Services', 'Услуги' ); ?></span>
		</div>
		<h1><?php the_title(); ?></h1>
		<p><?php silkway_e( 'Individual and group tours, corporate trips, adventure programs and booking support across Central Asia.', 'Индивидуальные и групповые туры, корпоративные поездки, приключенческие программы и поддержка бронирований по Центральной Азии.' ); ?></p>
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
			<h2><?php silkway_e( 'Looking for a specific format?', 'Ищете конкретный формат?' ); ?></h2>
			<p><?php silkway_e( 'Describe your goals and we will propose services that fit budget and timeline.', 'Опишите задачи — предложим услуги под бюджет и сроки.' ); ?></p>
			<a class="btn btn-light" href="#enquiry"><?php silkway_e( 'Send enquiry', 'Оставить заявку' ); ?></a>
		</div>
	</div>
</section>
<?php get_footer(); ?>
