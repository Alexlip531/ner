<?php
/**
 * Класс админ-страницы настроек.
 *
 * @package CurrencyExchangeRates
 */

// Запрет прямого доступа.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CER_Admin
 */
class CER_Admin {

	/**
	 * Добавление меню.
	 */
	public static function add_menu() {
		add_menu_page(
			__( 'Курс валют', 'currency-exchange-rates' ),
			__( 'Курс валют', 'currency-exchange-rates' ),
			'manage_options',
			'cer-settings',
			array( __CLASS__, 'render_page' ),
			'dashicons-money-alt',
			80
		);
	}

	/**
	 * Регистрация настроек.
	 */
	public static function register_settings() {
		register_setting(
			'cer_settings_group',
			'cer_settings',
			array( __CLASS__, 'sanitize_settings' )
		);

		add_settings_section(
			'cer_main_section',
			__( 'Основные настройки', 'currency-exchange-rates' ),
			array( __CLASS__, 'section_callback' ),
			'cer-settings'
		);

		add_settings_field(
			'currencies',
			__( 'Отображаемые валюты', 'currency-exchange-rates' ),
			array( __CLASS__, 'field_currencies' ),
			'cer-settings',
			'cer_main_section'
		);

		add_settings_field(
			'display_layout',
			__( 'Шаблон отображения', 'currency-exchange-rates' ),
			array( __CLASS__, 'field_layout' ),
			'cer-settings',
			'cer_main_section'
		);

		add_settings_field(
			'show_flag',
			__( 'Флаги стран', 'currency-exchange-rates' ),
			array( __CLASS__, 'field_checkbox' ),
			'cer-settings',
			'cer_main_section',
			array( 'key' => 'show_flag', 'label' => __( 'Показывать флаги рядом с кодом валюты', 'currency-exchange-rates' ) )
		);

		add_settings_field(
			'show_change',
			__( 'Изменение курса', 'currency-exchange-rates' ),
			array( __CLASS__, 'field_checkbox' ),
			'cer-settings',
			'cer_main_section',
			array( 'key' => 'show_change', 'label' => __( 'Показывать изменение курса за день', 'currency-exchange-rates' ) )
		);

		add_settings_field(
			'show_date',
			__( 'Дата актуальности', 'currency-exchange-rates' ),
			array( __CLASS__, 'field_checkbox' ),
			'cer-settings',
			'cer_main_section',
			array( 'key' => 'show_date', 'label' => __( 'Показывать дату и время обновления курсов', 'currency-exchange-rates' ) )
		);

		add_settings_field(
			'cache_ttl',
			__( 'Время кэширования (секунды)', 'currency-exchange-rates' ),
			array( __CLASS__, 'field_cache_ttl' ),
			'cer-settings',
			'cer_main_section'
		);
	}

	/**
	 * Описание секции.
	 */
	public static function section_callback() {
		echo '<p>' . esc_html__( 'Настройте отображение курсов валют на вашем сайте.', 'currency-exchange-rates' ) . '</p>';
	}

	/**
	 * Поле выбора валют.
	 */
	public static function field_currencies() {
		$settings   = get_option( 'cer_settings', array() );
		$selected   = isset( $settings['currencies'] ) ? (array) $settings['currencies'] : array( 'USD', 'EUR' );
		$available  = self::get_available_currencies();

		echo '<fieldset class="cer-currencies-fieldset">';
		echo '<legend class="screen-reader-text">' . esc_html__( 'Валюты', 'currency-exchange-rates' ) . '</legend>';

		foreach ( $available as $code => $name ) {
			$checked = in_array( $code, $selected, true ) ? 'checked' : '';
			?>
			<label class="cer-currency-label">
				<input type="checkbox" name="cer_settings[currencies][]"
					value="<?php echo esc_attr( $code ); ?>" <?php echo esc_attr( $checked ); ?>>
				<span class="cer-flag"><?php echo esc_html( CBR_API::get_flag( $code ) ); ?></span>
				<span class="cer-currency-code"><?php echo esc_html( $code ); ?></span>
				<span class="cer-currency-name"><?php echo esc_html( $name ); ?></span>
			</label>
			<?php
		}
		echo '</fieldset>';
		echo '<p class="description">' . esc_html__( 'Выберите валюты для отображения.', 'currency-exchange-rates' ) . '</p>';
	}

	/**
	 * Поле выбора шаблона.
	 */
	public static function field_layout() {
		$settings = get_option( 'cer_settings', array() );
		$current  = isset( $settings['display_layout'] ) ? $settings['display_layout'] : 'table';
		$options  = array(
			'table'    => __( 'Таблица (полная)', 'currency-exchange-rates' ),
			'cards'    => __( 'Карточки', 'currency-exchange-rates' ),
			'compact'  => __( 'Компактный список', 'currency-exchange-rates' ),
		);
		echo '<select name="cer_settings[display_layout]">';
		foreach ( $options as $value => $label ) {
			printf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $value ),
				selected( $current, $value, false ),
				esc_html( $label )
			);
		}
		echo '</select>';
	}

	/**
	 * Поле чекбокса.
	 *
	 * @param array $args Аргументы.
	 */
	public static function field_checkbox( $args ) {
		$settings = get_option( 'cer_settings', array() );
		$key      = $args['key'];
		$label    = $args['label'];
		$value    = isset( $settings[ $key ] ) ? (int) $settings[ $key ] : 0;
		?>
		<label>
			<input type="checkbox" name="cer_settings[<?php echo esc_attr( $key ); ?>]" value="1" <?php checked( $value, 1 ); ?>>
			<?php echo esc_html( $label ); ?>
		</label>
		<?php
	}

	/**
	 * Поле времени кэширования.
	 */
	public static function field_cache_ttl() {
		$settings = get_option( 'cer_settings', array() );
		$ttl      = isset( $settings['cache_ttl'] ) ? (int) $settings['cache_ttl'] : HOUR_IN_SECONDS;
		?>
		<input type="number" name="cer_settings[cache_ttl]"
			value="<?php echo esc_attr( $ttl ); ?>" min="60" step="60">
		<p class="description"><?php esc_html_e( 'Минимум 60 секунд. По умолчанию 3600 (1 час).', 'currency-exchange-rates' ); ?></p>
		<?php
	}

	/**
	 * Очистка и сохранение настроек.
	 *
	 * @param array $input Входные данные.
	 * @return array
	 */
	public static function sanitize_settings( $input ) {
		$output = array();

		// Валюты.
		$output['currencies'] = array();
		if ( ! empty( $input['currencies'] ) && is_array( $input['currencies'] ) ) {
			foreach ( $input['currencies'] as $code ) {
				$code = strtoupper( sanitize_text_field( $code ) );
				if ( preg_match( '/^[A-Z]{3}$/', $code ) ) {
					$output['currencies'][] = $code;
				}
			}
		}
		if ( empty( $output['currencies'] ) ) {
			$output['currencies'] = array( 'USD', 'EUR' );
		}

		// Шаблон.
		$output['display_layout'] = isset( $input['display_layout'] ) && in_array( $input['display_layout'], array( 'table', 'cards', 'compact' ), true )
			? $input['display_layout'] : 'table';

		// Чекбоксы.
		$output['show_flag']   = isset( $input['show_flag'] ) ? 1 : 0;
		$output['show_change'] = isset( $input['show_change'] ) ? 1 : 0;
		$output['show_date']   = isset( $input['show_date'] ) ? 1 : 0;

		// Время кэширования.
		$output['cache_ttl'] = isset( $input['cache_ttl'] ) ? max( 60, (int) $input['cache_ttl'] ) : HOUR_IN_SECONDS;

		// Сбрасываем кэш при сохранении.
		delete_transient( CER_CACHE_KEY );

		return $output;
	}

	/**
	 * Ссылка на настройки в списке плагинов.
	 *
	 * @param array $links Ссылки.
	 * @return array
	 */
	public static function add_action_link( $links ) {
		$settings_link = '<a href="' . admin_url( 'admin.php?page=cer-settings' ) . '">'
			. esc_html__( 'Настройки', 'currency-exchange-rates' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Получение списка доступных валют с сервера ЦБ РФ.
	 *
	 * @return array Код => Название.
	 */
	public static function get_available_currencies() {
		$rates = CBR_API::get_rates();
		if ( is_wp_error( $rates ) || empty( $rates['valute'] ) ) {
			return array(
				'USD' => 'Доллар США',
				'EUR' => 'Евро',
				'GBP' => 'Фунт стерлингов',
				'CNY' => 'Китайский юань',
				'JPY' => 'Японская иена',
				'CHF' => 'Швейцарский франк',
			);
		}

		$list = array();
		foreach ( $rates['valute'] as $code => $info ) {
			$list[ $code ] = $info['name'];
		}

		return $list;
	}

	/**
	 * Отрисовка страницы настроек.
	 */
	public static function render_page() {
		// Обработка принудительного обновления.
		if ( isset( $_POST['cer_refresh'] ) && check_admin_referer( 'cer_refresh_nonce' ) ) {
			$result = CBR_API::refresh();
			if ( is_wp_error( $result ) ) {
				echo '<div class="notice notice-error"><p>' . esc_html( $result->get_error_message() ) . '</p></div>';
			} else {
				echo '<div class="notice notice-success"><p>' . esc_html__( 'Курсы валют успешно обновлены.', 'currency-exchange-rates' ) . '</p></div>';
			}
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<div class="cer-admin-grid">
				<div class="cer-admin-main">
					<form action="options.php" method="post">
						<?php
						settings_fields( 'cer_settings_group' );
						do_settings_sections( 'cer-settings' );
						submit_button( __( 'Сохранить настройки', 'currency-exchange-rates' ) );
						?>
					</form>
				</div>

				<div class="cer-admin-sidebar">
					<div class="cer-card-info">
						<h3><?php esc_html_e( 'Как использовать', 'currency-exchange-rates' ); ?></h3>
						<h4><?php esc_html_e( 'Шорткод', 'currency-exchange-rates' ); ?></h4>
						<code>[exchange_rates]</code>
						<p><?php esc_html_e( 'Базовое использование — настройки из панели.', 'currency-exchange-rates' ); ?></p>

						<h4><?php esc_html_e( 'С параметрами', 'currency-exchange-rates' ); ?></h4>
						<code>[exchange_rates currencies="USD,EUR,GBP" layout="cards" show_flag="1" show_change="1"]</code>

						<h4><?php esc_html_e( 'Параметры шорткода', 'currency-exchange-rates' ); ?></h4>
						<ul>
							<li><strong>currencies</strong> — список кодов через запятую</li>
							<li><strong>layout</strong> — table / cards / compact</li>
							<li><strong>show_flag</strong> — 1 или 0</li>
							<li><strong>show_change</strong> — 1 или 0</li>
							<li><strong>show_date</strong> — 1 или 0</li>
						</ul>

						<h4><?php esc_html_e( 'Виджет', 'currency-exchange-rates' ); ?></h4>
						<p><?php esc_html_e( 'Перейдите в Внешний вид → Виджеты и добавьте «Курс валют ЦБ РФ» в нужную область.', 'currency-exchange-rates' ); ?></p>

						<hr>
						<h3><?php esc_html_e( 'Принудительное обновление', 'currency-exchange-rates' ); ?></h3>
						<form method="post" style="margin-top:10px;">
							<?php wp_nonce_field( 'cer_refresh_nonce' ); ?>
							<input type="hidden" name="cer_refresh" value="1">
							<?php submit_button( __( 'Обновить курсы сейчас', 'currency-exchange-rates' ), 'secondary', 'cer_refresh_btn', false ); ?>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
