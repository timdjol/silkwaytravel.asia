<?php
/**
 * Bootstrap Polylang: activate, EN/RU languages, assign content, create RU translations.
 * Run: php setup-polylang.php
 */

$_SERVER['HTTP_HOST']   = $_SERVER['HTTP_HOST'] ?? 'localhost:8888';
$_SERVER['REQUEST_URI'] = $_SERVER['REQUEST_URI'] ?? '/silkwaytravel/';
$_SERVER['SERVER_NAME'] = $_SERVER['SERVER_NAME'] ?? 'localhost';
$_SERVER['SERVER_PORT'] = $_SERVER['SERVER_PORT'] ?? '8888';
$_SERVER['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$_SERVER['SCRIPT_NAME'] = $_SERVER['SCRIPT_NAME'] ?? '/silkwaytravel/index.php';

// Force Polylang admin context in CLI so it boots before languages exist.
if ( ! defined( 'WP_CLI' ) ) {
	define( 'WP_CLI', true );
}

define( 'WP_USE_THEMES', false );
require __DIR__ . '/wp-load.php';

if ( php_sapi_name() === 'cli' ) {
	wp_set_current_user( 1 );
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';

$plugin = 'polylang/polylang.php';
if ( ! is_plugin_active( $plugin ) ) {
	$r = activate_plugin( $plugin );
	if ( is_wp_error( $r ) ) {
		fwrite( STDERR, 'Activate error: ' . $r->get_error_message() . PHP_EOL );
		exit( 1 );
	}
	echo "Polylang activated.\n";
} else {
	echo "Polylang already active.\n";
}

// Reload Polylang objects after activation.
if ( ! function_exists( 'PLL' ) ) {
	fwrite( STDERR, "PLL() unavailable. Open WP admin once, then re-run.\n" );
	exit( 1 );
}

$model = PLL()->model;

function silkway_pll_ensure_language( $args ) {
	$model = PLL()->model;
	if ( $model->get_language( $args['slug'] ) ) {
		echo "Language {$args['slug']} exists.\n";
		return;
	}
	$result = $model->add_language( $args );
	if ( is_wp_error( $result ) ) {
		fwrite( STDERR, $result->get_error_message() . PHP_EOL );
		return;
	}
	echo "Added language {$args['slug']}.\n";
}

silkway_pll_ensure_language( array(
	'name'       => 'English',
	'slug'       => 'en',
	'locale'     => 'en_US',
	'rtl'        => 0,
	'flag'       => 'us',
	'term_group' => 0,
) );

silkway_pll_ensure_language( array(
	'name'       => 'Русский',
	'slug'       => 'ru',
	'locale'     => 'ru_RU',
	'rtl'        => 0,
	'flag'       => 'ru',
	'term_group' => 1,
) );

// Default language EN.
if ( method_exists( $model, 'update_default_lang' ) ) {
	$model->update_default_lang( 'en' );
} else {
	update_option( 'polylang', array_merge( (array) get_option( 'polylang', array() ), array( 'default_lang' => 'en' ) ) );
}

// Force Polylang options for CPT/tax.
$options = get_option( 'polylang', array() );
$options['default_lang'] = 'en';
$options['force_lang']   = 1;
$options['rewrite']      = 1;
$options['hide_default'] = 1;
$options['browser']      = 0;
$options['media_support'] = 0;
$options['post_types']   = array_values( array_unique( array_merge( (array) ( $options['post_types'] ?? array() ), array( 'tour', 'review' ) ) ) );
$options['taxonomies']   = array_values( array_unique( array_merge( (array) ( $options['taxonomies'] ?? array() ), array( 'tour_type' ) ) ) );
update_option( 'polylang', $options );

// Clean language cache.
if ( method_exists( $model, 'clean_languages_cache' ) ) {
	$model->clean_languages_cache();
}

/**
 * Assign EN to content without language.
 */
function silkway_assign_en( $post_types ) {
	$q = new WP_Query( array(
		'post_type'      => $post_types,
		'post_status'    => array( 'publish', 'draft', 'private' ),
		'posts_per_page' => -1,
		'lang'           => '',
		'fields'         => 'ids',
	) );
	foreach ( $q->posts as $id ) {
		$current = function_exists( 'pll_get_post_language' ) ? pll_get_post_language( $id ) : false;
		if ( ! $current ) {
			pll_set_post_language( $id, 'en' );
			echo "Assigned EN to #{$id}\n";
		}
	}
}

silkway_assign_en( array( 'page', 'post', 'tour', 'review' ) );

/**
 * Create or update RU translation linked to EN post.
 */
function silkway_upsert_translation( $en_id, $title, $slug, $content, $excerpt = '', $meta = array(), $template = '' ) {
	$en_id = (int) $en_id;
	if ( ! $en_id ) {
		return 0;
	}

	$ru_id = function_exists( 'pll_get_post' ) ? pll_get_post( $en_id, 'ru' ) : 0;
	$data  = array(
		'post_title'   => $title,
		'post_name'    => $slug,
		'post_content' => $content,
		'post_excerpt' => $excerpt,
		'post_status'  => 'publish',
		'post_type'    => get_post_type( $en_id ),
	);

	if ( $ru_id ) {
		$data['ID'] = $ru_id;
		$ru_id      = wp_update_post( $data, true );
	} else {
		$ru_id = wp_insert_post( $data, true );
	}

	if ( is_wp_error( $ru_id ) ) {
		fwrite( STDERR, $ru_id->get_error_message() . PHP_EOL );
		return 0;
	}

	pll_set_post_language( $ru_id, 'ru' );
	pll_save_post_translations( array(
		'en' => $en_id,
		'ru' => (int) $ru_id,
	) );

	foreach ( $meta as $key => $value ) {
		update_post_meta( $ru_id, $key, $value );
	}

	if ( $template ) {
		update_post_meta( $ru_id, '_wp_page_template', $template );
	}

	// Copy featured image.
	$thumb = get_post_thumbnail_id( $en_id );
	if ( $thumb ) {
		set_post_thumbnail( $ru_id, $thumb );
	}

	echo "RU translation ready: {$title} (#{$ru_id})\n";
	return (int) $ru_id;
}

function silkway_en_page( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? (int) $page->ID : 0;
}

function silkway_en_cpt( $type, $slug ) {
	$post = get_page_by_path( $slug, OBJECT, $type );
	return $post ? (int) $post->ID : 0;
}

echo "Creating RU pages...\n";

$home_en = (int) get_option( 'page_on_front' );
if ( ! $home_en ) {
	$home_en = silkway_en_page( 'home' );
}
if ( $home_en ) {
	silkway_upsert_translation(
		$home_en,
		'Главная',
		'glavnaya',
		'',
		'Silk Way Travel — путешествия ради воспоминаний!'
	);
}

silkway_upsert_translation(
	silkway_en_page( 'about' ),
	'О нас',
	'o-nas',
	'<div class="soft-band__card" style="margin-bottom:40px;"><div><p class="eyebrow">Наша миссия</p><h2>Путешествия, которые ощущаются местными, понятными и запоминающимися</h2><p>С 2016 года мы выстроили партнёрства с отелями, гидами и поставщиками услуг по региону. Это помогает предлагать сильные решения для FIT, групп и B2B-партнёров.</p><p>От Иссык-Куля и Сон-Куля до Самарканда и Памирского тракта — мы делаем акцент на аутентичном опыте и прозрачном планировании.</p></div></div>',
	'Часть Silk Way Group. Мы создаём путешествия, где природа, культура и надёжная логистика работают вместе.'
);

silkway_upsert_translation(
	silkway_en_page( 'blog' ),
	'Блог',
	'blog-ru',
	'',
	'Заметки о путешествиях по Кыргызстану и Центральной Азии.'
);

silkway_upsert_translation(
	silkway_en_page( 'gallery' ),
	'Галерея',
	'galereya',
	'',
	'Фотогалерея туров Silk Way Travel.',
	array(),
	'page-gallery.php'
);

silkway_upsert_translation(
	silkway_en_page( 'services' ),
	'Услуги',
	'uslugi',
	'',
	'Организация туров и поддержка бронирований.',
	array(),
	'page-services.php'
);

silkway_upsert_translation(
	silkway_en_page( 'corporate-tours' ),
	'Корпоративные туры',
	'korporativnye-tury',
	'<div class="content-block"><h2>Форматы для команд</h2><ul><li>Incentive-поездки на Иссык-Куль и в горные локации</li><li>Культурные программы в Бишкеке с тимбилдингом</li><li>Смешанные активные дни: хайкинг, кони, лодки, вечера в юртах</li><li>Поддержка конференций: трансферы, площадки и leisure-блоки</li></ul></div><div class="cta-banner" style="margin-top:40px;"><h2>Запросить корпоративное предложение</h2><p>Пришлите даты, количество участников и регион — подготовим варианты и оценку.</p><a class="btn btn-light" href="#enquiry">Оставить заявку</a></div>',
	'Корпоративные туры и командные приключения в Кыргызстане.'
);

silkway_upsert_translation(
	silkway_en_page( 'privacy-policy' ),
	'Политика конфиденциальности',
	'politika-konfidencialnosti',
	'<h2>Какие данные мы собираем</h2><p>Имя, телефон, email, предпочтения по поездке и любая информация, которую вы добровольно указываете в формах и переписке.</p><h2>Как используем данные</h2><p>Для ответа на запросы, подготовки предложений, улучшения сервиса и исполнения договорных обязательств.</p><h2>Контакты</h2><p>Вопросы по конфиденциальности: info@silkwaytravel.asia</p>',
	'Как Silk Way Travel обрабатывает персональные данные.'
);

echo "Creating RU tours...\n";

$default_included_ru = "Трансферы аэропорт\nТранспорт по маршруту\nПроживание\nЗавтраки\nАнгло-/русскоговорящий гид\nВходные билеты по программе";
$default_excluded_ru = "Международные авиабилеты\nОбеды и ужины\nСтраховка\nЛичные расходы\nОпциональные активности";

$tours_ru = array(
	array(
		'kyrgyzstan-highlights',
		'Главные красоты Кыргызстана',
		'glavnye-krasoty-kyrgyzstana',
		'8-дневное путешествие по Бишкеку, Иссык-Кулю и горным локациям — комфортный старт для первого визита и активных путешественников.',
		'Культура, природа и активный отдых вокруг Бишкека, Иссык-Куля и горных долин.',
		array(
			'_tour_duration'   => '8 дней / 7 ночей',
			'_tour_price'      => '$890',
			'_tour_old_price'  => '$980',
			'_tour_start'      => 'Бишкек',
			'_tour_type_label' => 'Комбинированный',
			'_tour_badge'      => '8 дней',
			'_tour_included'   => $default_included_ru,
			'_tour_excluded'   => $default_excluded_ru,
			'_tour_program'    => "Прилёт в Бишкек|Трансфер, знакомство с городом и ночёвка в Бишкеке.\nБишкек – Бурана – Иссык-Куль|Башня Бурана и переезд на северный берег.\nУщелья Григорьевка / Семёновка|Лёгкий хайкинг и опциональная конная прогулка.\nКаньон Сказка и горячие источники|Цветные скалы и термальный отдых.\nКаракол и культурный вечер|Городские локации и опциональный фольклор.\nДжеты-Огуз и возвращение на запад|Красные скалы и дорога вдоль озера.\nМузей под открытым небом и Бишкек|Петроглифы и возвращение в столицу.\nВылет|Трансфер в аэропорт Манас.",
			'_tour_dates'      => "10–17 июн 2026|Доступен|$890\n05–12 июл 2026|Доступен|$920\n12–19 авг 2026|Мало мест|$920\n02–09 сен 2026|Доступен|$890",
		),
	),
	array(
		'southern-kyrgyzstan',
		'Юг Кыргызстана',
		'yug-kyrgyzstana',
		'Ош, Арсланбаб и Сары-Челек — ореховые леса, озёра и южное гостеприимство.',
		'Ош, Арсланбаб и Сары-Челек для любителей природы и культуры.',
		array(
			'_tour_duration'   => '6 дней / 5 ночей',
			'_tour_price'      => '$790',
			'_tour_start'      => 'Бишкек / Ош',
			'_tour_type_label' => 'Культура и природа',
			'_tour_badge'      => '6 дней',
			'_tour_included'   => $default_included_ru,
			'_tour_excluded'   => $default_excluded_ru,
			'_tour_program'    => "Перелёт в Ош|Трансфер и знакомство с городом.\nСулайман-Тоо|Объект ЮНЕСКО и базар.\nАрсланбаб|Прогулки по ореховому лесу.\nСары-Челек|День у озера и видовые точки.\nВозвращение|Горная дорога и ночёвка.\nВылет|Трансфер в аэропорт.",
			'_tour_dates'      => "15–20 июн 2026|Доступен|$790\n20–25 июл 2026|Доступен|$820",
		),
	),
	array(
		'nomadic-life-son-kul',
		'Кочевая жизнь — Сон-Куль',
		'kochevaya-zhizn-son-kul',
		'Погружение в юрточную культуру на высокогорном плато озера Сон-Куль.',
		'Проживание в юртах и высокогорные пейзажи Сон-Куля.',
		array(
			'_tour_duration'   => '4 дня / 3 ночи',
			'_tour_price'      => '$490',
			'_tour_start'      => 'Бишкек',
			'_tour_type_label' => 'Культурный',
			'_tour_badge'      => '4 дня',
			'_tour_included'   => $default_included_ru,
			'_tour_excluded'   => $default_excluded_ru,
			'_tour_program'    => "Бишкек – Сон-Куль|Переезд на плато и юрточный лагерь.\nДень кочевников|Конные опции и прогулки у озера.\nЖизнь в юрте|Местная кухня и культурный обмен.\nВозвращение в Бишкек|Живописный трансфер.",
			'_tour_dates'      => "01–04 июл 2026|Доступен|$490\n10–13 авг 2026|Доступен|$520",
		),
	),
	array(
		'horse-riding-tour',
		'Конный тур',
		'konnyj-tur',
		'Активное конное приключение по следам кочевников.',
		'Маршрут по альпийским пастбищам с опытными местными гидами.',
		array(
			'_tour_duration'   => '7 дней / 6 ночей',
			'_tour_price'      => '$950',
			'_tour_start'      => 'Бишкек',
			'_tour_type_label' => 'Приключение',
			'_tour_badge'      => '7 дней',
			'_tour_included'   => $default_included_ru . "\nАренда лошадей и конный гид",
			'_tour_excluded'   => $default_excluded_ru,
			'_tour_program'    => "Прилёт и брифинг|Знакомство с командой и подготовка.\nПервый конный день|Пастбища и лагерь.\nВысокая долина|Длинный день в седле.\nОтдых и культура|Село и более лёгкая активность.\nАльпийская петля|Хребты и озёра.\nОбратный путь|Спуск к дороге.\nВылет|Трансфер в Бишкек.",
			'_tour_dates'      => "08–14 июл 2026|Доступен|$950\n05–11 авг 2026|Доступен|$980",
		),
	),
	array(
		'kyrgyzstan-uzbekistan',
		'Кыргызстан – Узбекистан',
		'kyrgyzstan-uzbekistan-ru',
		'Комбинированный маршрут через горы и легендарные города Шёлкового пути.',
		'Горы и города Шёлкового пути: Самарканд, Бухара и природа Кыргызстана.',
		array(
			'_tour_duration'   => '15 дней / 14 ночей',
			'_tour_price'      => '$1 490',
			'_tour_start'      => 'Бишкек / Ташкент',
			'_tour_type_label' => 'Комбинированный',
			'_tour_badge'      => '15 дней',
			'_tour_included'   => $default_included_ru,
			'_tour_excluded'   => $default_excluded_ru,
			'_tour_program'    => "Блок природы Кыргызстана|Иссык-Куль и горные локации.\nПересечение границы|Переезд в Узбекистан.\nСамарканд|Регистан и наследие Шёлкового пути.\nБухара|Прогулки по старому городу.\nВозвращение|Трансферы и вылет.",
			'_tour_dates'      => "01–15 сен 2026|Доступен|$1 490",
		),
	),
);

foreach ( $tours_ru as $tour ) {
	$en_id = silkway_en_cpt( 'tour', $tour[0] );
	if ( ! $en_id ) {
		echo "Skip missing EN tour {$tour[0]}\n";
		continue;
	}
	silkway_upsert_translation( $en_id, $tour[1], $tour[2], '<p>' . esc_html( $tour[3] ) . '</p>', $tour[4], $tour[5] );
}

echo "Creating RU reviews...\n";
$reviews_ru = array(
	array( 'elena-k', 'Елена К.', 'elena-k-ru', 'Этот тур стал для нас настоящим открытием Кыргызстана. За короткое время мы увидели Бишкек и лучшие локации Иссык-Куля.', 'Екатеринбург' ),
	array( 'larisa', 'Лариса', 'larisa-ru', 'Спасибо за организацию отдыха. Водитель был внимательным, а темп подошёл семье с детьми.', 'Краснодар' ),
	array( 'dmitry', 'Дмитрий', 'dmitrij-ru', 'Корпоративная группа из 14 человек — логистика была чёткой, вечера продуманы, все остались довольны.', 'Москва' ),
	array( 'maria', 'Мария', 'mariya-ru', 'Конные дни превзошли ожидания. Гиды всё объясняли, маршруты были безопасными и красивыми.', 'Алматы' ),
	array( 'igor', 'Игорь', 'igor-ru', 'Юг Кыргызстана стал изюминкой — Арсланбаб и Сары-Челек были отлично таймингово выстроены.', 'Санкт-Петербург' ),
	array( 'anna', 'Анна', 'anna-ru', 'Понятная коммуникация до прилёта и гибкие изменения при погоде. Очень рекомендуем.', 'Астана' ),
);
foreach ( $reviews_ru as $review ) {
	$en_id = silkway_en_cpt( 'review', $review[0] );
	if ( ! $en_id ) {
		// try by title match
		$found = get_posts( array( 'post_type' => 'review', 'title' => str_replace( array( '-ru' ), '', $review[1] ), 'posts_per_page' => 1, 'lang' => 'en' ) );
		// fallback: get all EN reviews by slug variants
		$en_id = silkway_en_cpt( 'review', $review[0] );
	}
	if ( ! $en_id ) {
		// create mapping via known english titles from setup
		continue;
	}
	silkway_upsert_translation(
		$en_id,
		$review[1],
		$review[2],
		$review[3],
		'',
		array(
			'_review_city'   => $review[4],
			'_review_rating' => 5,
		)
	);
}

// Link EN reviews by querying all and matching order if slug missing.
$en_reviews = get_posts( array( 'post_type' => 'review', 'posts_per_page' => -1, 'lang' => 'en', 'orderby' => 'ID', 'order' => 'ASC' ) );
foreach ( $en_reviews as $i => $en_review ) {
	if ( empty( $reviews_ru[ $i ] ) ) {
		continue;
	}
	$existing_ru = pll_get_post( $en_review->ID, 'ru' );
	if ( $existing_ru ) {
		continue;
	}
	$r = $reviews_ru[ $i ];
	silkway_upsert_translation(
		$en_review->ID,
		$r[1],
		$r[2],
		$r[3],
		'',
		array(
			'_review_city'   => $r[4],
			'_review_rating' => 5,
		)
	);
}

echo "Creating RU blog posts...\n";
$posts_ru = array(
	array( 'best-time-issyk-kul', 'Когда лучше ехать на Иссык-Куль', 'kogda-luchshe-ehat-na-issyk-kul', 'Когда озеро тёплое, тропы открыты, а в межсезонье спокойнее. Лето популярно для купания, май–июнь и сентябрь отличны для хайкинга и фото.' ),
	array( 'yurt-stay', 'Что ждать от проживания в юрте', 'chto-zhdat-ot-prozhivaniya-v-yurte', 'Советы по комфорту, этикету и ночам на плато Сон-Куль. Возьмите тёплые слои и налобный фонарь.' ),
	array( 'kg-uz-combined', 'Кыргызстан + Узбекистан в одной поездке', 'kyrgyzstan-uzbekistan-v-odnoj-poezdke', 'Как совместить горы и города Шёлкового пути без спешки. Обычно хорошо работает маршрут на 12–15 дней.' ),
);
foreach ( $posts_ru as $post ) {
	$en = get_page_by_path( $post[0], OBJECT, 'post' );
	if ( ! $en ) {
		echo "Skip missing EN post {$post[0]}\n";
		continue;
	}
	silkway_upsert_translation( $en->ID, $post[1], $post[2], '<p>' . esc_html( $post[3] ) . '</p>' );
}

flush_rewrite_rules( false );
echo "Done. Open Languages in WP admin if needed: " . admin_url( 'admin.php?page=mlang' ) . "\n";
echo "RU home: " . ( function_exists( 'pll_home_url' ) ? pll_home_url( 'ru' ) : '/ru/' ) . "\n";
