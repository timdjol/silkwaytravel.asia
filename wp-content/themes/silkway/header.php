<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="theme-color" content="#015cab">
	<?php wp_head(); ?>
</head>
<body <?php body_class( is_front_page() ? 'has-hero' : '' ); ?>>
<?php wp_body_open(); ?>

<header class="site-header" data-header>
	<div class="header-top">
		<div class="container">
			<div class="header-top__row">
				<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<img src="<?php echo esc_url( silkway_img( 'logo.png' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>">
					<?php endif; ?>
				</a>
				<div class="header-top__actions">
					<a class="header-phone" href="tel:+996772020222"><?php echo esc_html( silkway_phone() ); ?></a>
					<a class="header-mail d-none d-lg-inline" href="mailto:<?php echo esc_attr( silkway_email() ); ?>"><?php echo esc_html( silkway_email() ); ?></a>
					<div class="messengers d-none d-md-flex">
						<a href="https://wa.me/996772020222" target="_blank" rel="noopener" aria-label="WhatsApp">WA</a>
						<a href="https://t.me/" target="_blank" rel="noopener" aria-label="Telegram">TG</a>
					</div>
					<div class="lang-switch">
						<a class="is-active" href="<?php echo esc_url( home_url( '/' ) ); ?>">EN</a>
						<a href="#">RU</a>
					</div>
					<button class="toggle-mnu d-lg-none" type="button" aria-label="Menu"><span></span></button>
				</div>
			</div>
		</div>
	</div>
	<div class="header-nav">
		<div class="container">
			<nav class="main-nav" data-nav>
				<ul>
					<li class="has-dropdown">
						<a href="<?php echo esc_url( get_post_type_archive_link( 'tour' ) ); ?>">Tours</a>
						<ul class="dropdown">
							<?php
							$tour_q = new WP_Query( array(
								'post_type'      => 'tour',
								'posts_per_page' => 8,
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
					</li>
					<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li>
					<li><a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>">Blog</a></li>
					<li><a href="<?php echo esc_url( get_post_type_archive_link( 'review' ) ); ?>">Reviews</a></li>
					<li><a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>">Gallery</a></li>
					<li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Services</a></li>
					<li class="nav-cta"><a class="btn btn-accent" href="#enquiry">Send enquiry</a></li>
				</ul>
			</nav>
		</div>
	</div>
</header>
