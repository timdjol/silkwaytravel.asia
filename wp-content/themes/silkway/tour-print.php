<?php
/**
 * Printable tour program (browser → Save as PDF).
 *
 * @package silkway
 */

if ( ! have_posts() ) {
	return;
}
the_post();

$duration = get_post_meta( get_the_ID(), '_tour_duration', true );
$price    = get_post_meta( get_the_ID(), '_tour_price', true );
$start    = get_post_meta( get_the_ID(), '_tour_start', true );
$included = silkway_lines( get_post_meta( get_the_ID(), '_tour_included', true ) );
$excluded = silkway_lines( get_post_meta( get_the_ID(), '_tour_excluded', true ) );
$program  = silkway_lines( get_post_meta( get_the_ID(), '_tour_program', true ) );
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo esc_html( get_the_title() . ' — ' . silkway__( 'Tour program', 'Программа тура' ) ); ?></title>
	<style>
		body{font-family:Arial,Helvetica,sans-serif;color:#1d2a36;line-height:1.5;margin:0;padding:32px;max-width:860px}
		h1{color:#015cab;margin:0 0 8px;font-size:28px}
		h2{color:#015cab;margin:28px 0 12px;font-size:18px;border-bottom:2px solid #5bbfd0;padding-bottom:6px}
		.meta{color:#6b7a88;margin-bottom:20px}
		.meta span{display:inline-block;margin-right:14px}
		.day{display:flex;gap:14px;margin:0 0 14px;page-break-inside:avoid}
		.num{min-width:36px;height:36px;border-radius:50%;background:#015cab;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700}
		ul{padding-left:18px}
		.brand{margin-top:36px;padding-top:16px;border-top:1px solid #d7e0ea;color:#6b7a88;font-size:13px}
		@media print{.no-print{display:none!important}body{padding:12px}}
	</style>
</head>
<body>
	<p class="no-print"><button onclick="window.print()"><?php silkway_e( 'Print / Save as PDF', 'Печать / Сохранить PDF' ); ?></button></p>
	<h1><?php the_title(); ?></h1>
	<p class="meta">
		<?php if ( $duration ) : ?><span><?php echo esc_html( $duration ); ?></span><?php endif; ?>
		<?php if ( $start ) : ?><span><?php silkway_e( 'Start:', 'Старт:' ); ?> <?php echo esc_html( $start ); ?></span><?php endif; ?>
		<?php if ( $price ) : ?><span><?php silkway_e( 'from', 'от' ); ?> <?php echo esc_html( $price ); ?></span><?php endif; ?>
	</p>
	<?php if ( has_excerpt() ) : ?><p><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>

	<?php if ( $program ) : ?>
		<h2><?php silkway_e( 'Tour program', 'Программа тура' ); ?></h2>
		<?php foreach ( $program as $index => $line ) :
			$parts = array_map( 'trim', explode( '|', $line, 2 ) );
			?>
			<div class="day">
				<div class="num"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></div>
				<div>
					<strong><?php echo esc_html( $parts[0] ?? '' ); ?></strong>
					<?php if ( ! empty( $parts[1] ) ) : ?><div><?php echo esc_html( $parts[1] ); ?></div><?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>

	<?php if ( $included ) : ?>
		<h2><?php silkway_e( 'Included', 'Включено' ); ?></h2>
		<ul><?php foreach ( $included as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?></ul>
	<?php endif; ?>

	<?php if ( $excluded ) : ?>
		<h2><?php silkway_e( 'Not included', 'Не включено' ); ?></h2>
		<ul><?php foreach ( $excluded as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?></ul>
	<?php endif; ?>

	<div class="brand">
		<strong>Silk Way Travel</strong><br>
		<?php echo esc_html( silkway_phone() ); ?> · <?php echo esc_html( silkway_email() ); ?><br>
		<?php echo esc_html( silkway_address() ); ?>
	</div>
	<script>window.addEventListener('load',function(){setTimeout(function(){window.print();},400);});</script>
</body>
</html>
