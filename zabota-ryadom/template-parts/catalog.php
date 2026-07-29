<?php
/**
 * Каталог пансионатов: 4 карточки.
 *
 * @package ZabotaRyadom
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pensions = array(
	array(
		'title'    => __( 'Пансионат «Забота и уют»', 'zabota-ryadom' ),
		'address'  => __( 'Москва, г. Саратово', 'zabota-ryadom' ),
		'price'    => '35 000',
		'rating'   => '4.9',
		'reviews'  => '127',
		'bg'       => '#FED7AA',
		'tags'     => array(
			array( 'icon' => '✓', 'text' => __( 'Реабилитация', 'zabota-ryadom' ) ),
			array( 'icon' => '👥', 'text' => __( '5-разовое питание', 'zabota-ryadom' ) ),
			array( 'icon' => '✓', 'text' => __( 'Есть свободные места', 'zabota-ryadom' ) ),
		),
	),
	array(
		'title'    => __( 'Дом «Тихая гавань»', 'zabota-ryadom' ),
		'address'  => __( 'Москва, г. Реутов', 'zabota-ryadom' ),
		'price'    => '40 000',
		'rating'   => '4.8',
		'reviews'  => '94',
		'bg'       => '#BFDBFE',
		'tags'     => array(
			array( 'icon' => '✓', 'text' => __( 'Деменция', 'zabota-ryadom' ) ),
			array( 'icon' => '👥', 'text' => __( 'Малые группы', 'zabota-ryadom' ) ),
			array( 'icon' => '✓', 'text' => __( 'Есть свободные места', 'zabota-ryadom' ) ),
		),
	),
	array(
		'title'    => __( 'Пансионат «Солнечный»', 'zabota-ryadom' ),
		'address'  => __( 'Москва, г. Видное', 'zabota-ryadom' ),
		'price'    => '30 000',
		'rating'   => '4.7',
		'reviews'  => '215',
		'bg'       => '#FECDD3',
		'tags'     => array(
			array( 'icon' => '✓', 'text' => __( 'После инсульта', 'zabota-ryadom' ) ),
			array( 'icon' => '🏥', 'text' => __( 'Медсестра 24/7', 'zabota-ryadom' ) ),
			array( 'icon' => '✕', 'text' => __( 'Нет мест', 'zabota-ryadom' ) ),
		),
	),
	array(
		'title'    => __( 'Реабилитационный центр «Здоровье»', 'zabota-ryadom' ),
		'address'  => __( 'Москва, г. Балашиха', 'zabota-ryadom' ),
		'price'    => '50 000',
		'rating'   => '4.9',
		'reviews'  => '178',
		'bg'       => '#C7D2FE',
		'tags'     => array(
			array( 'icon' => '✓', 'text' => __( 'Реабилитация', 'zabota-ryadom' ) ),
			array( 'icon' => '🏊', 'text' => __( 'Бассейн', 'zabota-ryadom' ) ),
			array( 'icon' => '✓', 'text' => __( 'Есть свободные места', 'zabota-ryadom' ) ),
		),
	),
);
?>

<section class="catalog-section">
	<div class="container">

		<div class="catalog-head">
			<div class="catalog-head-left">
				<h2 class="section-title"><?php esc_html_e( 'Лучшие пансионаты', 'zabota-ryadom' ); ?></h2>
				<p class="section-subtitle"><?php esc_html_e( 'Топ проверенных учреждений по отзывам семей', 'zabota-ryadom' ); ?></p>
			</div>
			<a href="#" class="link-arrow">
				<?php esc_html_e( 'Смотреть все', 'zabota-ryadom' ); ?>
				<svg width="14" height="14" viewBox="0 0 14 14" fill="none">
					<path d="M5 3l4 4-4 4" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</a>
		</div>

		<div class="catalog-grid">
			<?php foreach ( $pensions as $pension ) : ?>
				<article class="pension-card">
					<div class="pension-image" style="background: <?php echo esc_attr( $pension['bg'] ); ?>;">
						<!-- Иконка-дом как заглушка фото -->
						<svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg" style="opacity:0.5;">
							<path d="M10 38 40 12l30 26" stroke="#1F2937" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M16 32v34h48V32" stroke="#1F2937" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<rect x="32" y="44" width="16" height="22" fill="#1F2937"/>
						</svg>
						<span class="pension-rating">
							<svg width="14" height="14" viewBox="0 0 14 14" fill="#F59E0B">
								<path d="m7 1 2 4 4 .5-3 3 1 4-4-2-4 2 1-4-3-3 4-.5 2-4Z"/>
							</svg>
							<?php echo esc_html( $pension['rating'] ); ?>
						</span>
						<span class="pension-fav">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none">
								<path d="M12 21S3 14.5 3 8.5A4.5 4.5 0 0 1 12 6a4.5 4.5 0 0 1 9 2.5c0 6-9 12.5-9 12.5Z" stroke="white" stroke-width="2"/>
							</svg>
						</span>
					</div>

					<div class="pension-body">
						<h3 class="pension-title"><?php echo esc_html( $pension['title'] ); ?></h3>
						<p class="pension-address">
							<svg width="14" height="14" viewBox="0 0 16 16" fill="none">
								<path d="M8 14s5-3.5 5-8a5 5 0 0 0-10 0c0 4.5 5 8 5 8Z" stroke="#6B7280" stroke-width="1.5" fill="none"/>
								<circle cx="8" cy="6" r="2" fill="#6B7280"/>
							</svg>
							<?php echo esc_html( $pension['address'] ); ?>
						</p>

						<div class="pension-price">
							<?php
							/* translators: %s: цена */
							printf( esc_html__( 'от %s ₽ / мес.', 'zabota-ryadom' ), '<strong>' . esc_html( $pension['price'] ) . '</strong>' );
							?>
						</div>

						<div class="pension-tags">
							<?php foreach ( $pension['tags'] as $tag ) : ?>
								<span class="pension-tag">
									<span class="pension-tag-icon"><?php echo esc_html( $tag['icon'] ); ?></span>
									<?php echo esc_html( $tag['text'] ); ?>
								</span>
							<?php endforeach; ?>
						</div>

						<div class="pension-actions">
							<a href="#" class="btn btn-text"><?php esc_html_e( 'Подробнее', 'zabota-ryadom' ); ?> →</a>
							<div class="pension-actions-right">
								<button type="button" class="pension-icon-btn" aria-label="<?php esc_attr_e( 'В избранное', 'zabota-ryadom' ); ?>">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none">
										<path d="M12 21S3 14.5 3 8.5A4.5 4.5 0 0 1 12 6a4.5 4.5 0 0 1 9 2.5c0 6-9 12.5-9 12.5Z" stroke="#10B981" stroke-width="2"/>
									</svg>
								</button>
								<button type="button" class="pension-icon-btn pension-icon-btn-primary" aria-label="<?php esc_attr_e( 'Связаться', 'zabota-ryadom' ); ?>">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none">
										<path d="M21 11.5a8.4 8.4 0 0 1-9 8.4L3 21l1.1-9A8.4 8.4 0 0 1 21 11.5Z" stroke="white" stroke-width="2" stroke-linejoin="round"/>
									</svg>
								</button>
							</div>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

	</div>
</section>
