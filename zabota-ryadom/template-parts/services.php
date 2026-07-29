<?php
/**
 * Секция услуг: 5 карточек.
 *
 * @package ZabotaRyadom
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$services = array(
	array(
		'title'    => __( 'Пансионаты', 'zabota-ryadom' ),
		'desc'     => __( 'Комфортное проживание и постоянный уход', 'zabota-ryadom' ),
		'count'    => '1 250 учреждений',
		'icon_bg'  => '#D1FAE5',
		'icon'     => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none"><path d="M8 22 24 8l16 14" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 20v20h24V20" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><rect x="20" y="28" width="8" height="12" fill="#10B981"/><circle cx="34" cy="14" r="6" fill="#34D399"/><path d="M5 38c4-2 6-2 9 0s5 2 9 0 6-2 9 0 5 2 9 0" stroke="#10B981" stroke-width="2" stroke-linecap="round"/></svg>',
	),
	array(
		'title'    => __( 'Сиделки', 'zabota-ryadom' ),
		'desc'     => __( 'Профессиональный уход на дому', 'zabota-ryadom' ),
		'count'    => '2 750 сиделок',
		'icon_bg'  => '#FCE7F3',
		'icon'     => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="14" r="8" fill="#EC4899"/><path d="M8 40c0-8 7-14 16-14s16 6 16 14" stroke="#EC4899" stroke-width="2.5" fill="#FBCFE8"/><circle cx="36" cy="22" r="3" fill="#F472B6"/><path d="M16 22v8a4 4 0 0 0 8 0" stroke="#EC4899" stroke-width="2" stroke-linecap="round"/></svg>',
	),
	array(
		'title'    => __( 'Реабилитация', 'zabota-ryadom' ),
		'desc'     => __( 'Восстановление после болезней и травм', 'zabota-ryadom' ),
		'count'    => '350 центров',
		'icon_bg'  => '#DBEAFE',
		'icon'     => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none"><circle cx="14" cy="20" r="8" stroke="#3B82F6" stroke-width="2.5" fill="#BFDBFE"/><rect x="22" y="30" width="18" height="8" rx="2" fill="#3B82F6"/><path d="M14 28v8h8" stroke="#3B82F6" stroke-width="2.5" stroke-linecap="round"/><circle cx="34" cy="14" r="3" fill="#3B82F6"/><path d="M40 14h-3M40 14l-3 3" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"/></svg>',
	),
	array(
		'title'    => __( 'Уход на дому', 'zabota-ryadom' ),
		'desc'     => __( 'Медицинский и бытовой уход на дому', 'zabota-ryadom' ),
		'count'    => '980 предложений',
		'icon_bg'  => '#FEF3C7',
		'icon'     => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none"><path d="M8 40h32" stroke="#F59E0B" stroke-width="2.5" stroke-linecap="round"/><rect x="12" y="20" width="24" height="20" rx="2" stroke="#F59E0B" stroke-width="2.5" fill="#FEF3C7"/><rect x="20" y="26" width="8" height="14" fill="#F59E0B"/><circle cx="36" cy="14" r="6" fill="#34D399"/><path d="M16 14h8M20 10v8" stroke="#F59E0B" stroke-width="2" stroke-linecap="round"/></svg>',
	),
	array(
		'title'    => __( 'Специализированный уход', 'zabota-ryadom' ),
		'desc'     => __( 'Деменция, Альцгеймер, инсульт и др.', 'zabota-ryadom' ),
		'count'    => '620 учреждений',
		'icon_bg'  => '#EDE9FE',
		'icon'     => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none"><path d="M24 6c-5 0-9 4-9 9 0 2 1 4 2 5-3 1-5 4-5 7 0 4 3 7 7 7h10c4 0 7-3 7-7 0-3-2-6-5-7 1-1 2-3 2-5 0-5-4-9-9-9Z" stroke="#8B5CF6" stroke-width="2.5" fill="#EDE9FE"/><path d="M18 18c-3-1-6 0-7 3M30 18c3-1 6 0 7 3" stroke="#8B5CF6" stroke-width="2" stroke-linecap="round"/><circle cx="20" cy="22" r="2" fill="#8B5CF6"/><circle cx="28" cy="22" r="2" fill="#8B5CF6"/><path d="M20 27h8" stroke="#8B5CF6" stroke-width="2" stroke-linecap="round"/></svg>',
	),
);
?>

<section class="services-section">
	<div class="container">

		<div class="section-head">
			<h2 class="section-title"><?php esc_html_e( 'Выберите, что вам нужно', 'zabota-ryadom' ); ?></h2>
			<p class="section-subtitle"><?php esc_html_e( 'Мы поможем подобрать оптимальный вариант ухода для вашего близкого', 'zabota-ryadom' ); ?></p>
		</div>

		<div class="services-grid">
			<?php foreach ( $services as $service ) : ?>
				<a href="#" class="service-card">
					<div class="service-icon" style="background: <?php echo esc_attr( $service['icon_bg'] ); ?>;">
						<?php echo $service['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<h3 class="service-title"><?php echo esc_html( $service['title'] ); ?></h3>
					<p class="service-desc"><?php echo esc_html( $service['desc'] ); ?></p>
					<div class="service-count"><?php echo esc_html( $service['count'] ); ?></div>
				</a>
			<?php endforeach; ?>
		</div>

	</div>
</section>
