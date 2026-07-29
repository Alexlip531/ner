<?php
/**
 * Подбор по ситуации: 8 иконок.
 *
 * @package ZabotaRyadom
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$situations = array(
	array(
		'label' => __( 'После инсульта', 'zabota-ryadom' ),
		'icon'  => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M16 4c-3 0-6 3-6 6 0 1 1 3 1 3-2 1-3 3-3 5 0 3 2 5 5 5h6c3 0 5-2 5-5 0-2-1-4-3-5 0 0 1-2 1-3 0-3-3-6-6-6Z" stroke="#10B981" stroke-width="2" fill="none"/><circle cx="13" cy="14" r="1.5" fill="#10B981"/><circle cx="19" cy="14" r="1.5" fill="#10B981"/><path d="M12 18h8" stroke="#10B981" stroke-width="1.5" stroke-linecap="round"/></svg>',
	),
	array(
		'label' => __( 'После перелома', 'zabota-ryadom' ),
		'icon'  => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M10 6 6 10l4 4M22 6l4 4-4 4M10 26l-4-4 4-4M22 26l4-4-4-4" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 10l8 12M20 10l-8 12" stroke="#EF4444" stroke-width="2" stroke-linecap="round"/></svg>',
	),
	array(
		'label' => __( 'Деменция (Альцгеймер)', 'zabota-ryadom' ),
		'icon'  => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M16 4c-3 0-6 3-6 6 0 1 1 2 1 2-2 1-3 3-3 5 0 3 2 5 5 5h6c3 0 5-2 5-5 0-2-1-4-3-5 0 0 1-1 1-2 0-3-3-6-6-6Z" stroke="#10B981" stroke-width="2" fill="none"/><circle cx="13" cy="13" r="1.5" fill="#10B981"/><circle cx="19" cy="13" r="1.5" fill="#10B981"/><path d="M12 18h8" stroke="#10B981" stroke-width="1.5" stroke-linecap="round"/><path d="M10 22l3 4M22 22l-3 4" stroke="#10B981" stroke-width="1.5" stroke-linecap="round"/></svg>',
	),
	array(
		'label' => __( 'Паркинсон', 'zabota-ryadom' ),
		'icon'  => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><circle cx="16" cy="10" r="5" stroke="#10B981" stroke-width="2" fill="none"/><path d="M11 17c-2 1-4 4-4 7 0 2 2 4 4 4h10c2 0 4-2 4-4 0-3-2-6-4-7" stroke="#10B981" stroke-width="2" fill="none"/><path d="M14 13l-1 2M18 13l1 2" stroke="#10B981" stroke-width="1.5" stroke-linecap="round"/></svg>',
	),
	array(
		'label' => __( 'Лежачий больной', 'zabota-ryadom' ),
		'icon'  => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><circle cx="8" cy="14" r="4" stroke="#10B981" stroke-width="2" fill="none"/><path d="M2 22h28v4H2z" stroke="#10B981" stroke-width="2" fill="none"/><path d="M12 18l8 4" stroke="#10B981" stroke-width="2" stroke-linecap="round"/><path d="M26 12v6" stroke="#10B981" stroke-width="2" stroke-linecap="round"/></svg>',
	),
	array(
		'label' => __( 'Онкология', 'zabota-ryadom' ),
		'icon'  => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M16 6c-5 0-9 4-9 9 0 6 9 11 9 11s9-5 9-11c0-5-4-9-9-9Z" stroke="#10B981" stroke-width="2" fill="none"/><path d="M14 14l2 2 4-4" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
	),
	array(
		'label' => __( 'Паллиативный уход', 'zabota-ryadom' ),
		'icon'  => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><circle cx="16" cy="10" r="5" stroke="#10B981" stroke-width="2" fill="none"/><path d="M11 16c-3 1-5 4-5 8 0 3 2 4 4 4h12c2 0 4-1 4-4 0-4-2-7-5-8" stroke="#10B981" stroke-width="2" fill="none"/><path d="M13 12l3 2 3-2" stroke="#10B981" stroke-width="1.5" stroke-linecap="round"/></svg>',
	),
	array(
		'label' => __( 'Временное проживание', 'zabota-ryadom' ),
		'icon'  => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M4 16 16 4l12 12" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 13v15h18V13" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><rect x="13" y="20" width="6" height="8" stroke="#10B981" stroke-width="2" fill="none"/><path d="M10 13v3M22 13v3" stroke="#10B981" stroke-width="2" stroke-linecap="round"/></svg>',
	),
);
?>

<section class="situations-section">
	<div class="container">

		<div class="section-head">
			<h2 class="section-title"><?php esc_html_e( 'Подбор по ситуации', 'zabota-ryadom' ); ?></h2>
			<p class="section-subtitle"><?php esc_html_e( 'Выберите подходящую категорию ухода — покажем подходящие учреждения и специалистов', 'zabota-ryadom' ); ?></p>
		</div>

		<div class="situations-grid">
			<?php foreach ( $situations as $situation ) : ?>
				<a href="#" class="situation-card">
					<div class="situation-icon">
						<?php echo $situation['icon']; // phpcs:ignore ?>
					</div>
					<span class="situation-label"><?php echo esc_html( $situation['label'] ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>

	</div>
</section>
