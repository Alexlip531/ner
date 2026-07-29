<?php
/**
 * Калькулятор + карта.
 *
 * @package ZabotaRyadom
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="calc-map-section">
	<div class="container">

		<div class="calc-map-grid">

			<!-- ЛЕВО: калькулятор -->
			<div class="calc-block">
				<div class="block-head">
					<h2 class="block-title"><?php esc_html_e( 'Рассчитайте примерную стоимость', 'zabota-ryadom' ); ?></h2>
					<p class="block-subtitle"><?php esc_html_e( 'Подберём варианты под ваш бюджет и требования', 'zabota-ryadom' ); ?></p>
				</div>

				<form class="calc-form" onsubmit="return false;">
					<div class="calc-row">
						<label class="calc-label"><?php esc_html_e( 'Город', 'zabota-ryadom' ); ?></label>
						<div class="calc-select-wrap">
							<select class="calc-select">
								<option>Москва</option>
								<option>Санкт-Петербург</option>
								<option>Екатеринбург</option>
								<option>Казань</option>
							</select>
							<svg class="select-arrow" width="12" height="12" viewBox="0 0 12 12" fill="none">
								<path d="M3 4.5 6 7.5 9 4.5" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</div>
					</div>

					<div class="calc-row">
						<label class="calc-label"><?php esc_html_e( 'Тип ухода', 'zabota-ryadom' ); ?></label>
						<div class="calc-select-wrap">
							<select class="calc-select">
								<option><?php esc_html_e( 'Самостоятельный', 'zabota-ryadom' ); ?></option>
								<option><?php esc_html_e( 'Передвижение с опорой', 'zabota-ryadom' ); ?></option>
								<option><?php esc_html_e( 'Лежачий', 'zabota-ryadom' ); ?></option>
							</select>
							<svg class="select-arrow" width="12" height="12" viewBox="0 0 12 12" fill="none">
								<path d="M3 4.5 6 7.5 9 4.5" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</div>
					</div>

					<div class="calc-row">
						<label class="calc-label"><?php esc_html_e( 'Дополнительно', 'zabota-ryadom' ); ?></label>
						<div class="calc-select-wrap">
							<select class="calc-select">
								<option><?php esc_html_e( 'Без особенностей', 'zabota-ryadom' ); ?></option>
								<option><?php esc_html_e( 'Деменция', 'zabota-ryadom' ); ?></option>
								<option><?php esc_html_e( 'После инсульта', 'zabota-ryadom' ); ?></option>
								<option><?php esc_html_e( 'Реабилитация', 'zabota-ryadom' ); ?></option>
							</select>
							<svg class="select-arrow" width="12" height="12" viewBox="0 0 12 12" fill="none">
								<path d="M3 4.5 6 7.5 9 4.5" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</div>
					</div>

					<div class="calc-row">
						<div class="calc-label-row">
							<label class="calc-label"><?php esc_html_e( 'Бюджет в месяц', 'zabota-ryadom' ); ?></label>
							<span class="calc-budget-value">от <strong>25 000 ₽</strong></span>
						</div>
						<div class="calc-slider-wrap">
							<input type="range" min="10000" max="150000" step="5000" value="25000" class="calc-slider" id="calcBudget">
							<div class="calc-slider-labels">
								<span>10 000 ₽</span>
								<span>150 000 ₽+</span>
							</div>
						</div>
					</div>

					<div class="calc-result">
						<div class="calc-result-label"><?php esc_html_e( 'Примерная стоимость', 'zabota-ryadom' ); ?></div>
						<div class="calc-result-value"><strong>от 25 000 ₽</strong> <span>/ <?php esc_html_e( 'мес.', 'zabota-ryadom' ); ?></span></div>
					</div>

					<button type="submit" class="btn btn-primary btn-block"><?php esc_html_e( 'Показать варианты', 'zabota-ryadom' ); ?></button>
				</form>
			</div>

			<!-- ПРАВО: карта + фильтры -->
			<div class="map-block">
				<div class="block-head">
					<h2 class="block-title"><?php esc_html_e( 'Пансионаты рядом с вами', 'zabota-ryadom' ); ?></h2>
					<p class="block-subtitle"><?php esc_html_e( 'Найдите ближайшие учреждения на карте', 'zabota-ryadom' ); ?></p>
				</div>

				<div class="map-wrapper">
					<div class="map-container">
						<!-- Имитация карты -->
						<div class="map-placeholder">
							<svg width="100%" height="100%" viewBox="0 0 500 400" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
								<rect width="500" height="400" fill="#E5E7EB"/>
								<path d="M0 100 L150 80 L300 110 L500 90 L500 200 L350 220 L200 200 L0 230 Z" fill="#D1FAE5" opacity="0.4"/>
								<path d="M0 280 L200 300 L400 280 L500 320 L500 400 L0 400 Z" fill="#BFDBFE" opacity="0.4"/>
								<path d="M50 0 L80 200 L120 400" stroke="#9CA3AF" stroke-width="2" opacity="0.5"/>
								<path d="M200 0 L240 150 L260 400" stroke="#9CA3AF" stroke-width="2" opacity="0.5"/>
								<path d="M380 0 L350 250 L400 400" stroke="#9CA3AF" stroke-width="2" opacity="0.5"/>
								<path d="M0 150 L500 180" stroke="#9CA3AF" stroke-width="2" opacity="0.5"/>
								<!-- Метки -->
								<g transform="translate(150,140)">
									<path d="M0 0 C0 -10 8 -16 12 -16 C16 -16 24 -10 24 0 C24 10 12 24 12 24 C12 24 0 10 0 0 Z" fill="#10B981"/>
									<circle cx="12" cy="-4" r="5" fill="white"/>
								</g>
								<g transform="translate(280,210)">
									<path d="M0 0 C0 -10 8 -16 12 -16 C16 -16 24 -10 24 0 C24 10 12 24 12 24 C12 24 0 10 0 0 Z" fill="#10B981"/>
									<circle cx="12" cy="-4" r="5" fill="white"/>
								</g>
								<g transform="translate(380,130)">
									<path d="M0 0 C0 -10 8 -16 12 -16 C16 -16 24 -10 24 0 C24 10 12 24 12 24 C12 24 0 10 0 0 Z" fill="#F59E0B"/>
									<circle cx="12" cy="-4" r="5" fill="white"/>
								</g>
								<g transform="translate(80,300)">
									<path d="M0 0 C0 -10 8 -16 12 -16 C16 -16 24 -10 24 0 C24 10 12 24 12 24 C12 24 0 10 0 0 Z" fill="#10B981"/>
									<circle cx="12" cy="-4" r="5" fill="white"/>
								</g>
							</svg>
						</div>
						<button type="button" class="btn btn-primary map-center-btn">
							<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
								<circle cx="8" cy="8" r="3" stroke="white" stroke-width="2"/>
								<path d="M8 1v2M8 13v2M1 8h2M13 8h2" stroke="white" stroke-width="2" stroke-linecap="round"/>
							</svg>
							<?php esc_html_e( 'Показать на карте', 'zabota-ryadom' ); ?>
						</button>
					</div>

					<aside class="map-filters">
						<h4 class="map-filters-title"><?php esc_html_e( 'Показать только', 'zabota-ryadom' ); ?></h4>
						<ul class="map-filters-list">
							<li>
								<label class="checkbox-row">
									<input type="checkbox">
									<span class="checkbox-box"></span>
									<span class="checkbox-text"><?php esc_html_e( 'До 30 000 ₽', 'zabota-ryadom' ); ?></span>
								</label>
							</li>
							<li>
								<label class="checkbox-row">
									<input type="checkbox">
									<span class="checkbox-box"></span>
									<span class="checkbox-text"><?php esc_html_e( 'До 50 000 ₽', 'zabota-ryadom' ); ?></span>
								</label>
							</li>
							<li>
								<label class="checkbox-row">
									<input type="checkbox">
									<span class="checkbox-box"></span>
									<span class="checkbox-text"><?php esc_html_e( 'Есть бассейн', 'zabota-ryadom' ); ?></span>
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
									<span class="checkbox-text"><?php esc_html_e( 'Для лежачих', 'zabota-ryadom' ); ?></span>
								</label>
							</li>
							<li>
								<label class="checkbox-row">
									<input type="checkbox" checked>
									<span class="checkbox-box"></span>
									<span class="checkbox-text"><?php esc_html_e( 'Есть свободные места', 'zabota-ryadom' ); ?></span>
								</label>
							</li>
						</ul>
						<a href="#" class="link-arrow"><?php esc_html_e( 'Все фильтры', 'zabota-ryadom' ); ?>
							<svg width="14" height="14" viewBox="0 0 14 14" fill="none">
								<path d="M5 3l4 4-4 4" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</a>
					</aside>
				</div>
			</div>

		</div>

	</div>
</section>
