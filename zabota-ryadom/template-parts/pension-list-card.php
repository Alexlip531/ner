<?php
/**
 * Карточка пансионата в виде строки (для каталога).
 *
 * Данные передаются через set_query_var('pension_data', ...).
 *
 * @package ZabotaRyadom
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$p = get_query_var( 'pension_data' );
if ( empty( $p ) ) {
	return;
}

// Иконки для преимуществ.
$benefit_icons = array(
	'check' => '<svg width="14" height="14" viewBox="0 0 14 14" fill="none"><circle cx="7" cy="7" r="6" fill="#10B981"/><path d="M4 7l2 2 4-4" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
	'clock' => '<svg width="14" height="14" viewBox="0 0 14 14" fill="none"><circle cx="7" cy="7" r="6" fill="#10B981"/><path d="M7 4v3l2 2" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
	'car'   => '<svg width="14" height="14" viewBox="0 0 14 14" fill="none"><circle cx="7" cy="7" r="6" fill="#10B981"/><path d="M3 8l1-2.5h6L11 8v2H3V8Z" stroke="white" stroke-width="1.2" stroke-linejoin="round" fill="none"/><circle cx="5" cy="9.5" r="0.8" fill="white"/><circle cx="9" cy="9.5" r="0.8" fill="white"/></svg>',
);
?>

<article class="pension-list-card">

	<!-- ЛЕВАЯ КОЛОНКА: Фото -->
	<div class="plc-gallery">

		<div class="plc-main-photo" style="background: <?php echo esc_attr( $p['bg'] ); ?>;">
			<span class="plc-video-badge">
				<svg width="12" height="12" viewBox="0 0 12 12" fill="white">
					<path d="M3 2l7 4-7 4V2Z"/>
				</svg>
				<?php esc_html_e( 'Видео', 'zabota-ryadom' ); ?>
			</span>

			<button type="button" class="plc-fav-btn" aria-label="<?php esc_attr_e( 'В избранное', 'zabota-ryadom' ); ?>">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none">
					<path d="M12 21S3 14.5 3 8.5A4.5 4.5 0 0 1 12 6a4.5 4.5 0 0 1 9 2.5c0 6-9 12.5-9 12.5Z" stroke="white" stroke-width="2"/>
				</svg>
			</button>

			<svg class="plc-photo-icon" width="100" height="100" viewBox="0 0 100 100" fill="none">
				<path d="M15 50 50 18l35 32" stroke="#1F2937" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" opacity="0.4"/>
				<path d="M22 42v40h56V42" stroke="#1F2937" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" opacity="0.4"/>
				<rect x="42" y="58" width="16" height="24" fill="#1F2937" opacity="0.4"/>
			</svg>
		</div>

		<div class="plc-thumbs">
			<div class="plc-thumb" style="background: <?php echo esc_attr( $p['bg'] ); ?>; filter: brightness(0.92);"></div>
			<div class="plc-thumb" style="background: <?php echo esc_attr( $p['bg'] ); ?>; filter: brightness(0.85);"></div>
			<div class="plc-thumb" style="background: <?php echo esc_attr( $p['bg'] ); ?>; filter: brightness(0.78);"></div>
			<div class="plc-thumb plc-thumb-more" style="background: <?php echo esc_attr( $p['bg'] ); ?>; filter: brightness(0.6);">
				<span>+<?php echo esc_html( $p['extra_photos'] ); ?></span>
			</div>
		</div>

	</div>

	<!-- ЦЕНТРАЛЬНАЯ КОЛОНКА: Информация -->
	<div class="plc-info">

		<h3 class="plc-title">
			<a href="#"><?php echo esc_html( $p['title'] ); ?></a>
		</h3>

		<div class="plc-meta">
			<span class="plc-address">
				<svg width="14" height="14" viewBox="0 0 16 16" fill="none">
					<path d="M8 14s5-3.5 5-8a5 5 0 0 0-10 0c0 4.5 5 8 5 8Z" stroke="#6B7280" stroke-width="1.5" fill="none"/>
					<circle cx="8" cy="6" r="2" fill="#6B7280"/>
				</svg>
				<?php echo esc_html( $p['address'] ); ?>
			</span>
			<span class="plc-distance">
				<svg width="14" height="14" viewBox="0 0 16 16" fill="none">
					<path d="M2 8h7M9 5l3 3-3 3" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					<circle cx="13" cy="8" r="1.5" stroke="#6B7280" stroke-width="1.5" fill="none"/>
				</svg>
				<?php echo esc_html( $p['distance'] ); ?>
			</span>
		</div>

		<div class="plc-rating-row">
			<span class="plc-rating-stars">
				<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
					<svg width="14" height="14" viewBox="0 0 14 14" fill="<?php echo $i <= round( $p['rating'] ) ? '#F59E0B' : '#E5E7EB'; ?>">
						<path d="m7 1 2 4 4 .5-3 3 1 4-4-2-4 2 1-4-3-3 4-.5 2-4Z"/>
					</svg>
				<?php endfor; ?>
			</span>
			<strong class="plc-rating-value"><?php echo esc_html( $p['rating'] ); ?></strong>
			<span class="plc-reviews">(<?php echo esc_html( $p['reviews'] ); ?> <?php echo esc_html( _n( 'отзыв', 'отзывов', $p['reviews'], 'zabota-ryadom' ) ); ?>)</span>
		</div>

		<div class="plc-services">
			<?php foreach ( $p['services'] as $service ) : ?>
				<span class="plc-service-tag">
					<span class="plc-service-icon"><?php echo esc_html( $service['icon'] ); ?></span>
					<?php echo esc_html( $service['text'] ); ?>
				</span>
			<?php endforeach; ?>
		</div>

		<p class="plc-description"><?php echo esc_html( $p['desc'] ); ?></p>

		<div class="plc-benefits">
			<?php foreach ( $p['benefits'] as $benefit ) : ?>
				<span class="plc-benefit">
					<?php echo isset( $benefit_icons[ $benefit['icon'] ] ) ? $benefit_icons[ $benefit['icon'] ] : ''; // phpcs:ignore ?>
					<?php echo esc_html( $benefit['text'] ); ?>
				</span>
			<?php endforeach; ?>
		</div>

	</div>

	<!-- ПРАВАЯ КОЛОНКА: Цена + действия -->
	<div class="plc-actions-col">

		<div class="plc-price-block">
			<div class="plc-price">
				<span class="plc-price-from"><?php esc_html_e( 'от', 'zabota-ryadom' ); ?></span>
				<strong class="plc-price-value"><?php echo esc_html( $p['price'] ); ?> ₽</strong>
				<span class="plc-price-unit">/ <?php esc_html_e( 'мес.', 'zabota-ryadom' ); ?></span>
			</div>
			<p class="plc-price-note"><?php esc_html_e( 'Стоимость зависит от состояния и типа размещения', 'zabota-ryadom' ); ?></p>
		</div>

		<a href="#" class="btn btn-primary btn-block plc-details-btn"><?php esc_html_e( 'Подробнее', 'zabota-ryadom' ); ?></a>

		<div class="plc-secondary-actions">
			<button type="button" class="plc-secondary-btn">
				<svg width="14" height="14" viewBox="0 0 16 16" fill="none">
					<rect x="2" y="3" width="5" height="10" rx="1" stroke="#6B7280" stroke-width="1.5" fill="none"/>
					<rect x="9" y="3" width="5" height="10" rx="1" stroke="#6B7280" stroke-width="1.5" fill="none"/>
				</svg>
				<?php esc_html_e( 'Сравнить', 'zabota-ryadom' ); ?>
			</button>
			<button type="button" class="plc-secondary-btn plc-fav-toggle">
				<svg width="14" height="14" viewBox="0 0 16 16" fill="none">
					<path d="M8 14S2 10 2 5.5A3 3 0 0 1 8 4a3 3 0 0 1 6 1.5C14 10 8 14 8 14Z" stroke="#6B7280" stroke-width="1.5" fill="none"/>
				</svg>
				<?php esc_html_e( 'В избранное', 'zabota-ryadom' ); ?>
			</button>
		</div>

	</div>

</article>
