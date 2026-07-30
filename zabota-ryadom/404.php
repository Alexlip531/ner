<?php
/**
 * 404 шаблон.
 *
 * @package ZabotaRyadom
 */

get_header();
?>

<main id="main" class="site-content">
	<div class="container error-404">
		<div class="error-content">
			<h1 class="error-code">404</h1>
			<h2 class="error-title"><?php esc_html_e( 'Страница не найдена', 'zabota-ryadom' ); ?></h2>
			<p class="error-text"><?php esc_html_e( 'К сожалению, такой страницы не существует. Возможно, она была перемещена или удалена.', 'zabota-ryadom' ); ?></p>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'На главную', 'zabota-ryadom' ); ?></a>
		</div>
	</div>
</main>

<?php
get_footer();
