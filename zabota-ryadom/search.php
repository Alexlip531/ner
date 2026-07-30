<?php
/**
 * Шаблон поиска.
 *
 * @package ZabotaRyadom
 */

get_header();
?>

<main id="main" class="site-content">
	<div class="container">
		<header class="page-header">
			<h1 class="page-title">
				<?php
				/* translators: %s: поисковый запрос */
				printf( esc_html__( 'Результаты поиска: %s', 'zabota-ryadom' ), '<span>' . get_search_query() . '</span>' );
				?>
			</h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="posts-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', 'card' );
				endwhile;
				?>
			</div>
			<?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'Ничего не найдено. Попробуйте изменить запрос.', 'zabota-ryadom' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
