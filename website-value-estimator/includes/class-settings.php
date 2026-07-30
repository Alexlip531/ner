<?php
/**
 * Класс управления настройками плагина.
 *
 * @package WebsiteValueEstimator
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * WVE_Settings — singleton для управления настройками.
 */
class WVE_Settings {

    /**
     * Экземпляр.
     *
     * @var WVE_Settings|null
     */
    private static $instance = null;

    /**
     * Настройки.
     *
     * @var array
     */
    private $settings = null;

    /**
     * Получить экземпляр.
     *
     * @return WVE_Settings
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
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_filter( 'plugin_action_links_' . WVE_PLUGIN_BASENAME, array( $this, 'add_action_links' ) );
    }

    /**
     * Получить настройку.
     *
     * @param string $key     Ключ.
     * @param mixed  $default Значение по умолчанию.
     * @return mixed
     */
    public static function get( $key, $default = '' ) {
        $instance = self::instance();
        if ( null === $instance->settings ) {
            $instance->settings = get_option( 'wve_settings', array() );
        }
        return isset( $instance->settings[ $key ] ) ? $instance->settings[ $key ] : $default;
    }

    /**
     * Добавить пункт меню.
     *
     * @return void
     */
    public function add_admin_menu() {
        add_menu_page(
            __( 'Оценка стоимости сайтов', 'website-value-estimator' ),
            __( 'Оценка сайтов', 'website-value-estimator' ),
            'manage_options',
            'wve-settings',
            array( $this, 'render_settings_page' ),
            'dashicons-chart-line',
            80
        );

        add_submenu_page(
            'wve-settings',
            __( 'История оценок', 'website-value-estimator' ),
            __( 'История', 'website-value-estimator' ),
            'manage_options',
            'wve-history',
            array( $this, 'render_history_page' )
        );
    }

    /**
     * Регистрация настроек.
     *
     * @return void
     */
    public function register_settings() {
        register_setting( 'wve_settings_group', 'wve_settings', array( $this, 'sanitize_settings' ) );

        add_settings_section(
            'wve_main_section',
            __( 'Основные настройки', 'website-value-estimator' ),
            array( $this, 'render_section_info' ),
            'wve-settings'
        );

        add_settings_field( 'api_key', __( 'Google PageSpeed API Key', 'website-value-estimator' ), array( $this, 'render_api_key_field' ), 'wve-settings', 'wve_main_section' );
        add_settings_field( 'cache_ttl', __( 'Время кэширования (сек)', 'website-value-estimator' ), array( $this, 'render_cache_ttl_field' ), 'wve-settings', 'wve_main_section' );
        add_settings_field( 'base_value', __( 'Базовая стоимость ($)', 'website-value-estimator' ), array( $this, 'render_base_value_field' ), 'wve-settings', 'wve_main_section' );
        add_settings_field( 'currency', __( 'Валюта отображения', 'website-value-estimator' ), array( $this, 'render_currency_field' ), 'wve-settings', 'wve_main_section' );
        add_settings_field( 'request_timeout', __( 'Таймаут запроса (сек)', 'website-value-estimator' ), array( $this, 'render_timeout_field' ), 'wve-settings', 'wve_main_section' );
        add_settings_field( 'enable_caching', __( 'Кэширование результатов', 'website-value-estimator' ), array( $this, 'render_caching_field' ), 'wve-settings', 'wve_main_section' );
        add_settings_field( 'enable_history', __( 'Сохранять историю оценок', 'website-value-estimator' ), array( $this, 'render_history_field' ), 'wve-settings', 'wve_main_section' );
        add_settings_field( 'show_detailed', __( 'Подробный отчёт', 'website-value-estimator' ), array( $this, 'render_detailed_field' ), 'wve-settings', 'wve_main_section' );
    }

    /**
     * Очистка настроек.
     *
     * @param array $input Входные данные.
     * @return array
     */
    public function sanitize_settings( $input ) {
        $output = array();
        $output['api_key']         = isset( $input['api_key'] ) ? sanitize_text_field( $input['api_key'] ) : '';
        $output['cache_ttl']       = isset( $input['cache_ttl'] ) ? max( 60, intval( $input['cache_ttl'] ) ) : 3600;
        $output['base_value']      = isset( $input['base_value'] ) ? max( 0, intval( $input['base_value'] ) ) : 1000;
        $output['currency']        = isset( $input['currency'] ) ? sanitize_text_field( $input['currency'] ) : 'USD';
        $output['request_timeout'] = isset( $input['request_timeout'] ) ? max( 5, min( 60, intval( $input['request_timeout'] ) ) ) : 30;
        $output['enable_caching']  = isset( $input['enable_caching'] ) ? 1 : 0;
        $output['enable_history']  = isset( $input['enable_history'] ) ? 1 : 0;
        $output['show_detailed']   = isset( $input['show_detailed'] ) ? 1 : 0;
        return $output;
    }

    /**
     * Информация по секции.
     *
     * @return void
     */
    public function render_section_info() {
        echo '<p>' . esc_html__( 'Настройте параметры оценки стоимости сайтов. Google PageSpeed API Key опционален — без него доступно ограниченное количество запросов.', 'website-value-estimator' ) . '</p>';
    }

    public function render_api_key_field() {
        $val = self::get( 'api_key', '' );
        echo '<input type="text" name="wve_settings[api_key]" value="' . esc_attr( $val ) . '" class="regular-text" placeholder="AIzaSy..." />';
        echo '<p class="description">' . esc_html__( 'Получить бесплатный ключ: Google Cloud Console → PageSpeed Insights API', 'website-value-estimator' ) . '</p>';
    }

    public function render_cache_ttl_field() {
        $val = self::get( 'cache_ttl', 3600 );
        echo '<input type="number" name="wve_settings[cache_ttl]" value="' . esc_attr( $val ) . '" min="60" step="60" class="small-text" />';
        echo '<p class="description">' . esc_html__( 'Минимум 60 секунд. По умолчанию 3600 (1 час).', 'website-value-estimator' ) . '</p>';
    }

    public function render_base_value_field() {
        $val = self::get( 'base_value', 1000 );
        echo '<input type="number" name="wve_settings[base_value]" value="' . esc_attr( $val ) . '" min="0" step="100" class="small-text" />';
        echo '<p class="description">' . esc_html__( 'Базовая стоимость, к которой добавляются бонусы за метрики.', 'website-value-estimator' ) . '</p>';
    }

    public function render_currency_field() {
        $val = self::get( 'currency', 'USD' );
        $currencies = array( 'USD' => 'USD ($)', 'EUR' => 'EUR (€)', 'RUB' => 'RUB (₽)', 'UAH' => 'UAH (₴)', 'KZT' => 'KZT (₸)' );
        echo '<select name="wve_settings[currency]">';
        foreach ( $currencies as $code => $label ) {
            echo '<option value="' . esc_attr( $code ) . '" ' . selected( $val, $code, false ) . '>' . esc_html( $label ) . '</option>';
        }
        echo '</select>';
    }

    public function render_timeout_field() {
        $val = self::get( 'request_timeout', 30 );
        echo '<input type="number" name="wve_settings[request_timeout]" value="' . esc_attr( $val ) . '" min="5" max="60" class="small-text" />';
        echo '<p class="description">' . esc_html__( 'От 5 до 60 секунд. Дольше — точнее, но медленнее.', 'website-value-estimator' ) . '</p>';
    }

    public function render_caching_field() {
        $val = self::get( 'enable_caching', 1 );
        echo '<label><input type="checkbox" name="wve_settings[enable_caching]" value="1" ' . checked( $val, 1, false ) . ' /> ' . esc_html__( 'Кэшировать результаты оценок', 'website-value-estimator' ) . '</label>';
    }

    public function render_history_field() {
        $val = self::get( 'enable_history', 1 );
        echo '<label><input type="checkbox" name="wve_settings[enable_history]" value="1" ' . checked( $val, 1, false ) . ' /> ' . esc_html__( 'Сохранять все оценки в БД', 'website-value-estimator' ) . '</label>';
    }

    public function render_detailed_field() {
        $val = self::get( 'show_detailed', 1 );
        echo '<label><input type="checkbox" name="wve_settings[show_detailed]" value="1" ' . checked( $val, 1, false ) . ' /> ' . esc_html__( 'Показывать подробный отчёт по метрикам', 'website-value-estimator' ) . '</label>';
    }

    /**
     * Страница настроек.
     *
     * @return void
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Оценка стоимости сайтов', 'website-value-estimator' ); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'wve_settings_group' );
                do_settings_sections( 'wve-settings' );
                submit_button();
                ?>
            </form>

            <h2><?php esc_html_e( 'Как использовать', 'website-value-estimator' ); ?></h2>
            <p><?php esc_html_e( 'Вставьте шорткод на любую страницу или запись:', 'website-value-estimator' ); ?></p>
            <p><code>[site_value_calculator]</code></p>
            <p><?php esc_html_e( 'Дополнительные параметры шорткода:', 'website-value-estimator' ); ?></p>
            <ul style="list-style: disc; padding-left: 20px;">
                <li><code>[site_value_calculator title="Оценка сайта" placeholder="example.com"]</code></li>
                <li><code>[site_value_calculator show_history="1"]</code> — <?php esc_html_e( 'показать последние оценки', 'website-value-estimator' ); ?></li>
            </ul>
        </div>
        <?php
    }

    /**
     * Страница истории.
     *
     * @return void
     */
    public function render_history_page() {
        global $wpdb;
        $table = $wpdb->prefix . 'wve_estimates';
        $items = $wpdb->get_results( "SELECT * FROM {$table} ORDER BY created_at DESC LIMIT 100" );

        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'История оценок', 'website-value-estimator' ); ?></h1>
            <?php if ( empty( $items ) ) : ?>
                <p><?php esc_html_e( 'Пока нет сохранённых оценок.', 'website-value-estimator' ); ?></p>
            <?php else : ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th><?php esc_html_e( 'URL', 'website-value-estimator' ); ?></th>
                            <th width="150"><?php esc_html_e( 'Стоимость', 'website-value-estimator' ); ?></th>
                            <th width="160"><?php esc_html_e( 'Дата', 'website-value-estimator' ); ?></th>
                            <th width="120"><?php esc_html_e( 'IP', 'website-value-estimator' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $items as $item ) : ?>
                            <tr>
                                <td><?php echo esc_html( $item->id ); ?></td>
                                <td><a href="<?php echo esc_attr( $item->url ); ?>" target="_blank"><?php echo esc_html( $item->url ); ?></a></td>
                                <td><strong><?php echo esc_html( number_format( (float) $item->estimated_value, 0, ',', ' ' ) . ' ' . $item->currency ); ?></strong></td>
                                <td><?php echo esc_html( $item->created_at ); ?></td>
                                <td><?php echo esc_html( $item->ip_address ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Ссылки в списке плагинов.
     *
     * @param array $links Ссылки.
     * @return array
     */
    public function add_action_links( $links ) {
        $settings_link = '<a href="admin.php?page=wve-settings">' . __( 'Настройки', 'website-value-estimator' ) . '</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }
}
