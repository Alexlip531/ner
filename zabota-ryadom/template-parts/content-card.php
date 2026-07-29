<?php
/**
 * Шаблон карточки записи.
 *
 * @package ZabotaRyadom
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" class="post-card-thumb">
			<?php the_post_thumbnail( 'pension-card' ); ?>
		</a>
	<?php endif; ?>

	<div class="post-card-body">
		<div class="post-card-meta">
			<span class="post-card-date"><?php echo esc_html( get_the_date() ); ?></span>
		</div>

		<h3 class="post-card-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<div class="post-card-excerpt">
			<?php the_excerpt(); ?>
		</div>

		<a href="<?php the_permalink(); ?>" class="btn-text"><?php esc_html_e( 'Читать далее', 'zabota-ryadom' ); ?> →</a>
	</div>
</article>
