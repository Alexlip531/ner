<?php
/**
 * Шаблон: карточки.
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
		$date_formatted = wp_date( 'd.m.Y H:i', $timestamp );
	} else {
		$date_formatted = esc_html( $data['date'] );
	}
}
?>
<div class="cer-wrapper cer-layout-cards">
	<div class="cer-header">
		<h3 class="cer-title"><?php esc_html_e( 'Курс валют ЦБ РФ', 'currency-exchange-rates' ); ?></h3>
		<?php if ( $show_date && $date_formatted ) : ?>
			<span class="cer-date"><?php echo esc_html( $date_formatted ); ?></span>
		<?php endif; ?>
	</div>

	<div class="cer-cards">
		<?php foreach ( $data['rates'] as $code => $info ) : ?>
			<?php
			$change_class = 'cer-no-change';
			$arrow        = '→';
			if ( $info['change'] > 0 ) {
				$change_class = 'cer-up';
				$arrow        = '▲';
			} elseif ( $info['change'] < 0 ) {
				$change_class = 'cer-down';
				$arrow        = '▼';
			}
			?>
			<div class="cer-card <?php echo esc_attr( $change_class ); ?>">
				<div class="cer-card-top">
					<?php if ( $show_flag ) : ?>
						<span class="cer-flag"><?php echo esc_html( CBR_API::get_flag( $code ) ); ?></span>
					<?php endif; ?>
					<span class="cer-card-code"><?php echo esc_html( $code ); ?></span>
					<span class="cer-card-nominal"><?php echo esc_html( $info['nominal'] ); ?> <?php echo esc_html( $code ); ?></span>
				</div>
				<div class="cer-card-name"><?php echo esc_html( $info['name'] ); ?></div>
				<div class="cer-card-value">
					<span class="cer-value"><?php echo esc_html( CBR_API::format_number( $info['value'] ) ); ?></span>
					<span class="cer-unit">₽</span>
				</div>
				<?php if ( $show_change ) : ?>
					<div class="cer-card-change <?php echo esc_attr( $change_class ); ?>">
						<?php echo esc_html( $arrow ); ?>
						<?php echo esc_html( CBR_API::format_number( abs( $info['change'] ), 4 ) ); ?> ₽
						<?php if ( 0 !== $info['change_pct'] ) : ?>
							(<?php echo esc_html( ( $info['change_pct'] > 0 ? '+' : '' ) . $info['change_pct'] ); ?>%)
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="cer-footer">
		<span class="cer-source"><?php esc_html_e( 'Источник: ЦБ РФ', 'currency-exchange-rates' ); ?></span>
	</div>
</div>
