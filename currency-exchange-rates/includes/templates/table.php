<?php
/**
 * Шаблон: таблица.
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
<div class="cer-wrapper cer-layout-table">
	<div class="cer-header">
		<h3 class="cer-title"><?php esc_html_e( 'Курс валют ЦБ РФ', 'currency-exchange-rates' ); ?></h3>
		<?php if ( $show_date && $date_formatted ) : ?>
			<span class="cer-date"><?php echo esc_html( $date_formatted ); ?></span>
		<?php endif; ?>
	</div>

	<table class="cer-table">
		<thead>
			<tr>
				<?php if ( $show_flag ) : ?>
					<th class="cer-th-flag"><?php esc_html_e( 'Флаг', 'currency-exchange-rates' ); ?></th>
				<?php endif; ?>
				<th class="cer-th-code"><?php esc_html_e( 'Код', 'currency-exchange-rates' ); ?></th>
				<th class="cer-th-name"><?php esc_html_e( 'Валюта', 'currency-exchange-rates' ); ?></th>
				<th class="cer-th-value"><?php esc_html_e( 'Курс', 'currency-exchange-rates' ); ?></th>
				<?php if ( $show_change ) : ?>
					<th class="cer-th-change"><?php esc_html_e( 'Изменение', 'currency-exchange-rates' ); ?></th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $data['rates'] as $code => $info ) : ?>
				<tr>
					<?php if ( $show_flag ) : ?>
						<td class="cer-td-flag"><span class="cer-flag"><?php echo esc_html( CBR_API::get_flag( $code ) ); ?></span></td>
					<?php endif; ?>
					<td class="cer-td-code"><?php echo esc_html( $code ); ?></td>
					<td class="cer-td-name"><?php echo esc_html( $info['name'] ); ?>
						<span class="cer-nominal"><?php echo esc_html( sprintf( __( '%d ед.', 'currency-exchange-rates' ), $info['nominal'] ) ); ?></span>
					</td>
					<td class="cer-td-value"><?php echo esc_html( CBR_API::format_number( $info['value'] ) ); ?> ₽</td>
					<?php if ( $show_change ) : ?>
						<td class="cer-td-change">
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
							<span class="cer-change <?php echo esc_attr( $change_class ); ?>">
								<?php echo esc_html( $arrow ); ?>
								<?php echo esc_html( CBR_API::format_number( abs( $info['change'] ), 4 ) ); ?>
								<?php if ( 0 !== $info['change_pct'] ) : ?>
									(<?php echo esc_html( ( $info['change_pct'] > 0 ? '+' : '' ) . $info['change_pct'] ); ?>%)
								<?php endif; ?>
							</span>
						</td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div class="cer-footer">
		<span class="cer-source"><?php esc_html_e( 'Источник: ЦБ РФ', 'currency-exchange-rates' ); ?></span>
	</div>
</div>
