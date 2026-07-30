<?php
/**
 * Пагинация каталога.
 *
 * @package ZabotaRyadom
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="catalog-pagination">

	<div class="catalog-pagination-info">
		<?php
		/* translators: 1: from, 2: to, 3: total */
		printf( esc_html__( 'Показано %1$s–%2$s из %3$s', 'zabota-ryadom' ), '1', '12', '128' );
		?>
	</div>

	<nav class="pagination-nav" aria-label="<?php esc_attr_e( 'Пагинация', 'zabota-ryadom' ); ?>">
		<a href="#" class="page-arrow page-arrow-prev" aria-label="<?php esc_attr_e( 'Назад', 'zabota-ryadom' ); ?>">
			<svg width="14" height="14" viewBox="0 0 14 14" fill="none">
				<path d="M9 3 5 7l4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</a>

		<span class="page-num current">1</span>
		<span class="page-num">2</span>
		<span class="page-num">3</span>
		<span class="page-dots">…</span>
		<span class="page-num">11</span>

		<a href="#" class="page-arrow page-arrow-next" aria-label="<?php esc_attr_e( 'Вперёд', 'zabota-ryadom' ); ?>">
			<svg width="14" height="14" viewBox="0 0 14 14" fill="none">
				<path d="M5 3l4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</a>
	</nav>

	<div class="catalog-pagination-per">
		<label for="perPage"><?php esc_html_e( 'Показывать по:', 'zabota-ryadom' ); ?></label>
		<div class="per-page-wrap">
			<select id="perPage" class="per-page-select">
				<option value="12" selected>12</option>
				<option value="24">24</option>
				<option value="48">48</option>
				<option value="96">96</option>
			</select>
			<svg class="select-arrow" width="12" height="12" viewBox="0 0 12 12" fill="none">
				<path d="M3 4.5 6 7.5 9 4.5" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</div>
	</div>

</div>
