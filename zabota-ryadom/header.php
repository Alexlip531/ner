<?php
/**
 * Header шаблон.
 *
 * @package ZabotaRyadom
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Перейти к содержимому', 'zabota-ryadom' ); ?></a>

<header class="site-header" id="site-header">
	<div class="container header-inner">

		<!-- Логотип -->
		<div class="logo-wrap">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo" rel="home">
					<span class="logo-icon" aria-hidden="true">
						<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M18 31.5C18 31.5 4.5 22.5 4.5 13.5C4.5 9.358 7.858 6 12 6C14.5 6 16.5 7.5 18 9.5C19.5 7.5 21.5 6 24 6C28.142 6 31.5 9.358 31.5 13.5C31.5 22.5 18 31.5 18 31.5Z" fill="#10B981"/>
							<rect x="13" y="16" width="10" height="9" rx="1" fill="white"/>
							<rect x="16" y="20" width="4" height="5" fill="#10B981"/>
							<rect x="14.5" y="13" width="2" height="3" fill="white"/>
							<rect x="19.5" y="13" width="2" height="3" fill="white"/>
						</svg>
					</span>
					<span class="logo-text">
						<span class="logo-title"><?php bloginfo( 'name' ); ?></span>
						<span class="logo-subtitle"><?php esc_html_e( 'Уход за пожилыми', 'zabota-ryadom' ); ?></span>
					</span>
				</a>
			<?php endif; ?>
		</div>

		<!-- Навигация -->
		<nav class="main-nav" aria-label="<?php esc_attr_e( 'Главное меню', 'zabota-ryadom' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'menu_class'     => 'menu',
					'fallback_cb'    => 'zr_menu_fallback',
				)
			);
			?>
		</nav>

		<!-- Правая часть шапки -->
		<div class="header-actions">
			<button type="button" class="location-btn" aria-label="<?php esc_attr_e( 'Выбрать город', 'zabota-ryadom' ); ?>">
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
					<path d="M8 14s5-3.5 5-8a5 5 0 0 0-10 0c0 4.5 5 8 5 8Z" stroke="#10B981" stroke-width="1.5" fill="none"/>
					<circle cx="8" cy="6" r="2" fill="#10B981"/>
				</svg>
				<span class="location-text">Москва</span>
				<svg width="12" height="12" viewBox="0 0 12 12" fill="none">
					<path d="M3 4.5 6 7.5 9 4.5" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</button>

			<a href="#" class="favorites-btn" aria-label="<?php esc_attr_e( 'Избранное', 'zabota-ryadom' ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none">
					<path d="M12 21S3 14.5 3 8.5A4.5 4.5 0 0 1 12 6a4.5 4.5 0 0 1 9 2.5c0 6-9 12.5-9 12.5Z" stroke="#10B981" stroke-width="2"/>
				</svg>
				<span class="favorites-text"><?php esc_html_e( 'Избранное', 'zabota-ryadom' ); ?></span>
				<span class="favorites-count">3</span>
			</a>

			<a href="#" class="btn btn-outline btn-login"><?php esc_html_e( 'Войти', 'zabota-ryadom' ); ?></a>

			<button type="button" class="menu-toggle" aria-label="<?php esc_attr_e( 'Открыть меню', 'zabota-ryadom' ); ?>" aria-expanded="false">
				<span></span><span></span><span></span>
			</button>
		</div>

	</div>
</header>

<div id="primary" class="site-main">
