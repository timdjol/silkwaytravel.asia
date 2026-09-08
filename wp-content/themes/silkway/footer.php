<?php
/**
 * Footer template.
 *
 * @package silkway
 */
?>
<section class="enquiry" id="enquiry" style="--enquiry-image:url(<?php echo esc_url( silkway_img( 'bg.jpg' ) ); ?>)">
	<div class="container">
		<div class="enquiry__grid">
			<div class="enquiry__intro" data-aos="fade-up">
				<p class="eyebrow"><?php silkway_e( 'Plan your trip', 'Спланируйте поездку' ); ?></p>
				<h2><?php silkway_e( 'Leave a request', 'Оставить заявку' ); ?></h2>
				<p><?php silkway_e( 'Our managers will contact you and help craft the right Central Asia itinerary.', 'Наши менеджеры свяжутся с вами и помогут подобрать маршрут по Центральной Азии.' ); ?></p>
				<div class="enquiry__contacts">
					<a href="tel:+996772020222"><?php echo esc_html( silkway_phone() ); ?></a>
					<a href="mailto:<?php echo esc_attr( silkway_email() ); ?>"><?php echo esc_html( silkway_email() ); ?></a>
					<a href="https://wa.me/996772020222" target="_blank" rel="noopener">WhatsApp</a>
				</div>
			</div>
			<form class="enquiry__form" action="#" method="post" data-aos="fade-up" data-aos-delay="100">
				<div class="form-row">
					<input type="text" name="name" placeholder="<?php echo esc_attr( silkway__( 'Your name', 'Ваше имя' ) ); ?>" required>
					<input type="tel" name="phone" placeholder="<?php echo esc_attr( silkway__( 'Phone number', 'Телефон' ) ); ?>" required>
				</div>
				<div class="form-row">
					<input type="email" name="email" placeholder="<?php echo esc_attr( silkway__( 'Email', 'Email' ) ); ?>">
					<input type="number" name="guests" min="1" value="2" placeholder="<?php echo esc_attr( silkway__( 'Guests', 'Гости' ) ); ?>">
				</div>
				<textarea name="message" rows="4" placeholder="<?php echo esc_attr( silkway__( 'Tell us about your travel plans', 'Расскажите о планах поездки' ) ); ?>"></textarea>
				<label class="agree">
					<input type="checkbox" required>
					<span><?php echo esc_html( silkway__( 'I agree to the processing of personal data according to the', 'Я даю согласие на обработку персональных данных согласно' ) ); ?> <a href="<?php echo esc_url( silkway_page_url( 'privacy-policy' ) ); ?>"><?php silkway_e( 'privacy policy', 'политике конфиденциальности' ); ?></a></span>
				</label>
				<button class="btn btn-enquiry" type="submit"><?php silkway_e( 'Send request', 'Отправить' ); ?></button>
			</form>
		</div>
	</div>
</section>

<footer class="site-footer">
	<div class="container">
		<div class="footer-grid">
			<div class="footer-brand">
				<a class="logo" href="<?php echo esc_url( silkway_home_url() ); ?>">
					<img src="<?php echo esc_url( silkway_img( 'logo.png' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>">
				</a>
				<p><?php silkway_e( 'Silk Way Travel — part of Silk Way Group. Discover Central Asia with local expertise since 2016.', 'Silk Way Travel — часть Silk Way Group. Открывайте Центральную Азию с местной экспертизой с 2016 года.' ); ?></p>
				<p class="footer-contacts">
					<a href="tel:+996772020222"><?php echo esc_html( silkway_phone() ); ?></a><br>
					<a href="mailto:<?php echo esc_attr( silkway_email() ); ?>"><?php echo esc_html( silkway_email() ); ?></a><br>
					<?php echo esc_html( silkway_address() ); ?>
				</p>
			</div>
			<div>
				<h4><?php silkway_e( 'Tours', 'Туры' ); ?></h4>
				<ul>
					<?php
					$tour_q = new WP_Query( array(
						'post_type'      => 'tour',
						'posts_per_page' => 6,
						'orderby'        => 'menu_order title',
						'order'          => 'ASC',
						'lang'           => silkway_lang(),
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
					<li><a href="<?php echo esc_url( silkway_page_url( 'corporate-tours' ) ); ?>"><?php silkway_e( 'Corporate Tours', 'Корпоративные туры' ); ?></a></li>
				</ul>
			</div>
			<div>
				<h4><?php silkway_e( 'Company', 'Компания' ); ?></h4>
				<ul>
					<li><a href="<?php echo esc_url( silkway_page_url( 'about' ) ); ?>"><?php silkway_e( 'About', 'О нас' ); ?></a></li>
					<li><a href="<?php echo esc_url( silkway_page_url( 'blog' ) ); ?>"><?php silkway_e( 'Blog', 'Блог' ); ?></a></li>
					<li><a href="<?php echo esc_url( get_post_type_archive_link( 'review' ) ); ?>"><?php silkway_e( 'Reviews', 'Отзывы' ); ?></a></li>
					<li><a href="<?php echo esc_url( silkway_page_url( 'gallery' ) ); ?>"><?php silkway_e( 'Gallery', 'Галерея' ); ?></a></li>
					<li><a href="<?php echo esc_url( silkway_page_url( 'services' ) ); ?>"><?php silkway_e( 'Services', 'Услуги' ); ?></a></li>
					<li><a href="<?php echo esc_url( silkway_page_url( 'privacy-policy' ) ); ?>"><?php silkway_e( 'Privacy Policy', 'Политика конфиденциальности' ); ?></a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="copy">
		<div class="container">
			<p><?php silkway_e( 'All rights reserved', 'Все права защищены' ); ?> &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> silkwaytravel.asia</p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
