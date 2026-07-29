<?php
/**
 * Sidebar.
 *
 * @package ZabotaRyadom
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<aside class="sidebar" id="secondary">
	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	<?php else : ?>
		<section class="widget widget-default">
			<h3 class="widget-title"><?php esc_html_e( 'Поиск', 'zabota-ryadom' ); ?></h3>
			<?php get_search_form(); ?>
		</section>
		<section class="widget widget-default">
			<h3 class="widget-title"><?php esc_html_e( 'Свежие записи', 'zabota-ryadom' ); ?></h3>
			<ul>
				<?php
				wp_get_recent_posts(
					array(
						'numberposts' => 5,
						'post_status' => 'publish',
					)
				);
				?>
			</ul>
		</section>
	<?php endif; ?>
</aside>
