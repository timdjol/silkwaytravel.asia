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
	'lang'           => silkway_lang(),
) );

$reviews = new WP_Query( array(
	'post_type'      => 'review',
	'posts_per_page' => 6,
	'lang'           => silkway_lang(),
) );

$fallbacks = array( 'serv1.jpg', 'serv2.jpg', 'serv3.jpg', 'serv4.jpg', 'partner.jpg', 'bg.jpg' );

$why = silkway_is_ru()
	? array(
		array( 'why1.png', 'Местная экспертиза и опытные гиды' ),
		array( 'why2.png', 'Персональный сервис и гибкие маршруты' ),
		array( 'why3.png', 'Ответственный подход к туризму' ),
		array( 'why4.png', 'Искреннее гостеприимство и аутентичный опыт' ),
	)
	: array(
		array( 'why1.png', 'Local expertise and experienced guides' ),
		array( 'why2.png', 'Personalized service and flexible itineraries' ),
		array( 'why3.png', 'Commitment to sustainable tourism' ),
		array( 'why4.png', 'Genuine hospitality and authentic experiences' ),
	);
?>

<section class="hero">
	<div class="owl-carousel owl-hero">
		<div class="hero__slide" style="background-image: url(<?php echo esc_url( silkway_img( 'bg.jpg' ) ); ?>);">
			<div class="hero__content" data-aos="fade-up">
				<p class="eyebrow" style="color:#b7e35d;">Silk Way Travel</p>
				<h1><?php silkway_e( 'Travel for memories across Central Asia', 'Путешествия ради воспоминаний по Центральной Азии' ); ?></h1>
				<p><?php silkway_e( 'Author tours in Kyrgyzstan and beyond — lakes, mountains, Silk Road cities and nomadic culture with full local support.', 'Авторские туры по Кыргызстану и дальше — озёра, горы, города Шёлкового пути и кочевая культура с полной поддержкой на месте.' ); ?></p>
				<div class="btn-group">
					<a class="btn btn-accent" href="<?php echo esc_url( get_post_type_archive_link( 'tour' ) ); ?>"><?php silkway_e( 'Choose a tour', 'Выбрать тур' ); ?></a>
					<a class="btn btn-outline" href="<?php echo esc_url( silkway_page_url( 'about' ) ); ?>"><?php silkway_e( 'About us', 'О нас' ); ?></a>
				</div>
			</div>
		</div>
		<div class="hero__slide" style="background-image: url(<?php echo esc_url( silkway_img( 'serv1.jpg' ) ); ?>);">
			<div class="hero__content">
				<p class="eyebrow" style="color:#b7e35d;"><?php silkway_e( 'Issyk-Kul & mountains', 'Иссык-Куль и горы' ); ?></p>
				<h1><?php silkway_e( 'Kyrgyzstan highlights with comfort and character', 'Главные красоты Кыргызстана — комфортно и по-настоящему' ); ?></h1>
				<p><?php silkway_e( 'From alpine lakes to yurt stays — flexible itineraries for couples, families and groups.', 'От высокогорных озёр до юрт — гибкие маршруты для пар, семей и групп.' ); ?></p>
				<div class="btn-group">
					<a class="btn btn-accent" href="<?php echo esc_url( get_post_type_archive_link( 'tour' ) ); ?>"><?php silkway_e( 'View tours', 'Смотреть туры' ); ?></a>
					<a class="btn btn-outline" href="#enquiry"><?php silkway_e( 'Send enquiry', 'Оставить заявку' ); ?></a>
				</div>
			</div>
		</div>
		<div class="hero__slide" style="background-image: url(<?php echo esc_url( silkway_img( 'serv3.jpg' ) ); ?>);">
			<div class="hero__content">
				<p class="eyebrow" style="color:#b7e35d;"><?php silkway_e( 'Adventure & culture', 'Приключения и культура' ); ?></p>
				<h1><?php silkway_e( 'Horse riding, trekking and Silk Road discoveries', 'Конные туры, треккинг и открытия Шёлкового пути' ); ?></h1>
				<p><?php silkway_e( 'Trusted DMC connections since 2016 — transparent planning, strong logistics, memorable experiences.', 'Надёжные DMC-связи с 2016 года — прозрачное планирование, сильная логистика, яркие впечатления.' ); ?></p>
				<div class="btn-group">
					<a class="btn btn-accent" href="<?php echo esc_url( silkway_page_url( 'services' ) ); ?>"><?php silkway_e( 'Our services', 'Наши услуги' ); ?></a>
					<a class="btn btn-outline" href="<?php echo esc_url( silkway_page_url( 'gallery' ) ); ?>"><?php silkway_e( 'Gallery', 'Галерея' ); ?></a>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="tours-section" id="tours">
	<div class="container">
		<div class="section-head" data-aos="fade-up">
			<p class="eyebrow"><?php silkway_e( 'Our tours', 'Наши туры' ); ?></p>
			<h2><?php silkway_e( 'Featured journeys', 'Избранные маршруты' ); ?></h2>
			<p><?php silkway_e( 'Ready-made programs with clear duration, route focus and flexible departure options.', 'Готовые программы с понятной длительностью, акцентом маршрута и гибкими датами.' ); ?></p>
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
			<a class="btn btn-accent" href="<?php echo esc_url( get_post_type_archive_link( 'tour' ) ); ?>"><?php silkway_e( 'All tours', 'Все туры' ); ?></a>
		</div>
	</div>
</section>

<section class="soft-band">
	<div class="container">
		<div class="soft-band__card" data-aos="fade-up">
			<div>
				<p class="eyebrow"><?php silkway_e( 'About Silk Way Travel', 'О Silk Way Travel' ); ?></p>
				<h2><?php silkway_e( 'Local expertise, flexible planning, genuine hospitality', 'Местная экспертиза, гибкое планирование, настоящее гостеприимство' ); ?></h2>
				<p><?php silkway_e( 'We have been operating since 2016 as part of Silk Way Group, building strong relationships with hotels, guides and DMCs across Central Asia. Whether you need a private itinerary or a corporate trip, we keep logistics clear and experiences authentic.', 'Мы работаем с 2016 года в составе Silk Way Group и выстроили партнёрства с отелями, гидами и DMC по всей Центральной Азии. Для частных и корпоративных поездок обеспечиваем понятную логистику и аутентичный опыт.' ); ?></p>
				<a class="btn btn-accent" href="<?php echo esc_url( silkway_page_url( 'about' ) ); ?>"><?php silkway_e( 'Learn more', 'Подробнее' ); ?></a>
			</div>
			<div class="soft-band__visual" style="background-image:url(<?php echo esc_url( silkway_img( 'bg.jpg' ) ); ?>);"></div>
		</div>
	</div>
</section>

<section class="why" id="why">
	<div class="container">
		<div class="section-head" data-aos="fade-up">
			<p class="eyebrow"><?php silkway_e( 'Why choose us', 'Почему мы' ); ?></p>
			<h2><?php silkway_e( 'What makes the journey better', 'Что делает путешествие лучше' ); ?></h2>
		</div>
		<div class="row">
			<?php foreach ( $why as $item ) : ?>
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
			<p class="eyebrow"><?php silkway_e( 'Reviews', 'Отзывы' ); ?></p>
			<h2><?php silkway_e( 'What travelers say', 'Что говорят путешественники' ); ?></h2>
			<p><?php silkway_e( 'Feedback from guests and corporate groups after journeys across Kyrgyzstan and Central Asia.', 'Отзывы гостей и корпоративных групп после поездок по Кыргызстану и Центральной Азии.' ); ?></p>
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
			<a class="btn btn-accent" href="<?php echo esc_url( get_post_type_archive_link( 'review' ) ); ?>"><?php silkway_e( 'All reviews', 'Все отзывы' ); ?></a>
		</div>
	</div>
</section>

<section class="moments">
	<div class="container">
		<div class="section-head" data-aos="fade-up">
			<p class="eyebrow"><?php silkway_e( 'Moments', 'Моменты' ); ?></p>
			<h2><?php silkway_e( 'Atmosphere of the road', 'Атмосфера дороги' ); ?></h2>
		</div>
		<div class="moments__grid" data-aos="fade-up">
			<video src="<?php echo esc_url( silkway_img( 'video.mp4' ) ); ?>" loop autoplay muted playsinline></video>
			<div class="moments__side">
				<a class="moments__tile" href="<?php echo esc_url( silkway_page_url( 'gallery' ) ); ?>" style="background-image:url(<?php echo esc_url( silkway_img( 'serv1.jpg' ) ); ?>);">
					<span><?php silkway_e( 'Gallery', 'Галерея' ); ?></span>
					<strong><?php silkway_e( 'Lakes & peaks', 'Озёра и вершины' ); ?></strong>
				</a>
				<a class="moments__tile" href="<?php echo esc_url( silkway_page_url( 'gallery' ) ); ?>" style="background-image:url(<?php echo esc_url( silkway_img( 'serv4.jpg' ) ); ?>);">
					<span><?php silkway_e( 'Gallery', 'Галерея' ); ?></span>
					<strong><?php silkway_e( 'Nomadic trails', 'Кочевые тропы' ); ?></strong>
				</a>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
