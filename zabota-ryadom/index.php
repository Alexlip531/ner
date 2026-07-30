<?php
/**
 * Главный шаблон (блог/новости).
 *
 * @package ZabotaRyadom
 */

get_header();
?>

<main id="main" class="site-content">
	<div class="container">
		<div class="content-grid">
			<div id="primary" class="content-area">
				<?php if ( have_posts() ) : ?>

					<?php if ( is_home() && ! is_front_page() ) : ?>
						<header class="page-header">
							<h1 class="page-title"><?php single_post_title(); ?></h1>
						</header>
					<?php endif; ?>

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
					<p><?php esc_html_e( 'Записей не найдено.', 'zabota-ryadom' ); ?></p>
				<?php endif; ?>
			</div>

			<?php get_sidebar(); ?>
		</div>
	</div>
</main>

<?php
get_footer();
