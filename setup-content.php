<?php
/**
 * One-time content bootstrap for Silk Way Travel.
 * Run: php setup-content.php
 */

define( 'WP_USE_THEMES', false );
require __DIR__ . '/wp-load.php';

if ( ! current_user_can( 'manage_options' ) && php_sapi_name() !== 'cli' ) {
	wp_die( 'Forbidden' );
}

// Allow CLI without auth.
if ( php_sapi_name() === 'cli' ) {
	wp_set_current_user( 1 );
}

function silkway_upsert_page( $title, $slug, $content = '', $template = '', $excerpt = '' ) {
	$existing = get_page_by_path( $slug );
	$data     = array(
		'post_title'   => $title,
		'post_name'    => $slug,
		'post_content' => $content,
		'post_excerpt' => $excerpt,
		'post_status'  => 'publish',
		'post_type'    => 'page',
	);
	if ( $existing ) {
		$data['ID'] = $existing->ID;
		$id         = wp_update_post( $data, true );
	} else {
		$id = wp_insert_post( $data, true );
	}
	if ( is_wp_error( $id ) ) {
		fwrite( STDERR, $id->get_error_message() . PHP_EOL );
		return 0;
	}
	if ( $template ) {
		update_post_meta( $id, '_wp_page_template', $template );
	}
	return (int) $id;
}

function silkway_upsert_cpt( $type, $title, $slug, $content, $excerpt, $meta = array() ) {
	$existing = get_page_by_path( $slug, OBJECT, $type );
	$data     = array(
		'post_title'   => $title,
		'post_name'    => $slug,
		'post_content' => $content,
		'post_excerpt' => $excerpt,
		'post_status'  => 'publish',
		'post_type'    => $type,
	);
	if ( $existing ) {
		$data['ID'] = $existing->ID;
		$id         = wp_update_post( $data, true );
	} else {
		$id = wp_insert_post( $data, true );
	}
	if ( is_wp_error( $id ) ) {
		fwrite( STDERR, $id->get_error_message() . PHP_EOL );
		return 0;
	}
	foreach ( $meta as $key => $value ) {
		update_post_meta( $id, $key, $value );
	}
	return (int) $id;
}

echo "Activating theme...\n";
switch_theme( 'silkway' );

echo "Creating pages...\n";
$home_id = silkway_upsert_page( 'Home', 'home', '', '', 'Silk Way Travel — Travel for memories!' );
$about_id = silkway_upsert_page(
	'About',
	'about',
	'<div class="soft-band__card" style="margin-bottom:40px;"><div><p class="eyebrow">Our mission</p><h2>Travel that feels local, clear and memorable</h2><p>Since 2016 we have built partnerships with hotels, guides and service providers across the region. That network helps us offer strong value for FIT, groups and B2B partners.</p><p>From Issyk-Kul and Son-Kul to Samarkand and the Pamir Highway, we focus on authentic experiences with transparent planning.</p></div></div>',
	'',
	'Part of Silk Way Group. We design journeys that combine nature, culture and reliable logistics across Central Asia.'
);
$blog_id = silkway_upsert_page( 'Blog', 'blog', '', '', 'Travel notes about Kyrgyzstan and Central Asia.' );
$gallery_id = silkway_upsert_page( 'Gallery', 'gallery', '', 'page-gallery.php', 'Photo gallery of Silk Way Travel tours.' );
$services_id = silkway_upsert_page( 'Services', 'services', '', 'page-services.php', 'Tour organization and booking support.' );
$corporate_id = silkway_upsert_page(
	'Corporate Tours',
	'corporate-tours',
	'<div class="content-block"><h2>Formats that work for teams</h2><ul><li>Incentive trips around Issyk-Kul and mountain resorts</li><li>Cultural city programs in Bishkek with team activities</li><li>Mixed adventure days: hiking, horses, boats, yurt evenings</li><li>Conference support with transfers, venues and leisure blocks</li></ul></div><div class="cta-banner" style="margin-top:40px;"><h2>Request a corporate proposal</h2><p>Send dates, headcount and preferred region — we will reply with options and estimate.</p><a class="btn btn-light" href="#enquiry">Send enquiry</a></div>',
	'',
	'Corporate tours and team adventures in Kyrgyzstan.'
);
$privacy_id = silkway_upsert_page(
	'Privacy Policy',
	'privacy-policy',
	'<h2>Data we collect</h2><p>Name, phone, email, travel preferences and any details you voluntarily share in enquiry forms or correspondence.</p><h2>How we use data</h2><p>To respond to requests, prepare tour proposals, improve our services and fulfill contractual obligations.</p><h2>Contact</h2><p>Questions about privacy: info@silkwaytravel.asia</p>',
	'',
	'How Silk Way Travel handles personal data.'
);

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $home_id );
update_option( 'page_for_posts', $blog_id );
update_option( 'blogdescription', 'Travel for memories!' );
update_option( 'permalink_structure', '/%postname%/' );
flush_rewrite_rules();

echo "Creating tours...\n";
$default_included = "Airport transfers\nTransport along the route\nAccommodation\nBreakfasts\nEnglish/Russian speaking guide\nEntrance fees according to program";
$default_excluded = "International flights\nLunches and dinners\nTravel insurance\nPersonal expenses\nOptional activities";

$tours = array(
	array(
		'Kyrgyzstan Highlights',
		'kyrgyzstan-highlights',
		'An 8-day journey through Bishkek, Issyk-Kul and mountain landscapes — balanced for first-time visitors and active travelers.',
		'Culture, nature and active leisure around Bishkek, Issyk-Kul and mountain valleys.',
		array(
			'_tour_duration'   => '8 days / 7 nights',
			'_tour_price'      => '$890',
			'_tour_old_price'  => '$980',
			'_tour_start'      => 'Bishkek',
			'_tour_type_label' => 'Combined',
			'_tour_badge'      => '8 days',
			'_tour_included'   => $default_included,
			'_tour_excluded'   => $default_excluded,
			'_tour_program'    => "Arrival in Bishkek|Airport transfer, city orientation and overnight in Bishkek.\nBishkek – Burana – Issyk-Kul|Visit Burana Tower and continue to the northern shore.\nGrigorievka / Semenovka gorges|Easy hiking in spruce forests and optional horse ride.\nFairy Tale Canyon & hot springs|Explore canyon formations and relax in thermal waters.\nKarakol & cultural evening|City highlights and optional folklore evening.\nJeti-Oguz & return west|Red rocks and scenic drive along the lake.\nOpen-air museum & Bishkek|Petroglyphs and transfer to the capital.\nDeparture|Transfer to Manas Airport.",
			'_tour_dates'      => "10–17 Jun 2026|Available|$890\n05–12 Jul 2026|Available|$920\n12–19 Aug 2026|Few seats|$920\n02–09 Sep 2026|Available|$890",
		),
	),
	array(
		'Southern Kyrgyzstan',
		'southern-kyrgyzstan',
		'Osh, Arslanbob and Sary-Chelek — walnut forests, lakes and southern hospitality.',
		'Osh, Arslanbob and Sary-Chelek for nature lovers and cultural explorers.',
		array(
			'_tour_duration'   => '6 days / 5 nights',
			'_tour_price'      => '$790',
			'_tour_start'      => 'Bishkek / Osh',
			'_tour_type_label' => 'Cultural-nature',
			'_tour_badge'      => '6 days',
			'_tour_included'   => $default_included,
			'_tour_excluded'   => $default_excluded,
			'_tour_program'    => "Flight to Osh|Transfer and city introduction.\nSacred mountain Sulaiman-Too|UNESCO site and local bazaar.\nArslanbob|Walnut forest walks and village stay.\nSary-Chelek|Lake day with scenic viewpoints.\nReturn west|Mountain road and overnight.\nDeparture|Transfer to airport.",
			'_tour_dates'      => "15–20 Jun 2026|Available|$790\n20–25 Jul 2026|Available|$820",
		),
	),
	array(
		'Nomadic Life – Son-Kul',
		'nomadic-life-son-kul',
		'Immerse yourself in yurt culture on the high plateau of Son-Kul lake.',
		'Yurt stays and highland landscapes on the Son-Kul plateau.',
		array(
			'_tour_duration'   => '4 days / 3 nights',
			'_tour_price'      => '$490',
			'_tour_start'      => 'Bishkek',
			'_tour_type_label' => 'Cultural',
			'_tour_badge'      => '4 days',
			'_tour_included'   => $default_included,
			'_tour_excluded'   => $default_excluded,
			'_tour_program'    => "Bishkek – Son-Kul|Drive to the plateau and yurt camp.\nNomadic day|Horse riding options and lake walks.\nLife in the yurt|Local meals and cultural exchange.\nReturn to Bishkek|Scenic transfer and departure prep.",
			'_tour_dates'      => "01–04 Jul 2026|Available|$490\n10–13 Aug 2026|Available|$520",
		),
	),
	array(
		'Horse Riding Tour',
		'horse-riding-tour',
		'Active horseback adventure following nomadic trails.',
		'Follow the nomads across alpine pastures with experienced local guides.',
		array(
			'_tour_duration'   => '7 days / 6 nights',
			'_tour_price'      => '$950',
			'_tour_start'      => 'Bishkek',
			'_tour_type_label' => 'Adventure',
			'_tour_badge'      => '7 days',
			'_tour_included'   => $default_included . "\nHorse rental and equestrian guide",
			'_tour_excluded'   => $default_excluded,
			'_tour_program'    => "Arrival & briefing|Meet the team and prepare for the trail.\nFirst riding day|Mountain pastures and overnight camp.\nHigh valley ride|Longer day in the saddle.\nRest & culture|Village visit and lighter activity.\nAlpine loop|Scenic ridgelines and lakes.\nReturn ride|Descent toward the road.\nDeparture|Transfer to Bishkek airport.",
			'_tour_dates'      => "08–14 Jul 2026|Available|$950\n05–11 Aug 2026|Available|$980",
		),
	),
	array(
		'Kyrgyzstan – Uzbekistan',
		'kyrgyzstan-uzbekistan',
		'Combined route through mountains and legendary Silk Road cities.',
		'Mountains meet Silk Road cities: Samarkand, Bukhara and Kyrgyz nature in one journey.',
		array(
			'_tour_duration'   => '15 days / 14 nights',
			'_tour_price'      => '$1 490',
			'_tour_start'      => 'Bishkek / Tashkent',
			'_tour_type_label' => 'Combined',
			'_tour_badge'      => '15 days',
			'_tour_included'   => $default_included,
			'_tour_excluded'   => $default_excluded,
			'_tour_program'    => "Kyrgyzstan nature block|Issyk-Kul and mountain highlights.\nBorder crossing|Transfer toward Uzbekistan.\nSamarkand|Registan and Silk Road heritage.\nBukhara|Old city walking days.\nReturn logistics|Comfortable transfers and departure.",
			'_tour_dates'      => "01–15 Sep 2026|Available|$1 490",
		),
	),
);

foreach ( $tours as $tour ) {
	silkway_upsert_cpt( 'tour', $tour[0], $tour[1], '<p>' . esc_html( $tour[2] ) . '</p>', $tour[3], $tour[4] );
	echo "  tour: {$tour[0]}\n";
}

echo "Creating reviews...\n";
$reviews = array(
	array( 'Elena K.', 'ekaterinburg', 'This tour became a real discovery of Kyrgyzstan. In a short time we saw Bishkek and the best Issyk-Kul locations.', 'Ekaterinburg', 5 ),
	array( 'Larisa', 'krasnodar', 'Thank you for organizing our holiday. Our driver was attentive and the pacing worked well for a family with kids.', 'Krasnodar', 5 ),
	array( 'Dmitry', 'moscow', 'Corporate group of 14 people — logistics were clear, evenings were thoughtfully planned, everyone was happy.', 'Moscow', 5 ),
	array( 'Maria', 'almaty', 'Horse riding days exceeded expectations. Guides explained everything and routes felt safe and scenic.', 'Almaty', 5 ),
	array( 'Igor', 'spb', 'South Kyrgyzstan was a highlight — Arslanbob and Sary-Chelek were beautifully timed.', 'Saint Petersburg', 5 ),
	array( 'Anna', 'astana', 'Clear communication before arrival and flexible changes when weather shifted. Highly recommended.', 'Astana', 5 ),
);
foreach ( $reviews as $review ) {
	silkway_upsert_cpt(
		'review',
		$review[0],
		$review[1],
		$review[2],
		'',
		array(
			'_review_city'   => $review[3],
			'_review_rating' => $review[4],
		)
	);
}

echo "Creating blog posts...\n";
$posts = array(
	array( 'Best time to visit Issyk-Kul', 'best-time-issyk-kul', 'When the lake is warm, trails are open, and shoulder seasons give quieter stays. Summer is popular for swimming, while May–June and September are excellent for hiking and photography.' ),
	array( 'What to expect in a yurt stay', 'yurt-stay', 'Comfort tips, etiquette and how highland nights really feel at Son-Kul. Pack warm layers, bring a headlamp, and enjoy the quiet of the plateau.' ),
	array( 'Kyrgyzstan + Uzbekistan in one trip', 'kg-uz-combined', 'How to combine mountains and Silk Road cities without rushing the highlights. A well-paced 12–15 day route usually works best.' ),
);
foreach ( $posts as $post ) {
	$existing = get_page_by_path( $post[1], OBJECT, 'post' );
	$data     = array(
		'post_title'   => $post[0],
		'post_name'    => $post[1],
		'post_content' => '<p>' . $post[2] . '</p>',
		'post_status'  => 'publish',
		'post_type'    => 'post',
	);
	if ( $existing ) {
		$data['ID'] = $existing->ID;
		wp_update_post( $data );
	} else {
		wp_insert_post( $data );
	}
}

// Delete Hello World if exists.
$hello = get_page_by_path( 'hello-world', OBJECT, 'post' );
if ( $hello ) {
	wp_delete_post( $hello->ID, true );
}

echo "Done.\n";
echo "Front: " . home_url( '/' ) . "\n";
echo "Admin: " . admin_url() . " (admin / admin123)\n";
