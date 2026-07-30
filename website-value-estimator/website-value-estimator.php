<?php
/**
 * Plugin Name:       Website Value Estimator
 * Plugin URI:        https://github.com/Alexlip531/ner
 * Description:       Плагин оценки стоимости сайта. Пользователь вводит ссылку на сайт, плагин проверяет его через бесплатные сервисы (Google PageSpeed Insights, RDAP, HTML-анализ) и на основе показателей выводит расчётную стоимость сайта с детальным отчётом.
 * Version:           1.0.0
 * Author:            Alexlip531
 * Author URI:        https://github.com/Alexlip531
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       website-value-estimator
 * Domain Path:       /languages
 *
 * @package WebsiteValueEstimator
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Запрет прямого доступа.
}

define( 'WVE_VERSION', '1.0.0' );
define( 'WVE_PLUGIN_FILE', __FILE__ );
define( 'WVE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WVE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WVE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Автозагрузка классов плагина.
 *
 * @param string $class Имя класса.
 * @return void
 */
function wve_autoload( $class ) {
    if ( strpos( $class, 'WVE_' ) !== 0 ) {
        return;
    }
    $file = WVE_PLUGIN_DIR . 'includes/class-' . strtolower( str_replace( array( 'WVE_', '_' ), array( '', '-' ), $class ) ) . '.php';
    if ( file_exists( $file ) ) {
        require_once $file;
    }
}
spl_autoload_register( 'wve_autoload' );

/**
 * Главный класс плагина (singleton).
 */
final class WVE_Plugin {

    /**
     * Экземпляр singleton.
     *
     * @var WVE_Plugin|null
     */
    private static $instance = null;

    /**
     * Получить экземпляр.
     *
     * @return WVE_Plugin
     */
    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Конструктор.
     */
    private function __construct() {
        $this->init_hooks();
        $this->init_components();
    }

    /**
     * Хуки активации/деактивации.
     *
     * @return void
     */
    private function init_hooks() {
        register_activation_hook( WVE_PLUGIN_FILE, array( $this, 'activate' ) );
        register_deactivation_hook( WVE_PLUGIN_FILE, array( $this, 'deactivate' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
    }

    /**
     * Инициализация компонентов.
     *
     * @return void
     */
    private function init_components() {
        WVE_Settings::instance();
        WVE_API_Client::instance();
        WVE_Estimator::instance();
        WVE_Shortcode::instance();
        WVE_Ajax::instance();
    }

    /**
     * Активация плагина.
     *
     * @return void
     */
    public function activate() {
        // Настройки по умолчанию.
        $defaults = array(
            'api_key'             => '',
            'cache_ttl'           => 3600,
            'base_value'          => 1000,
            'currency'            => 'USD',
            'show_detailed'       => 1,
            'enable_caching'      => 1,
            'request_timeout'     => 30,
            'enable_history'      => 1,
        );
        if ( false === get_option( 'wve_settings' ) ) {
            add_option( 'wve_settings', $defaults );
        }
        // Таблица истории оценок.
        $this->create_history_table();
        flush_rewrite_rules();
    }

    /**
     * Деактивация плагина.
     *
     * @return void
     */
    public function deactivate() {
        flush_rewrite_rules();
    }

    /**
     * Создание таблицы истории оценок.
     *
     * @return void
     */
    private function create_history_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wve_estimates';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            url VARCHAR(2048) NOT NULL,
            domain VARCHAR(255) NOT NULL,
            estimated_value BIGINT(20) UNSIGNED DEFAULT 0,
            currency VARCHAR(10) DEFAULT 'USD',
            metrics LONGTEXT,
            created_at DATETIME NOT NULL,
            ip_address VARCHAR(100) DEFAULT '',
            PRIMARY KEY (id),
            KEY domain (domain),
            KEY created_at (created_at)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }

    /**
     * Подключение CSS и JS ассетов.
     *
     * @return void
     */
    public function enqueue_assets() {
        wp_enqueue_style(
            'wve-style',
            WVE_PLUGIN_URL . 'assets/css/style.css',
            array(),
            WVE_VERSION
        );

        wp_enqueue_script(
            'wve-script',
            WVE_PLUGIN_URL . 'assets/js/script.js',
            array(),
            WVE_VERSION,
            true
        );

        wp_localize_script(
            'wve-script',
            'wve_data',
            array(
                'ajax_url'   => admin_url( 'admin-ajax.php' ),
                'nonce'      => wp_create_nonce( 'wve_nonce' ),
                'i18n'       => array(
                    'analyzing'      => __( 'Анализируем сайт...', 'website-value-estimator' ),
                    'fetching'       => __( 'Получаем данные...', 'website-value-estimator' ),
                    'calculating'    => __( 'Рассчитываем стоимость...', 'website-value-estimator' ),
                    'error'          => __( 'Произошла ошибка. Попробуйте ещё раз.', 'website-value-estimator' ),
                    'invalid_url'    => __( 'Введите корректный URL сайта', 'website-value-estimator' ),
                    'please_wait'    => __( 'Пожалуйста, подождите. Это может занять до 30 секунд.', 'website-value-estimator' ),
                ),
                'currency'  => WVE_Settings::get( 'currency', 'USD' ),
                'timeout'   => intval( WVE_Settings::get( 'request_timeout', 30 ) ) * 1000,
            )
        );
    }
}

/**
 * Точка входа.
 *
 * @return WVE_Plugin
 */
function wve_plugin() {
    return WVE_Plugin::instance();
}

// Запуск плагина.
wve_plugin();
