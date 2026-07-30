<?php
/**
 * Шорткод для отображения формы оценки.
 *
 * @package WebsiteValueEstimator
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * WVE_Shortcode — фронтенд-форма.
 */
class WVE_Shortcode {

    /**
     * Экземпляр.
     *
     * @var WVE_Shortcode|null
     */
    private static $instance = null;

    /**
     * Получить экземпляр.
     *
     * @return WVE_Shortcode
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
        add_shortcode( 'site_value_calculator', array( $this, 'render_shortcode' ) );
        add_shortcode( 'website_value', array( $this, 'render_shortcode' ) );
        add_shortcode( 'site_estimator', array( $this, 'render_shortcode' ) );
    }

    /**
     * Рендер шорткода.
     *
     * @param array $atts Атрибуты.
     * @return string
     */
    public function render_shortcode( $atts ) {
        $atts = shortcode_atts(
            array(
                'title'        => __( 'Оценка стоимости сайта', 'website-value-estimator' ),
                'subtitle'     => __( 'Введите URL сайта — мы проверим его через сервисы оценки и покажем расчётную стоимость', 'website-value-estimator' ),
                'placeholder'  => 'example.com',
                'button_text'  => __( 'Оценить сайт', 'website-value-estimator' ),
                'show_history' => 0,
                'history_limit' => 5,
            ),
            $atts,
            'site_value_calculator'
        );

        ob_start();
        ?>
        <div class="wve-wrapper" id="wve-app">
            <div class="wve-card wve-calculator">
                <?php if ( ! empty( $atts['title'] ) ) : ?>
                    <h2 class="wve-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                <?php endif; ?>
                <?php if ( ! empty( $atts['subtitle'] ) ) : ?>
                    <p class="wve-subtitle"><?php echo esc_html( $atts['subtitle'] ); ?></p>
                <?php endif; ?>

                <form class="wve-form" id="wve-form" autocomplete="off">
                    <div class="wve-input-group">
                        <span class="wve-input-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="2" y1="12" x2="22" y2="12"/>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            name="wve_url"
                            id="wve-url"
                            class="wve-input"
                            placeholder="<?php echo esc_attr( $atts['placeholder'] ); ?>"
                            required
                        />
                        <button type="submit" class="wve-button" id="wve-submit">
                            <span class="wve-button-text"><?php echo esc_html( $atts['button_text'] ); ?></span>
                            <span class="wve-button-spinner" aria-hidden="true"></span>
                        </button>
                    </div>
                    <p class="wve-hint"><?php esc_html_e( 'Например: yandex.ru, google.com, https://example.com', 'website-value-estimator' ); ?></p>
                </form>

                <div class="wve-progress" id="wve-progress" hidden>
                    <div class="wve-progress-steps">
                        <div class="wve-step" data-step="1">
                            <span class="wve-step-num">1</span>
                            <span class="wve-step-text"><?php esc_html_e( 'Получаем HTML', 'website-value-estimator' ); ?></span>
                        </div>
                        <div class="wve-step" data-step="2">
                            <span class="wve-step-num">2</span>
                            <span class="wve-step-text"><?php esc_html_e( 'Проверяем домен', 'website-value-estimator' ); ?></span>
                        </div>
                        <div class="wve-step" data-step="3">
                            <span class="wve-step-num">3</span>
                            <span class="wve-step-text"><?php esc_html_e( 'PageSpeed Insights', 'website-value-estimator' ); ?></span>
                        </div>
                        <div class="wve-step" data-step="4">
                            <span class="wve-step-num">4</span>
                            <span class="wve-step-text"><?php esc_html_e( 'Расчёт стоимости', 'website-value-estimator' ); ?></span>
                        </div>
                    </div>
                    <div class="wve-progress-bar"><div class="wve-progress-fill"></div></div>
                    <p class="wve-progress-text"><?php esc_html_e( 'Анализируем сайт...', 'website-value-estimator' ); ?></p>
                </div>

                <div class="wve-result" id="wve-result" hidden></div>

                <div class="wve-error" id="wve-error" hidden></div>
            </div>

            <?php if ( ! empty( $atts['show_history'] ) ) : ?>
                <?php $this->render_history_block( intval( $atts['history_limit'] ) ); ?>
            <?php endif; ?>

            <div class="wve-features">
                <div class="wve-feature">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    <h4><?php esc_html_e( 'Точный анализ', 'website-value-estimator' ); ?></h4>
                    <p><?php esc_html_e( 'Проверяем 30+ метрик: PageSpeed, SEO, SSL, домен, контент', 'website-value-estimator' ); ?></p>
                </div>
                <div class="wve-feature">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M3 3v18h18"/>
                        <path d="M18 17V9M13 17V5M8 17v-3"/>
                    </svg>
                    <h4><?php esc_html_e( 'Прозрачная формула', 'website-value-estimator' ); ?></h4>
                    <p><?php esc_html_e( 'Показываем, как именно сложилась стоимость — каждый бонус и штраф', 'website-value-estimator' ); ?></p>
                </div>
                <div class="wve-feature">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                        <line x1="8" y1="21" x2="16" y2="21"/>
                        <line x1="12" y1="17" x2="12" y2="21"/>
                    </svg>
                    <h4><?php esc_html_e( 'Бесплатные сервисы', 'website-value-estimator' ); ?></h4>
                    <p><?php esc_html_e( 'Google PageSpeed Insights, RDAP, SSL, HTML-анализ — без платных API', 'website-value-estimator' ); ?></p>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Блок истории последних оценок.
     *
     * @param int $limit Лимит.
     * @return void
     */
    private function render_history_block( $limit ) {
        $items = WVE_Estimator::instance()->get_recent_estimates( $limit );
        if ( empty( $items ) ) {
            return;
        }
        ?>
        <div class="wve-card wve-history-card">
            <h3 class="wve-history-title"><?php esc_html_e( 'Последние оценки', 'website-value-estimator' ); ?></h3>
            <ul class="wve-history-list">
                <?php foreach ( $items as $item ) : ?>
                    <li class="wve-history-item">
                        <a href="<?php echo esc_attr( $item->url ); ?>" target="_blank" rel="noopener" class="wve-history-url">
                            <?php echo esc_html( $item->domain ); ?>
                        </a>
                        <span class="wve-history-value">
                            <?php echo esc_html( number_format( (float) $item->estimated_value, 0, ',', ' ' ) . ' ' . $item->currency ); ?>
                        </span>
                        <time class="wve-history-date"><?php echo esc_html( date_i18n( 'd.m.Y H:i', strtotime( $item->created_at ) ) ); ?></time>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }
}
