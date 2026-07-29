<?php
/**
 * Деинсталляция плагина.
 *
 * @package CurrencyExchangeRates
 */

// Запрет прямого доступа.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Удаляем опции.
delete_option( 'cer_settings' );

// Удаляем кэш.
delete_transient( 'cer_rates_cache' );

// Опционально: очистка всех записей кэша, начинающихся с cer_.
global $wpdb;
$wpdb->query(
	"DELETE FROM {$wpdb->options}
	 WHERE option_name LIKE '\_transient\_cer\_%'
	    OR option_name LIKE '\_transient\_timeout\_cer\_%'"
);
