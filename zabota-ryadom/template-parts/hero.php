<?php
/**
 * Hero секция.
 *
 * @package ZabotaRyadom
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="hero">
	<div class="hero-bg">
		<div class="hero-overlay"></div>
		<!-- Можно вставить <?php /* the_post_thumbnail( 'hero-image' ); */ ?> если используется фото -->
	</div>

	<div class="container hero-container">

		<!-- Левая часть -->
		<div class="hero-left">

			<div class="hero-badge">
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
					<path d="M14 8a6 6 0 1 1-1.8-4.3M14 2v3h-3" stroke="#10B981" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
				<span><?php esc_html_e( 'Бесплатный подбор за 3 минуты', 'zabota-ryadom' ); ?></span>
			</div>

			<h1 class="hero-title"><?php esc_html_e( 'Подберём лучший уход для вашего близкого', 'zabota-ryadom' ); ?></h1>

			<p class="hero-subtitle"><?php esc_html_e( 'Пансионаты, сиделки и уход на дому с проверенными отзывами и реальными фото', 'zabota-ryadom' ); ?></p>

			<!-- Табы -->
			<div class="hero-tabs" role="tablist">
				<button type="button" class="hero-tab active" role="tab" aria-selected="true"><?php esc_html_e( 'Пансионат', 'zabota-ryadom' ); ?></button>
				<button type="button" class="hero-tab" role="tab" aria-selected="false"><?php esc_html_e( 'Сиделка', 'zabota-ryadom' ); ?></button>
				<button type="button" class="hero-tab" role="tab" aria-selected="false"><?php esc_html_e( 'Уход на дому', 'zabota-ryadom' ); ?></button>
				<button type="button" class="hero-tab" role="tab" aria-selected="false"><?php esc_html_e( 'Реабилитация', 'zabota-ryadom' ); ?></button>
			</div>

			<!-- Фильтр поиска -->
			<div class="hero-search">
				<div class="hero-search-field">
					<label class="hero-search-label"><?php esc_html_e( 'Город', 'zabota-ryadom' ); ?></label>
					<div class="hero-search-select">
						<select>
							<option>Москва</option>
							<option>Санкт-Петербург</option>
							<option>Екатеринбург</option>
							<option>Новосибирск</option>
							<option>Казань</option>
						</select>
						<svg class="select-arrow" width="12" height="12" viewBox="0 0 12 12" fill="none">
							<path d="M3 4.5 6 7.5 9 4.5" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</div>
				</div>

				<div class="hero-search-field">
					<label class="hero-search-label"><?php esc_html_e( 'Уход требуется для', 'zabota-ryadom' ); ?></label>
					<div class="hero-search-select">
						<select>
							<option><?php esc_html_e( 'Самостоятельный', 'zabota-ryadom' ); ?></option>
							<option><?php esc_html_e( 'Передвижение с опорой', 'zabota-ryadom' ); ?></option>
							<option><?php esc_html_e( 'Лежачий', 'zabota-ryadom' ); ?></option>
							<option><?php esc_html_e( 'После инсульта', 'zabota-ryadom' ); ?></option>
						</select>
						<svg class="select-arrow" width="12" height="12" viewBox="0 0 12 12" fill="none">
							<path d="M3 4.5 6 7.5 9 4.5" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</div>
				</div>

				<div class="hero-search-field">
					<label class="hero-search-label"><?php esc_html_e( 'Бюджет в месяц', 'zabota-ryadom' ); ?></label>
					<div class="hero-search-select">
						<select>
							<option><?php esc_html_e( 'от 20 000 ₽', 'zabota-ryadom' ); ?></option>
							<option><?php esc_html_e( 'от 30 000 ₽', 'zabota-ryadom' ); ?></option>
							<option><?php esc_html_e( 'от 50 000 ₽', 'zabota-ryadom' ); ?></option>
							<option><?php esc_html_e( 'от 80 000 ₽', 'zabota-ryadom' ); ?></option>
						</select>
						<svg class="select-arrow" width="12" height="12" viewBox="0 0 12 12" fill="none">
							<path d="M3 4.5 6 7.5 9 4.5" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</div>
				</div>

				<button type="submit" class="btn btn-primary btn-search">
					<svg width="18" height="18" viewBox="0 0 18 18" fill="none">
						<circle cx="8" cy="8" r="5.5" stroke="white" stroke-width="2"/>
						<path d="M12 12l3 3" stroke="white" stroke-width="2" stroke-linecap="round"/>
					</svg>
					<?php esc_html_e( 'Подобрать', 'zabota-ryadom' ); ?>
				</button>
			</div>

			<!-- Социальное доказательство -->
			<div class="hero-proof">
				<div class="hero-avatars">
					<span class="avatar" style="background:#FCD34D;"></span>
					<span class="avatar" style="background:#F87171;"></span>
					<span class="avatar" style="background:#60A5FA;"></span>
					<span class="avatar" style="background:#34D399;"></span>
				</div>
				<p class="hero-proof-text"><?php esc_html_e( 'Мы уже помогли 12 500 семьям найти подходящий уход', 'zabota-ryadom' ); ?></p>
			</div>

		</div>

		<!-- Правая часть: плавающие карточки -->
		<div class="hero-right">
			<div class="hero-image-block">
				<!-- Иллюстрация: можно заменить на фото -->
				<div class="hero-image-placeholder">
					<svg width="100%" height="100%" viewBox="0 0 400 500" fill="none" xmlns="http://www.w3.org/2000/svg">
						<rect width="400" height="500" fill="#FED7AA"/>
						<circle cx="200" cy="200" r="120" fill="#FDBA74"/>
						<circle cx="160" cy="180" r="8" fill="#1F2937"/>
						<circle cx="220" cy="180" r="8" fill="#1F2937"/>
						<path d="M160 230 Q200 260 240 230" stroke="#1F2937" stroke-width="4" stroke-linecap="round" fill="none"/>
						<rect x="80" y="320" width="240" height="160" rx="40" fill="#FB923C"/>
						<circle cx="200" cy="400" r="60" fill="#FDBA74"/>
					</svg>
				</div>
			</div>

			<div class="hero-floating-cards">
				<div class="hero-float-card">
					<div class="hero-float-icon" style="background:#D1FAE5;">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none">
							<path d="M3 12 12 3l9 9M5 10v10h14V10" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</div>
					<div class="hero-float-text">
						<strong><?php esc_html_e( 'Проверенные учреждения и сиделки', 'zabota-ryadom' ); ?></strong>
						<span><?php esc_html_e( 'Каждый проверен лично', 'zabota-ryadom' ); ?></span>
					</div>
				</div>

				<div class="hero-float-card">
					<div class="hero-float-icon" style="background:#FEF3C7;">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none">
							<path d="m12 2 3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7l3-7Z" fill="#F59E0B"/>
						</svg>
					</div>
					<div class="hero-float-text">
						<strong><?php esc_html_e( 'Реальные отзывы и оценки', 'zabota-ryadom' ); ?></strong>
						<span><?php esc_html_e( 'Только проверенные отзывы', 'zabota-ryadom' ); ?></span>
					</div>
				</div>

				<div class="hero-float-card">
					<div class="hero-float-icon" style="background:#DBEAFE;">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none">
							<path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9l-6-6Z" stroke="#3B82F6" stroke-width="2" stroke-linejoin="round"/>
							<path d="M14 3v6h6M8 13h8M8 17h5" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"/>
						</svg>
					</div>
					<div class="hero-float-text">
						<strong><?php esc_html_e( 'Контроль качества и документы', 'zabota-ryadom' ); ?></strong>
						<span><?php esc_html_e( 'Проверяем лицензии и стандарты', 'zabota-ryadom' ); ?></span>
					</div>
				</div>

				<div class="hero-float-card">
					<div class="hero-float-icon" style="background:#FCE7F3;">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none">
							<path d="M21 11.5a8.4 8.4 0 0 1-9 8.4L3 21l1.1-9A8.4 8.4 0 0 1 21 11.5Z" stroke="#EC4899" stroke-width="2" stroke-linejoin="round"/>
							<circle cx="8.5" cy="12" r="1" fill="#EC4899"/>
							<circle cx="12.5" cy="12" r="1" fill="#EC4899"/>
							<circle cx="16.5" cy="12" r="1" fill="#EC4899"/>
						</svg>
					</div>
					<div class="hero-float-text">
						<strong><?php esc_html_e( 'Бесплатный подбор и консультации', 'zabota-ryadom' ); ?></strong>
						<span><?php esc_html_e( 'Поможем 24/7 без выходных', 'zabota-ryadom' ); ?></span>
					</div>
				</div>
			</div>
		</div>

	</div>
</section>
