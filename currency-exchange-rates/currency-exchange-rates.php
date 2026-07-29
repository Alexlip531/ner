<?php
/**
 * Plugin Name:       Курс валют
 * Plugin URI:        https://github.com/Alexlip531/ner
 * Description:       Плагин отображения курсов валют ЦБ РФ. Использует шорткод [exchange_rates], виджет и блок настроек. Курсы кэшируются на 1 час.
 * Version:           1.0.0
 * Requires at least: 5.5
 * Requires PHP:      7.2
 * Author:            Alexlip531
 * Author URI:        https://github.com/Alexlip531
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       currency-exchange-rates
 * Domain Path:       /languages
 *
 * @package CurrencyExchangeRates
 */

// Запрет прямого доступа.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Константы плагина.
define( 'CER_VERSION', '1.0.0' );
define( 'CER_PLUGIN_FILE', __FILE__ );
define( 'CER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CER_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'CER_CACHE_KEY', 'cer_rates_cache' );
define( 'CER_CACHE_TTL', HOUR_IN_SECONDS );
define( 'CER_API_URL', 'https://www.cbr-xml-daily.ru/daily_json.js' );

// Автозагрузка классов.
require_once CER_PLUGIN_DIR . 'includes/class-cbr-api.php';
require_once CER_PLUGIN_DIR . 'includes/class-shortcode.php';
require_once CER_PLUGIN_DIR . 'includes/class-widget.php';
require_once CER_PLUGIN_DIR . 'includes/class-admin.php';

/**
 * Главный класс плагина.
 */
final class Currency_Exchange_Rates {

	/**
	 * Экземпляр синглтона.
	 *
	 * @var Currency_Exchange_Rates|null
	 */
	private static $instance = null;

	/**
	 * Получение синглтон-экземпляра.
	 *
	 * @return Currency_Exchange_Rates
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
	}

	/**
	 * Инициализация хуков.
	 */
	private function init_hooks() {
		// Регистрация шорткода.
		add_action( 'init', array( 'CER_Shortcode', 'init' ) );

		// Регистрация виджета.
		add_action( 'widgets_init', array( 'CER_Widget', 'register' ) );

		// Регистрация админ-меню.
		if ( is_admin() ) {
			add_action( 'admin_menu', array( 'CER_Admin', 'add_menu' ) );
			add_action( 'admin_init', array( 'CER_Admin', 'register_settings' ) );
			add_filter( 'plugin_action_links_' . CER_PLUGIN_BASENAME, array( 'CER_Admin', 'add_action_link' ) );
		}

		// Подключение стилей на фронте.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

		// Активация плагина.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );

		// Деактивация плагина.
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
	}

	/**
	 * Подключение CSS и JS ассетов.
	 */
	public function enqueue_assets() {
		wp_register_style(
			'cer-style',
			CER_PLUGIN_URL . 'assets/css/style.css',
			array(),
			CER_VERSION
		);

		wp_register_script(
			'cer-script',
			CER_PLUGIN_URL . 'assets/js/script.js',
			array(),
			CER_VERSION,
			true
		);
	}

	/**
	 * Действия при активации плагина.
	 */
	public function activate() {
		// Установка настроек по умолчанию.
		if ( false === get_option( 'cer_settings' ) ) {
			$defaults = array(
				'currencies'      => array( 'USD', 'EUR' ),
				'show_flag'       => 1,
				'show_change'     => 1,
				'show_date'       => 1,
				'cache_ttl'       => HOUR_IN_SECONDS,
				'display_layout'  => 'table',
			);
			add_option( 'cer_settings', $defaults );
		}

		// Очистка кэша при активации.
		delete_transient( CER_CACHE_KEY );

		flush_rewrite_rules();
	}

	/**
	 * Действия при деактивации плагина.
	 */
	public function deactivate() {
		// Очистка кэша.
		delete_transient( CER_CACHE_KEY );
		flush_rewrite_rules();
	}
}

/**
 * Точка входа плагина.
 *
 * @return Currency_Exchange_Rates
 */
function cer_plugin() {
	return Currency_Exchange_Rates::instance();
}

// Запуск плагина.
cer_plugin();
