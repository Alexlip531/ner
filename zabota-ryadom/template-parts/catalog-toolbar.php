<?php
/**
 * Панель управления каталогом: chips активных фильтров, кнопки "На карте",
 * переключатель grid/list, сортировка.
 *
 * @package ZabotaRyadom
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="catalog-toolbar">

	<!-- Активные фильтры: chips -->
	<div class="catalog-chips">
		<span class="chip">
			<?php esc_html_e( 'Москва', 'zabota-ryadom' ); ?>
			<button type="button" class="chip-remove" aria-label="<?php esc_attr_e( 'Удалить', 'zabota-ryadom' ); ?>">
				<svg width="10" height="10" viewBox="0 0 10 10" fill="none">
					<path d="M2 2l6 6M8 2l-6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
				</svg>
			</button>
		</span>

		<span class="chip">
			<?php esc_html_e( 'Цена: 20 000 – 120 000 ₽', 'zabota-ryadom' ); ?>
			<button type="button" class="chip-remove" aria-label="<?php esc_attr_e( 'Удалить', 'zabota-ryadom' ); ?>">
				<svg width="10" height="10" viewBox="0 0 10 10" fill="none">
					<path d="M2 2l6 6M8 2l-6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
				</svg>
			</button>
		</span>

		<span class="chip">
			<?php esc_html_e( 'Медицинский уход', 'zabota-ryadom' ); ?>
			<button type="button" class="chip-remove" aria-label="<?php esc_attr_e( 'Удалить', 'zabota-ryadom' ); ?>">
				<svg width="10" height="10" viewBox="0 0 10 10" fill="none">
					<path d="M2 2l6 6M8 2l-6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
				</svg>
			</button>
		</span>

		<span class="chip">
			<?php esc_html_e( 'Деменция', 'zabota-ryadom' ); ?>
			<button type="button" class="chip-remove" aria-label="<?php esc_attr_e( 'Удалить', 'zabota-ryadom' ); ?>">
				<svg width="10" height="10" viewBox="0 0 10 10" fill="none">
					<path d="M2 2l6 6M8 2l-6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
				</svg>
			</button>
		</span>

		<a href="#" class="chips-reset"><?php esc_html_e( 'Сбросить всё', 'zabota-ryadom' ); ?></a>
	</div>

	<!-- Правая часть: кнопки управления -->
	<div class="catalog-controls">

		<button type="button" class="map-toggle-btn">
			<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
				<path d="M8 14s5-3.5 5-8a5 5 0 0 0-10 0c0 4.5 5 8 5 8Z" stroke="#10B981" stroke-width="1.5" fill="none"/>
				<circle cx="8" cy="6" r="2" fill="#10B981"/>
			</svg>
			<?php esc_html_e( 'На карте', 'zabota-ryadom' ); ?>
		</button>

		<div class="view-toggle" role="group" aria-label="<?php esc_attr_e( 'Вид отображения', 'zabota-ryadom' ); ?>">
			<button type="button" class="view-btn active" data-view="list" aria-label="<?php esc_attr_e( 'Список', 'zabota-ryadom' ); ?>">
				<svg width="18" height="18" viewBox="0 0 18 18" fill="none">
					<rect x="2" y="3" width="14" height="3" rx="0.5" fill="currentColor"/>
					<rect x="2" y="8" width="14" height="3" rx="0.5" fill="currentColor"/>
					<rect x="2" y="13" width="14" height="3" rx="0.5" fill="currentColor"/>
				</svg>
			</button>
			<button type="button" class="view-btn" data-view="grid" aria-label="<?php esc_attr_e( 'Сетка', 'zabota-ryadom' ); ?>">
				<svg width="18" height="18" viewBox="0 0 18 18" fill="none">
					<rect x="2" y="2" width="6" height="6" rx="0.5" fill="currentColor"/>
					<rect x="10" y="2" width="6" height="6" rx="0.5" fill="currentColor"/>
					<rect x="2" y="10" width="6" height="6" rx="0.5" fill="currentColor"/>
					<rect x="10" y="10" width="6" height="6" rx="0.5" fill="currentColor"/>
				</svg>
			</button>
		</div>

		<div class="sort-wrap">
			<label class="sort-label" for="sortBy"><?php esc_html_e( 'Сортировка:', 'zabota-ryadom' ); ?></label>
			<div class="sort-select-wrap">
				<select id="sortBy" class="sort-select">
					<option value="rating"><?php esc_html_e( 'по рейтингу', 'zabota-ryadom' ); ?></option>
					<option value="price-asc"><?php esc_html_e( 'Сначала дешёвые', 'zabota-ryadom' ); ?></option>
					<option value="price-desc"><?php esc_html_e( 'Сначала дорогие', 'zabota-ryadom' ); ?></option>
					<option value="distance"><?php esc_html_e( 'По расстоянию', 'zabota-ryadom' ); ?></option>
					<option value="reviews"><?php esc_html_e( 'По количеству отзывов', 'zabota-ryadom' ); ?></option>
				</select>
				<svg class="select-arrow" width="12" height="12" viewBox="0 0 12 12" fill="none">
					<path d="M3 4.5 6 7.5 9 4.5" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</div>
		</div>

	</div>

</div>
