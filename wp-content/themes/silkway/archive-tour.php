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
		<div class="breadcrumb"><a href="<?php echo esc_url( silkway_home_url() ); ?>"><?php silkway_e( 'Home', 'Главная' ); ?></a> · <span><?php silkway_e( 'Tours', 'Туры' ); ?></span></div>
		<h1><?php silkway_e( 'Tours in Kyrgyzstan & Central Asia', 'Туры по Кыргызстану и Центральной Азии' ); ?></h1>
		<p><?php silkway_e( 'Author programs with transparent logistics: lakes, mountains, cultural cities, horse riding and corporate journeys.', 'Авторские программы с прозрачной логистикой: озёра, горы, культурные города, конные и корпоративные поездки.' ); ?></p>
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
				<a class="tour-card" href="<?php echo esc_url( silkway_page_url( 'corporate-tours' ) ); ?>">
					<div class="tour-card__media" style="background-image:url(<?php echo esc_url( silkway_img( 'bg.jpg' ) ); ?>);">
						<span class="tour-card__badge"><?php silkway_e( 'Custom', 'Под заказ' ); ?></span>
					</div>
					<div class="tour-card__body">
						<h3><?php silkway_e( 'Corporate Tours', 'Корпоративные туры' ); ?></h3>
						<p><?php silkway_e( 'Team trips, incentives and business travel with tailored pacing.', 'Командные поездки, incentive и бизнес-туры с удобным темпом.' ); ?></p>
						<div class="tour-card__meta"><span class="tour-card__price"><?php silkway_e( 'on request', 'по запросу' ); ?></span><span class="tour-card__more"><?php silkway_e( 'Details →', 'Подробнее →' ); ?></span></div>
					</div>
				</a>
			</div>
		</div>

		<div class="content-block" style="margin-top:50px;" data-aos="fade-up">
			<h2><?php silkway_e( 'Important information', 'Важная информация' ); ?></h2>
			<div class="faq">
				<button class="accordion is-open" type="button"><?php silkway_e( 'What is usually included', 'Что обычно включено' ); ?></button>
				<div class="accordion-content" style="max-height:220px;">
					<ul>
						<li><?php silkway_e( 'Airport meet & greet / transfers according to the program', 'Встреча в аэропорту / трансферы по программе' ); ?></li>
						<li><?php silkway_e( 'Transportation along the route', 'Транспорт по маршруту' ); ?></li>
						<li><?php silkway_e( 'Accommodation as per itinerary', 'Проживание по программе' ); ?></li>
						<li><?php silkway_e( 'Breakfasts and English/Russian speaking guide', 'Завтраки и гид с английским/русским языком' ); ?></li>
					</ul>
				</div>
				<button class="accordion" type="button"><?php silkway_e( 'What is usually not included', 'Что обычно не включено' ); ?></button>
				<div class="accordion-content">
					<ul>
						<li><?php silkway_e( 'International flights', 'Международные авиаперелёты' ); ?></li>
						<li><?php silkway_e( 'Lunches and dinners (unless stated)', 'Обеды и ужины (если не указано иное)' ); ?></li>
						<li><?php silkway_e( 'Travel insurance', 'Страховка' ); ?></li>
						<li><?php silkway_e( 'Personal expenses and optional activities', 'Личные расходы и дополнительные активности' ); ?></li>
					</ul>
				</div>
				<button class="accordion" type="button"><?php silkway_e( 'Do I need a visa for Kyrgyzstan?', 'Нужна ли виза в Кыргызстан?' ); ?></button>
				<div class="accordion-content">
					<p><?php silkway_e( 'Citizens of many countries can visit Kyrgyzstan visa-free for a limited period. We confirm requirements for your nationality before booking.', 'Граждане многих стран могут посещать Кыргызстан без визы на ограниченный срок. Мы уточняем требования для вашей страны перед бронированием.' ); ?></p>
				</div>
			</div>
		</div>

		<div class="cta-banner" data-aos="fade-up">
			<h2><?php silkway_e( 'Need a custom route?', 'Нужен индивидуальный маршрут?' ); ?></h2>
			<p><?php silkway_e( 'Tell us your dates, group size and interests — we will prepare options within one business day.', 'Напишите даты, размер группы и интересы — подготовим варианты в течение одного рабочего дня.' ); ?></p>
			<a class="btn btn-light" href="#enquiry"><?php silkway_e( 'Send enquiry', 'Оставить заявку' ); ?></a>
		</div>
	</div>
</section>
<?php get_footer(); ?>
