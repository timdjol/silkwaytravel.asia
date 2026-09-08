<?php
/**
 * Single tour.
 *
 * @package silkway
 */

get_header();

while ( have_posts() ) :
	the_post();
	$duration = get_post_meta( get_the_ID(), '_tour_duration', true );
	$price    = get_post_meta( get_the_ID(), '_tour_price', true );
	$start    = get_post_meta( get_the_ID(), '_tour_start', true );
	$type     = get_post_meta( get_the_ID(), '_tour_type_label', true );
	$included = silkway_lines( get_post_meta( get_the_ID(), '_tour_included', true ) );
	$excluded = silkway_lines( get_post_meta( get_the_ID(), '_tour_excluded', true ) );
	$program  = silkway_lines( get_post_meta( get_the_ID(), '_tour_program', true ) );
	$dates    = silkway_lines( get_post_meta( get_the_ID(), '_tour_dates', true ) );
	$image    = silkway_tour_image_url( get_the_ID(), 'serv1.jpg' );
	?>
	<section class="page-hero" style="background-image:url(<?php echo esc_url( $image ); ?>);">
		<div class="container">
			<div class="breadcrumb">
				<a href="<?php echo esc_url( silkway_home_url() ); ?>"><?php silkway_e( 'Home', 'Главная' ); ?></a> ·
				<a href="<?php echo esc_url( get_post_type_archive_link( 'tour' ) ); ?>"><?php silkway_e( 'Tours', 'Туры' ); ?></a> ·
				<span><?php the_title(); ?></span>
			</div>
			<h1><?php the_title(); ?></h1>
			<p><?php echo esc_html( get_the_excerpt() ); ?></p>
			<div class="tour-hero-meta">
				<?php if ( $duration ) : ?><span><?php echo esc_html( $duration ); ?></span><?php endif; ?>
				<?php if ( $start ) : ?><span><?php silkway_e( 'Start:', 'Старт:' ); ?> <?php echo esc_html( $start ); ?></span><?php endif; ?>
				<?php if ( $type ) : ?><span><?php silkway_e( 'Type:', 'Тип:' ); ?> <?php echo esc_html( $type ); ?></span><?php endif; ?>
				<?php if ( $price ) : ?><span><?php silkway_e( 'from', 'от' ); ?> <?php echo esc_html( $price ); ?></span><?php endif; ?>
			</div>
			<div class="btn-group" style="justify-content:flex-start;margin-top:24px;">
				<a class="btn btn-accent" href="#enquiry"><?php silkway_e( 'Leave a request', 'Оставить заявку' ); ?></a>
				<a class="btn btn-outline" href="#program"><?php silkway_e( 'Tour program', 'Программа тура' ); ?></a>
			</div>
		</div>
	</section>

	<section class="page-section" id="program">
		<div class="container">
			<div class="content-block" data-aos="fade-up">
				<?php the_content(); ?>
			</div>

			<?php if ( $program ) : ?>
				<div class="content-block" data-aos="fade-up">
					<h2><?php silkway_e( 'Tour program', 'Программа тура' ); ?></h2>
					<?php foreach ( $program as $index => $line ) :
						$parts = array_map( 'trim', explode( '|', $line, 2 ) );
						$title = $parts[0] ?? '';
						$desc  = $parts[1] ?? '';
						?>
						<div class="program-day">
							<div class="program-day__num"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></div>
							<div>
								<h3><?php echo esc_html( $title ); ?></h3>
								<?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( $included || $excluded ) : ?>
				<div class="include-grid" data-aos="fade-up">
					<div class="include-card">
						<h3><?php silkway_e( 'Included', 'Включено' ); ?></h3>
						<ul>
							<?php foreach ( $included as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?>
						</ul>
					</div>
					<div class="include-card exclude">
						<h3><?php silkway_e( 'Not included', 'Не включено' ); ?></h3>
						<ul>
							<?php foreach ( $excluded as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?>
						</ul>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $dates ) : ?>
				<div class="content-block" style="margin-top:50px;" data-aos="fade-up">
					<h2><?php silkway_e( 'Departure dates', 'Даты выезда' ); ?></h2>
					<table class="dates-table">
						<thead>
							<tr>
								<th><?php silkway_e( 'Dates', 'Даты' ); ?></th>
								<th><?php silkway_e( 'Status', 'Статус' ); ?></th>
								<th><?php silkway_e( 'Price', 'Цена' ); ?></th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ( $dates as $line ) :
							$parts = array_map( 'trim', explode( '|', $line ) );
							?>
							<tr>
								<td><?php echo esc_html( $parts[0] ?? '' ); ?></td>
								<td><?php echo esc_html( $parts[1] ?? '' ); ?></td>
								<td><?php echo esc_html( $parts[2] ?? '' ); ?></td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>

			<div class="content-block" data-aos="fade-up">
				<h2><?php silkway_e( 'Bright moments', 'Яркие моменты' ); ?></h2>
				<div class="gallery gallery-grid">
					<?php foreach ( array( 'serv1.jpg', 'serv2.jpg', 'serv3.jpg', 'serv4.jpg', 'bg.jpg', 'partner.jpg' ) as $img ) : ?>
						<a href="<?php echo esc_url( silkway_img( $img ) ); ?>" style="background-image:url(<?php echo esc_url( silkway_img( $img ) ); ?>);"></a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
