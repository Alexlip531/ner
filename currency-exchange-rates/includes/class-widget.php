<?php
/**
 * Виджет курса валют.
 *
 * @package CurrencyExchangeRates
 */

// Запрет прямого доступа.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CER_Widget
 */
class CER_Widget extends WP_Widget {

	/**
	 * Регистрация виджета.
	 */
	public static function register() {
		register_widget( __CLASS__ );
	}

	/**
	 * Конструктор виджета.
	 */
	public function __construct() {
		parent::__construct(
			'cer_widget',
			__( 'Курс валют ЦБ РФ', 'currency-exchange-rates' ),
			array(
				'description' => __( 'Отображает актуальные курсы валют ЦБ РФ.', 'currency-exchange-rates' ),
				'classname'   => 'cer-widget',
			)
		);
	}

	/**
	 * Вывод виджета на фронте.
	 *
	 * @param array $args     Аргументы темы.
	 * @param array $instance Настройки виджета.
	 */
	public function widget( $args, $instance ) {
		$settings = get_option( 'cer_settings', array() );

		$title        = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Курс валют', 'currency-exchange-rates' );
		$currencies   = ! empty( $instance['currencies'] ) ? array_filter( array_map( 'trim', explode( ',', $instance['currencies'] ) ) ) : array( 'USD', 'EUR' );
		$show_flag    = isset( $instance['show_flag'] ) ? (bool) $instance['show_flag'] : true;
		$show_change  = isset( $instance['show_change'] ) ? (bool) $instance['show_change'] : true;
		$show_date    = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : true;

		// Заголовок виджета.
		echo wp_kses_post( $args['before_widget'] );
		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'] );
		}

		// Получаем курсы.
		$rates = CBR_API::get_rates();
		if ( is_wp_error( $rates ) ) {
			echo '<div class="cer-error">' . esc_html( $rates->get_error_message() ) . '</div>';
		} else {
			$filtered = array();
			foreach ( $currencies as $code ) {
				$code = strtoupper( $code );
				if ( isset( $rates['valute'][ $code ] ) ) {
					$filtered[ $code ] = $rates['valute'][ $code ];
				}
			}

			if ( empty( $filtered ) ) {
				echo '<div class="cer-error">' . esc_html__( 'Валюты не найдены.', 'currency-exchange-rates' ) . '</div>';
			} else {
				wp_enqueue_style( 'cer-style' );

				$date_formatted = '';
				if ( $show_date && ! empty( $rates['date'] ) ) {
					$timestamp = strtotime( $rates['date'] );
					if ( false !== $timestamp ) {
						$date_formatted = wp_date( 'd.m.Y', $timestamp );
					}
				}
				?>
				<div class="cer-wrapper cer-layout-compact cer-widget-inner">
					<?php if ( $show_date && $date_formatted ) : ?>
						<div class="cer-header-compact">
							<span class="cer-date"><?php echo esc_html( $date_formatted ); ?></span>
						</div>
					<?php endif; ?>
					<ul class="cer-compact-list">
						<?php foreach ( $filtered as $code => $info ) : ?>
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
				<?php
			}
		}

		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Форма настроек виджета в админке.
	 *
	 * @param array $instance Текущие настройки.
	 */
	public function form( $instance ) {
		$title       = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Курс валют', 'currency-exchange-rates' );
		$currencies  = ! empty( $instance['currencies'] ) ? $instance['currencies'] : 'USD,EUR';
		$show_flag   = isset( $instance['show_flag'] ) ? (bool) $instance['show_flag'] : true;
		$show_change = isset( $instance['show_change'] ) ? (bool) $instance['show_change'] : true;
		$show_date   = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : true;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Заголовок:', 'currency-exchange-rates' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
				value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'currencies' ) ); ?>"><?php esc_html_e( 'Коды валют (через запятую):', 'currency-exchange-rates' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'currencies' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'currencies' ) ); ?>" type="text"
				value="<?php echo esc_attr( $currencies ); ?>" placeholder="USD,EUR,GBP,CNY">
			<small><?php esc_html_e( 'Например: USD,EUR,GBP,CNY', 'currency-exchange-rates' ); ?></small>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_flag ); ?>
				id="<?php echo esc_attr( $this->get_field_id( 'show_flag' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'show_flag' ) ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_flag' ) ); ?>"><?php esc_html_e( 'Показывать флаги', 'currency-exchange-rates' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_change ); ?>
				id="<?php echo esc_attr( $this->get_field_id( 'show_change' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'show_change' ) ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_change' ) ); ?>"><?php esc_html_e( 'Показывать изменение', 'currency-exchange-rates' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_date ); ?>
				id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php esc_html_e( 'Показывать дату', 'currency-exchange-rates' ); ?></label>
		</p>
		<?php
	}

	/**
	 * Сохранение настроек виджета.
	 *
	 * @param array $new_instance Новые настройки.
	 * @param array $old_instance Старые настройки.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title']        = sanitize_text_field( $new_instance['title'] );
		$instance['currencies']   = sanitize_text_field( $new_instance['currencies'] );
		$instance['show_flag']    = isset( $new_instance['show_flag'] ) ? 1 : 0;
		$instance['show_change']  = isset( $new_instance['show_change'] ) ? 1 : 0;
		$instance['show_date']    = isset( $new_instance['show_date'] ) ? 1 : 0;

		return $instance;
	}
}
