<?php
/**
 * Хлебные крошки + H1 каталога.
 *
 * @package ZabotaRyadom
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="catalog-head-section">
	<div class="container">

		<!-- Хлебные крошки -->
		<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Хлебные крошки', 'zabota-ryadom' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="breadcrumb-home">
				<svg width="14" height="14" viewBox="0 0 16 16" fill="none">
					<path d="M2 7 8 2l6 5v7H10V9H6v5H2V7Z" stroke="#9CA3AF" stroke-width="1.5" stroke-linejoin="round"/>
				</svg>
				<?php esc_html_e( 'Главная', 'zabota-ryadom' ); ?>
			</a>
			<span class="breadcrumb-sep">›</span>
			<span class="breadcrumb-current"><?php esc_html_e( 'Пансионаты', 'zabota-ryadom' ); ?></span>
		</nav>

		<!-- Заголовок H1 + счётчик -->
		<div class="catalog-title-row">
			<h1 class="catalog-h1"><?php esc_html_e( 'Пансионаты для пожилых в Москве', 'zabota-ryadom' ); ?></h1>
			<span class="catalog-count-badge">
				<?php
				/* translators: %d: количество */
				printf( esc_html__( 'Найдено %d пансионатов', 'zabota-ryadom' ), 128 );
				?>
			</span>
		</div>

	</div>
</div>
