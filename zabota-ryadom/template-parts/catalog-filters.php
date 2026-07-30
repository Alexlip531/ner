<?php
/**
 * Сайдбар с фильтрами каталога.
 *
 * @package ZabotaRyadom
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="filters-box">

	<div class="filters-head">
		<h3 class="filters-title"><?php esc_html_e( 'Фильтры', 'zabota-ryadom' ); ?></h3>
		<a href="#" class="filters-reset"><?php esc_html_e( 'Сбросить всё', 'zabota-ryadom' ); ?></a>
	</div>

	<!-- Группа 1: Город / район -->
	<div class="filter-group">
		<label class="filter-label"><?php esc_html_e( 'Город / район', 'zabota-ryadom' ); ?></label>
		<div class="filter-select-wrap">
			<select class="filter-select">
				<option>Москва</option>
				<option>Санкт-Петербург</option>
				<option>Екатеринбург</option>
				<option>Казань</option>
				<option>Новосибирск</option>
			</select>
			<svg class="select-arrow" width="12" height="12" viewBox="0 0 12 12" fill="none">
				<path d="M3 4.5 6 7.5 9 4.5" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</div>
	</div>

	<!-- Группа 2: Цена -->
	<div class="filter-group">
		<label class="filter-label"><?php esc_html_e( 'Цена за месяц, ₽', 'zabota-ryadom' ); ?></label>

		<div class="filter-price-inputs">
			<div class="price-input-wrap">
				<span class="price-input-prefix"><?php esc_html_e( 'от', 'zabota-ryadom' ); ?></span>
				<input type="text" class="price-input" id="priceFrom" value="20 000">
			</div>
			<span class="price-dash">—</span>
			<div class="price-input-wrap">
				<span class="price-input-prefix"><?php esc_html_e( 'до', 'zabota-ryadom' ); ?></span>
				<input type="text" class="price-input" id="priceTo" value="120 000">
			</div>
		</div>

		<div class="filter-range">
			<div class="range-track">
				<div class="range-fill" id="rangeFill"></div>
			</div>
			<input type="range" min="20000" max="120000" step="5000" value="20000" class="range-input range-input-lower" id="rangeLower">
			<input type="range" min="20000" max="120000" step="5000" value="120000" class="range-input range-input-upper" id="rangeUpper">
		</div>

		<div class="range-labels">
			<span>20 000</span>
			<span>70 000</span>
			<span>120 000</span>
		</div>
	</div>

	<!-- Группа 3: Тип проживания -->
	<div class="filter-group">
		<label class="filter-label"><?php esc_html_e( 'Тип проживания', 'zabota-ryadom' ); ?></label>
		<ul class="filter-checkboxes">
			<li>
				<label class="checkbox-row">
					<input type="checkbox" checked>
					<span class="checkbox-box"></span>
					<span class="checkbox-text"><?php esc_html_e( 'Все варианты', 'zabota-ryadom' ); ?></span>
				</label>
			</li>
			<li>
				<label class="checkbox-row">
					<input type="checkbox">
					<span class="checkbox-box"></span>
					<span class="checkbox-text"><?php esc_html_e( 'Общее проживание', 'zabota-ryadom' ); ?></span>
				</label>
			</li>
			<li>
				<label class="checkbox-row">
					<input type="checkbox">
					<span class="checkbox-box"></span>
					<span class="checkbox-text"><?php esc_html_e( '2-3 местные комнаты', 'zabota-ryadom' ); ?></span>
				</label>
			</li>
			<li>
				<label class="checkbox-row">
					<input type="checkbox">
					<span class="checkbox-box"></span>
					<span class="checkbox-text"><?php esc_html_e( 'Одноместные комнаты', 'zabota-ryadom' ); ?></span>
				</label>
			</li>
		</ul>
	</div>

	<!-- Группа 4: Уход и состояние -->
	<div class="filter-group">
		<label class="filter-label"><?php esc_html_e( 'Уход и состояние', 'zabota-ryadom' ); ?></label>
		<ul class="filter-checkboxes">
			<li>
				<label class="checkbox-row">
					<input type="checkbox" checked>
					<span class="checkbox-box"></span>
					<span class="checkbox-text"><?php esc_html_e( 'Самостоятельные', 'zabota-ryadom' ); ?></span>
				</label>
			</li>
			<li>
				<label class="checkbox-row">
					<input type="checkbox">
					<span class="checkbox-box"></span>
					<span class="checkbox-text"><?php esc_html_e( 'После инсульта', 'zabota-ryadom' ); ?></span>
				</label>
			</li>
			<li>
				<label class="checkbox-row">
					<input type="checkbox">
					<span class="checkbox-box"></span>
					<span class="checkbox-text"><?php esc_html_e( 'Деменция (Альцгеймер)', 'zabota-ryadom' ); ?></span>
				</label>
			</li>
			<li>
				<label class="checkbox-row">
					<input type="checkbox">
					<span class="checkbox-box"></span>
					<span class="checkbox-text"><?php esc_html_e( 'Лежачие больные', 'zabota-ryadom' ); ?></span>
				</label>
			</li>
			<li>
				<label class="checkbox-row">
					<input type="checkbox">
					<span class="checkbox-box"></span>
					<span class="checkbox-text"><?php esc_html_e( 'Паркинсон', 'zabota-ryadom' ); ?></span>
				</label>
			</li>
			<li>
				<label class="checkbox-row">
					<input type="checkbox">
					<span class="checkbox-box"></span>
					<span class="checkbox-text"><?php esc_html_e( 'Онкология', 'zabota-ryadom' ); ?></span>
				</label>
			</li>
		</ul>
	</div>

	<!-- Группа 5: Услуги -->
	<div class="filter-group">
		<label class="filter-label"><?php esc_html_e( 'Услуги', 'zabota-ryadom' ); ?></label>
		<ul class="filter-checkboxes">
			<li>
				<label class="checkbox-row">
					<input type="checkbox" checked>
					<span class="checkbox-box"></span>
					<span class="checkbox-text"><?php esc_html_e( 'Медицинский уход', 'zabota-ryadom' ); ?></span>
				</label>
			</li>
			<li>
				<label class="checkbox-row">
					<input type="checkbox">
					<span class="checkbox-box"></span>
					<span class="checkbox-text"><?php esc_html_e( 'Реабилитация', 'zabota-ryadom' ); ?></span>
				</label>
			</li>
			<li>
				<label class="checkbox-row">
					<input type="checkbox">
					<span class="checkbox-box"></span>
					<span class="checkbox-text"><?php esc_html_e( 'Психолог', 'zabota-ryadom' ); ?></span>
				</label>
			</li>
			<li>
				<label class="checkbox-row">
					<input type="checkbox">
					<span class="checkbox-box"></span>
					<span class="checkbox-text"><?php esc_html_e( 'ЛФК', 'zabota-ryadom' ); ?></span>
				</label>
			</li>
			<li>
				<label class="checkbox-row">
					<input type="checkbox">
					<span class="checkbox-box"></span>
					<span class="checkbox-text"><?php esc_html_e( 'Паллиативный уход', 'zabota-ryadom' ); ?></span>
				</label>
			</li>
		</ul>
	</div>

	<button type="button" class="filters-show-more">
		<?php esc_html_e( 'Показать ещё', 'zabota-ryadom' ); ?>
		<svg width="14" height="14" viewBox="0 0 14 14" fill="none">
			<path d="M3 5l4 4 4-4" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>
	</button>

	<button type="button" class="btn btn-primary btn-block filters-apply">
		<?php esc_html_e( 'Применить фильтры', 'zabota-ryadom' ); ?>
	</button>

</div>
