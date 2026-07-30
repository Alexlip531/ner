<?php
/**
 * Шаблон: компактный.
 *
 * @package CurrencyExchangeRates
 * @var array $data
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$show_flag   = ! empty( $data['show_flag'] );
$show_change = ! empty( $data['show_change'] );
$show_date   = ! empty( $data['show_date'] );

$date_formatted = '';
if ( $show_date && ! empty( $data['date'] ) ) {
	$timestamp = strtotime( $data['date'] );
	if ( false !== $timestamp ) {
		$date_formatted = wp_date( 'd.m.Y', $timestamp );
	} else {
		$date_formatted = esc_html( $data['date'] );
	}
}
?>
<div class="cer-wrapper cer-layout-compact">
	<div class="cer-header-compact">
		<h3 class="cer-title"><?php esc_html_e( 'Курс валют', 'currency-exchange-rates' ); ?></h3>
		<?php if ( $show_date && $date_formatted ) : ?>
			<span class="cer-date"><?php echo esc_html( $date_formatted ); ?></span>
		<?php endif; ?>
	</div>

	<ul class="cer-compact-list">
		<?php foreach ( $data['rates'] as $code => $info ) : ?>
			<?php
			$change_class = 'cer-no-change';
			$arrow        = '';
			if ( $info['change'] > 0 ) {
				$change_class = 'cer-up';
				$arrow        = '▲';
			} elseif ( $info['change'] < 0 ) {
				$change_class = 'cer-down';
				$arrow        = '▼';
			}
			?>
			<li class="cer-compact-item">
				<span class="cer-compact-flag-code">
					<?php if ( $show_flag ) : ?>
						<span class="cer-flag"><?php echo esc_html( CBR_API::get_flag( $code ) ); ?></span>
					<?php endif; ?>
					<span class="cer-compact-code"><?php echo esc_html( $code ); ?></span>
				</span>
				<span class="cer-compact-value"><?php echo esc_html( CBR_API::format_number( $info['value'] ) ); ?> ₽</span>
				<?php if ( $show_change ) : ?>
					<span class="cer-compact-change <?php echo esc_attr( $change_class ); ?>">
						<?php echo esc_html( $arrow ); ?>
						<?php echo esc_html( ( $info['change_pct'] > 0 ? '+' : '' ) . $info['change_pct'] ); ?>%
					</span>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
