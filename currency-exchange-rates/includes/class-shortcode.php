<?php
/**
 * Класс шорткода [exchange_rates].
 *
 * @package CurrencyExchangeRates
 */

// Запрет прямого доступа.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CER_Shortcode
 */
class CER_Shortcode {

	/**
	 * Регистрация шорткода.
	 */
	public static function init() {
		add_shortcode( 'exchange_rates', array( __CLASS__, 'render' ) );
	}

	/**
	 * Отрисовка шорткода.
	 *
	 * @param array $atts Атрибуты шорткода.
	 *     - currencies:  список кодов валют через запятую (например: USD,EUR,GBP).
	 *     - layout:      table|cards|compact — вариант отображения.
	 *     - show_flag:   1|0 — показывать ли флаги.
	 *     - show_change: 1|0 — показывать изменение курса.
	 *     - show_date:   1|0 — показывать дату актуальности.
	 *
	 * @return string HTML.
	 */
	public static function render( $atts = array() ) {
		$settings = get_option( 'cer_settings', array() );

		$atts = shortcode_atts(
			array(
				'currencies'  => '',
				'layout'      => isset( $settings['display_layout'] ) ? $settings['display_layout'] : 'table',
				'show_flag'   => isset( $settings['show_flag'] ) ? (int) $settings['show_flag'] : 1,
				'show_change' => isset( $settings['show_change'] ) ? (int) $settings['show_change'] : 1,
				'show_date'   => isset( $settings['show_date'] ) ? (int) $settings['show_date'] : 1,
			),
			$atts,
			'exchange_rates'
		);

		// Определяем список валют.
		$currencies = array();
		if ( ! empty( $atts['currencies'] ) ) {
			$parts = array_map( 'trim', explode( ',', $atts['currencies'] ) );
			foreach ( $parts as $c ) {
				if ( '' !== $c ) {
					$currencies[] = strtoupper( $c );
				}
			}
		}
		if ( empty( $currencies ) ) {
			$currencies = isset( $settings['currencies'] ) ? (array) $settings['currencies'] : array( 'USD', 'EUR' );
		}

		// Получаем курсы.
		$rates = CBR_API::get_rates();
		if ( is_wp_error( $rates ) ) {
			return '<div class="cer-error">' . esc_html( $rates->get_error_message() ) . '</div>';
		}
		if ( empty( $rates['valute'] ) ) {
			return '<div class="cer-error">' . esc_html__( 'Курсы валют временно недоступны.', 'currency-exchange-rates' ) . '</div>';
		}

		// Фильтруем только выбранные валюты.
		$filtered = array();
		foreach ( $currencies as $code ) {
			if ( isset( $rates['valute'][ $code ] ) ) {
				$filtered[ $code ] = $rates['valute'][ $code ];
			}
		}

		if ( empty( $filtered ) ) {
			return '<div class="cer-error">' . esc_html__( 'Выбранные валюты не найдены в ответе API.', 'currency-exchange-rates' ) . '</div>';
		}

		// Подключаем стили и скрипты.
		wp_enqueue_style( 'cer-style' );
		wp_enqueue_script( 'cer-script' );

		// Готовим данные для шаблона.
		$data = array(
			'rates'       => $filtered,
			'show_flag'   => (int) $atts['show_flag'],
			'show_change' => (int) $atts['show_change'],
			'show_date'   => (int) $atts['show_date'],
			'date'        => isset( $rates['date'] ) ? $rates['date'] : '',
			'updated'     => isset( $rates['updated'] ) ? $rates['updated'] : '',
		);

		// Выбор шаблона.
		$layout = in_array( $atts['layout'], array( 'table', 'cards', 'compact' ), true ) ? $atts['layout'] : 'table';

		ob_start();
		self::render_template( $layout, $data );
		return ob_get_clean();
	}

	/**
	 * Подключение шаблона отображения.
	 *
	 * @param string $layout  Тип шаблона.
	 * @param array  $data    Данные.
	 */
	private static function render_template( $layout, $data ) {
		$template_path = CER_PLUGIN_DIR . 'includes/templates/' . $layout . '.php';

		if ( file_exists( $template_path ) ) {
			include $template_path;
		} else {
			echo '<div class="cer-error">' . esc_html__( 'Шаблон не найден.', 'currency-exchange-rates' ) . '</div>';
		}
	}
}
