<?php
/**
 * Удаление плагина — очистка данных.
 *
 * @package WebsiteValueEstimator
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Удаляем опции.
delete_option( 'wve_settings' );

// Удаляем транзиенты (кэш).
global $wpdb;
$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_wve_%' OR option_name LIKE '_transient_timeout_wve_%'" );

// Удаляем таблицу истории.
$table = $wpdb->prefix . 'wve_estimates';
$wpdb->query( "DROP TABLE IF EXISTS {$table}" );
