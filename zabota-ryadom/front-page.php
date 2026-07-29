<?php
/**
 * Шаблон главной страницы.
 *
 * @package ZabotaRyadom
 */

get_header();
?>

<main id="main" class="site-content">

	<!-- HERO -->
	<?php get_template_part( 'template-parts/hero' ); ?>

	<!-- СЕРВИСЫ: 5 карточек -->
	<?php get_template_part( 'template-parts/services' ); ?>

	<!-- ПОДБОР ПО СИТУАЦИИ: 8 иконок -->
	<?php get_template_part( 'template-parts/situations' ); ?>

	<!-- КАЛЬКУЛЯТОР + КАРТА -->
	<?php get_template_part( 'template-parts/calculator-map' ); ?>

	<!-- ЛУЧШИЕ ПАНСИОНАТЫ -->
	<?php get_template_part( 'template-parts/catalog' ); ?>

</main>

<?php
get_footer();
