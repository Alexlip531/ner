<?php
/**
 * Класс для работы с API ЦБ РФ.
 *
 * @package CurrencyExchangeRates
 */

// Запрет прямого доступа.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Класс CBR_API
 *
 * Получает и кэширует курсы валют с https://www.cbr-xml-daily.ru/
 */
class CBR_API {

	/**
	 * Получение курсов валют (с кэшированием).
	 *
	 * @return array|WP_Error Массив курсов или WP_Error.
	 */
	public static function get_rates() {
		// Попытка получить из кэша.
		$cached = get_transient( CER_CACHE_KEY );
		if ( false !== $cached && is_array( $cached ) ) {
			return $cached;
		}

		// Запрос к API.
		$response = wp_remote_get(
			CER_API_URL,
			array(
				'timeout'     => 10,
				'redirection' => 3,
				'headers'     => array(
					'Accept'       => 'application/json',
					'User-Agent'   => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . home_url(),
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== (int) $code ) {
			return new WP_Error(
				'cer_api_http_error',
				sprintf(
					/* translators: %d: HTTP код ответа */
					__( 'Ошибка API ЦБ РФ. HTTP код: %d', 'currency-exchange-rates' ),
					$code
				)
			);
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( JSON_ERROR_NONE !== json_last_error() || ! isset( $data['Valute'] ) ) {
			return new WP_Error(
				'cer_api_parse_error',
				__( 'Не удалось разобрать ответ API ЦБ РФ.', 'currency-exchange-rates' )
			);
		}

		// Формируем чистый массив.
		$rates = array(
			'date'    => isset( $data['Date'] ) ? $data['Date'] : current_time( 'mysql' ),
			'previous_date' => isset( $data['PreviousDate'] ) ? $data['PreviousDate'] : '',
			'base'    => 'RUB',
			'updated' => current_time( 'mysql' ),
			'valute'  => array(),
		);

		foreach ( $data['Valute'] as $code => $info ) {
			$rates['valute'][ $code ] = array(
				'num_code'  => isset( $info['NumCode'] ) ? $info['NumCode'] : '',
				'char_code' => isset( $info['CharCode'] ) ? $info['CharCode'] : $code,
				'nominal'   => isset( $info['Nominal'] ) ? (int) $info['Nominal'] : 1,
				'name'      => isset( $info['Name'] ) ? $info['Name'] : $code,
				'value'     => isset( $info['Value'] ) ? (float) $info['Value'] : 0,
				'previous'  => isset( $info['Previous'] ) ? (float) $info['Previous'] : 0,
				'change'    => 0,
				'change_pct' => 0,
			);

			// Расчёт изменения.
			if ( ! empty( $rates['valute'][ $code ]['previous'] ) ) {
				$rates['valute'][ $code ]['change']     = round( $rates['valute'][ $code ]['value'] - $rates['valute'][ $code ]['previous'], 4 );
				$rates['valute'][ $code ]['change_pct'] = round( ( $rates['valute'][ $code ]['change'] / $rates['valute'][ $code ]['previous'] ) * 100, 2 );
			}
		}

		// Сохраняем в кэш (с учётом пользовательского TTL).
		$settings = get_option( 'cer_settings', array() );
		$ttl      = isset( $settings['cache_ttl'] ) ? (int) $settings['cache_ttl'] : CER_CACHE_TTL;
		$ttl      = max( 60, $ttl ); // минимум 1 минута.

		set_transient( CER_CACHE_KEY, $rates, $ttl );

		return $rates;
	}

	/**
	 * Принудительное обновление кэша.
	 *
	 * @return array|WP_Error
	 */
	public static function refresh() {
		delete_transient( CER_CACHE_KEY );
		return self::get_rates();
	}

	/**
	 * Получение флага страны по коду валюты (эмодзи).
	 *
	 * @param string $char_code Код валюты (например, USD).
	 * @return string HTML эмодзи флага.
	 */
	public static function get_flag( $char_code ) {
		$flags = array(
			'USD' => '🇺🇸',
			'EUR' => '🇪🇺',
			'GBP' => '🇬🇧',
			'JPY' => '🇯🇵',
			'CNY' => '🇨🇳',
			'CHF' => '🇨🇭',
			'CAD' => '🇨🇦',
			'AUD' => '🇦🇺',
			'BYN' => '🇧🇾',
			'UAH' => '🇺🇦',
			'KZT' => '🇰🇿',
			'UZS' => '🇺🇿',
			'TRY' => '🇹🇷',
			'INR' => '🇮🇳',
			'BRL' => '🇧🇷',
			'ZAR' => '🇿🇦',
			'SGD' => '🇸🇬',
			'HKD' => '🇭🇰',
			'NOK' => '🇳🇴',
			'SEK' => '🇸🇪',
			'DKK' => '🇩🇰',
			'PLN' => '🇵🇱',
			'CZK' => '🇨🇿',
			'HUF' => '🇭🇺',
			'KRW' => '🇰🇷',
			'MXN' => '🇲🇽',
			'AED' => '🇦🇪',
			'ILS' => '🇮🇱',
			'AMD' => '🇦🇲',
			'AZN' => '🇦🇿',
			'KGS' => '🇰🇬',
			'MDL' => '🇲🇩',
			'TJS' => '🇹🇯',
			'TMT' => '🇹🇲',
			'GEL' => '🇬🇪',
			'BGN' => '🇧🇬',
			'RON' => '🇷🇴',
			'NZD' => '🇳🇿',
		);

		return isset( $flags[ $char_code ] ) ? $flags[ $char_code ] : '🏳️';
	}

	/**
	 * Форматирование числа с разделителями тысяч.
	 *
	 * @param float  $value  Значение.
	 * @param int    $decimal Количество знаков после запятой.
	 * @return string
	 */
	public static function format_number( $value, $decimal = 2 ) {
		return number_format( (float) $value, $decimal, ',', ' ' );
	}
}
