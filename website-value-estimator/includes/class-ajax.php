<?php
/**
 * AJAX-обработчики.
 *
 * @package WebsiteValueEstimator
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * WVE_Ajax — обработка AJAX-запросов оценки.
 */
class WVE_Ajax {

    /**
     * Экземпляр.
     *
     * @var WVE_Ajax|null
     */
    private static $instance = null;

    /**
     * Получить экземпляр.
     *
     * @return WVE_Ajax
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
        add_action( 'wp_ajax_wve_estimate', array( $this, 'handle_estimate' ) );
        add_action( 'wp_ajax_nopriv_wve_estimate', array( $this, 'handle_estimate' ) );
    }

    /**
     * Обработка запроса оценки.
     *
     * @return void
     */
    public function handle_estimate() {
        check_ajax_referer( 'wve_nonce', 'nonce' );

        $url = isset( $_POST['url'] ) ? sanitize_text_field( wp_unslash( $_POST['url'] ) ) : '';
        if ( empty( $url ) ) {
            wp_send_json_error( array( 'message' => __( 'URL не указан', 'website-value-estimator' ) ) );
        }

        $api = WVE_API_Client::instance();

        // Валидация URL.
        $normalized = $api->normalize_url( $url );
        if ( ! $normalized ) {
            wp_send_json_error( array( 'message' => __( 'Введите корректный URL сайта', 'website-value-estimator' ) ) );
        }

        // Лимит запросов — не более 5 в минуту на IP.
        $ip = $this->get_client_ip();
        if ( ! $this->check_rate_limit( $ip ) ) {
            wp_send_json_error( array(
                'message' => __( 'Слишком много запросов. Попробуйте через минуту.', 'website-value-estimator' ),
            ) );
        }

        // Анализ сайта.
        $data = $api->analyze_site( $normalized );
        if ( isset( $data['error'] ) && 'invalid_url' === $data['error'] ) {
            wp_send_json_error( array( 'message' => __( 'Некорректный URL', 'website-value-estimator' ) ) );
        }

        // Расчёт стоимости.
        $result = WVE_Estimator::instance()->estimate( $data );

        // Сохраняем в историю.
        WVE_Estimator::instance()->save_to_history( $result, $ip );

        // Рендерим HTML результата.
        $html = $this->render_result( $result );

        wp_send_json_success( array(
            'html'      => $html,
            'value'     => $result['estimated_value'],
            'currency'  => $result['currency'],
            'category'  => $result['category']['label'],
            'stats'     => $result['stats'],
        ) );
    }

    /**
     * Рендер HTML результата.
     *
     * @param array $result Результат.
     * @return string
     */
    private function render_result( $result ) {
        $stats = $result['stats'];
        $data = $result['data'];
        $currency = $result['currency'];
        $value = $result['estimated_value'];
        $category = $result['category'];
        $breakdown = $result['breakdown'];

        $currency_symbols = array(
            'USD' => '$',
            'EUR' => '€',
            'RUB' => '₽',
            'UAH' => '₴',
            'KZT' => '₸',
        );
        $symbol = isset( $currency_symbols[ $currency ] ) ? $currency_symbols[ $currency ] : '';
        $formatted_value = number_format( $value, 0, ',', ' ' );

        ob_start();
        ?>
        <div class="wve-result-inner">
            <!-- Шапка результата -->
            <div class="wve-result-header" style="--accent-color: <?php echo esc_attr( $category['color'] ); ?>">
                <div class="wve-result-site">
                    <div class="wve-site-favicon">
                        <?php if ( ! empty( $stats['favicon'] ) ) : ?>
                            <img src="<?php echo esc_url( $stats['favicon'] ); ?>" alt="" onerror="this.style.display='none'"/>
                        <?php else : ?>
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="2" y1="12" x2="22" y2="12"/>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                            </svg>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h3 class="wve-site-title"><?php echo esc_html( $data['domain'] ); ?></h3>
                        <a href="<?php echo esc_url( $data['url'] ); ?>" target="_blank" rel="noopener" class="wve-site-url">
                            <?php echo esc_html( $data['url'] ); ?>
                        </a>
                    </div>
                </div>
                <div class="wve-result-value">
                    <span class="wve-value-label"><?php esc_html_e( 'Расчётная стоимость', 'website-value-estimator' ); ?></span>
                    <div class="wve-value-amount">
                        <?php if ( 'RUB' === $currency || 'UAH' === $currency || 'KZT' === $currency ) : ?>
                            <span class="wve-value-number"><?php echo esc_html( $formatted_value ); ?></span>
                            <span class="wve-value-symbol"><?php echo esc_html( $symbol ); ?></span>
                        <?php else : ?>
                            <span class="wve-value-symbol"><?php echo esc_html( $symbol ); ?></span>
                            <span class="wve-value-number"><?php echo esc_html( $formatted_value ); ?></span>
                        <?php endif; ?>
                    </div>
                    <span class="wve-value-category" style="background: <?php echo esc_attr( $category['color'] ); ?>20; color: <?php echo esc_attr( $category['color'] ); ?>">
                        <?php echo esc_html( $category['label'] ); ?>
                    </span>
                </div>
            </div>

            <?php if ( ! empty( $category['description'] ) ) : ?>
                <p class="wve-category-desc"><?php echo esc_html( $category['description'] ); ?></p>
            <?php endif; ?>

            <!-- Экспресс-метрики -->
            <div class="wve-quick-stats">
                <div class="wve-stat">
                    <div class="wve-stat-value"><?php echo esc_html( $stats['avg_score'] ); ?>/100</div>
                    <div class="wve-stat-label"><?php esc_html_e( 'Средний PageSpeed', 'website-value-estimator' ); ?></div>
                </div>
                <div class="wve-stat">
                    <div class="wve-stat-value"><?php echo esc_html( $stats['domain_age_years'] ); ?> <?php esc_html_e( 'лет', 'website-value-estimator' ); ?></div>
                    <div class="wve-stat-label"><?php esc_html_e( 'Возраст домена', 'website-value-estimator' ); ?></div>
                </div>
                <div class="wve-stat">
                    <div class="wve-stat-value"><?php echo esc_html( $stats['html_size_kb'] ); ?> KB</div>
                    <div class="wve-stat-label"><?php esc_html_e( 'Размер страницы', 'website-value-estimator' ); ?></div>
                </div>
                <div class="wve-stat">
                    <div class="wve-stat-value"><?php echo esc_html( $stats['response_time_ms'] ); ?> <?php esc_html_e( 'мс', 'website-value-estimator' ); ?></div>
                    <div class="wve-stat-label"><?php esc_html_e( 'Ответ сервера', 'website-value-estimator' ); ?></div>
                </div>
            </div>

            <!-- Категории PageSpeed -->
            <?php if ( ! empty( $stats['categories'] ) ) : ?>
                <div class="wve-section">
                    <h4 class="wve-section-title"><?php esc_html_e( 'Показатели Google PageSpeed Insights', 'website-value-estimator' ); ?></h4>
                    <div class="wve-scores-grid">
                        <?php foreach ( $stats['categories'] as $key => $cat ) : ?>
                            <div class="wve-score-card <?php echo esc_attr( $this->score_class( $cat['score'] ) ); ?>">
                                <div class="wve-score-circle" style="--p: <?php echo esc_attr( $cat['score'] ); ?>">
                                    <svg viewBox="0 0 36 36">
                                        <path class="wve-score-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                        <path class="wve-score-fill" stroke-dasharray="<?php echo esc_attr( $cat['score'] ); ?>, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    </svg>
                                    <span class="wve-score-num"><?php echo esc_html( $cat['score'] ); ?></span>
                                </div>
                                <span class="wve-score-label"><?php echo esc_html( $cat['title'] ); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php elseif ( ! empty( $stats['psi_error'] ) ) : ?>
                <div class="wve-section">
                    <div class="wve-notice wve-notice-warning">
                        <?php esc_html_e( 'PageSpeed Insights временно недоступен. Оценка рассчитана по доступным данным.', 'website-value-estimator' ); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Web Vitals -->
            <?php if ( $stats['lcp_ms'] || $stats['fcp_ms'] || $stats['cls'] || $stats['tbt_ms'] ) : ?>
                <div class="wve-section">
                    <h4 class="wve-section-title"><?php esc_html_e( 'Web Vitals (основные метрики)', 'website-value-estimator' ); ?></h4>
                    <div class="wve-vitals-grid">
                        <?php if ( $stats['lcp_ms'] ) : ?>
                            <div class="wve-vital <?php echo esc_attr( $this->vital_class( $stats['lcp_ms'], array( 2500, 4000 ) ) ); ?>">
                                <span class="wve-vital-name">LCP</span>
                                <span class="wve-vital-value"><?php echo esc_html( round( $stats['lcp_ms'] / 1000, 2 ) ); ?>s</span>
                                <span class="wve-vital-desc"><?php esc_html_e( 'Отрисовка', 'website-value-estimator' ); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ( $stats['fcp_ms'] ) : ?>
                            <div class="wve-vital <?php echo esc_attr( $this->vital_class( $stats['fcp_ms'], array( 1800, 3000 ) ) ); ?>">
                                <span class="wve-vital-name">FCP</span>
                                <span class="wve-vital-value"><?php echo esc_html( round( $stats['fcp_ms'] / 1000, 2 ) ); ?>s</span>
                                <span class="wve-vital-desc"><?php esc_html_e( 'Первый контент', 'website-value-estimator' ); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ( $stats['cls'] ) : ?>
                            <div class="wve-vital <?php echo esc_attr( $this->vital_class( $stats['cls'] * 100, array( 10, 25 ) ) ); ?>">
                                <span class="wve-vital-name">CLS</span>
                                <span class="wve-vital-value"><?php echo esc_html( round( $stats['cls'], 3 ) ); ?></span>
                                <span class="wve-vital-desc"><?php esc_html_e( 'Смещение', 'website-value-estimator' ); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ( $stats['tbt_ms'] ) : ?>
                            <div class="wve-vital <?php echo esc_attr( $this->vital_class( $stats['tbt_ms'], array( 200, 600 ) ) ); ?>">
                                <span class="wve-vital-name">TBT</span>
                                <span class="wve-vital-value"><?php echo esc_html( round( $stats['tbt_ms'] ) ); ?>ms</span>
                                <span class="wve-vital-desc"><?php esc_html_e( 'Блокировка', 'website-value-estimator' ); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Технические характеристики -->
            <div class="wve-section">
                <h4 class="wve-section-title"><?php esc_html_e( 'Технические характеристики', 'website-value-estimator' ); ?></h4>
                <div class="wve-tech-grid">
                    <div class="wve-tech-item">
                        <span class="wve-tech-label"><?php esc_html_e( 'SSL-сертификат', 'website-value-estimator' ); ?></span>
                        <span class="wve-tech-value <?php echo $stats['has_ssl'] ? 'wve-yes' : 'wve-no'; ?>">
                            <?php echo $stats['has_ssl'] ? esc_html__( 'Да', 'website-value-estimator' ) : esc_html__( 'Нет', 'website-value-estimator' ); ?>
                            <?php if ( $stats['has_ssl'] && ! empty( $stats['ssl_issuer'] ) ) : ?>
                                <small>(<?php echo esc_html( $stats['ssl_issuer'] ); ?>)</small>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="wve-tech-item">
                        <span class="wve-tech-label"><?php esc_html_e( 'Возраст домена', 'website-value-estimator' ); ?></span>
                        <span class="wve-tech-value"><?php echo esc_html( $stats['domain_age_years'] ); ?> <?php esc_html_e( 'лет', 'website-value-estimator' ); ?></span>
                    </div>
                    <?php if ( ! empty( $stats['domain_reg_date'] ) ) : ?>
                        <div class="wve-tech-item">
                            <span class="wve-tech-label"><?php esc_html_e( 'Регистрация', 'website-value-estimator' ); ?></span>
                            <span class="wve-tech-value"><?php echo esc_html( date_i18n( 'd.m.Y', strtotime( $stats['domain_reg_date'] ) ) ); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ( ! empty( $stats['registrar'] ) ) : ?>
                        <div class="wve-tech-item">
                            <span class="wve-tech-label"><?php esc_html_e( 'Регистратор', 'website-value-estimator' ); ?></span>
                            <span class="wve-tech-value"><?php echo esc_html( $stats['registrar'] ); ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="wve-tech-item">
                        <span class="wve-tech-label"><?php esc_html_e( 'Язык страницы', 'website-value-estimator' ); ?></span>
                        <span class="wve-tech-value"><?php echo esc_html( strtoupper( $stats['language'] ) ); ?></span>
                    </div>
                    <div class="wve-tech-item">
                        <span class="wve-tech-label"><?php esc_html_e( 'Изображений', 'website-value-estimator' ); ?></span>
                        <span class="wve-tech-value"><?php echo esc_html( $stats['images_count'] ); ?></span>
                    </div>
                    <div class="wve-tech-item">
                        <span class="wve-tech-label"><?php esc_html_e( 'Скриптов', 'website-value-estimator' ); ?></span>
                        <span class="wve-tech-value"><?php echo esc_html( $stats['scripts_count'] ); ?></span>
                    </div>
                    <div class="wve-tech-item">
                        <span class="wve-tech-label"><?php esc_html_e( 'CSS-файлов', 'website-value-estimator' ); ?></span>
                        <span class="wve-tech-value"><?php echo esc_html( $stats['styles_count'] ); ?></span>
                    </div>
                    <div class="wve-tech-item">
                        <span class="wve-tech-label"><?php esc_html_e( 'Внутренних ссылок', 'website-value-estimator' ); ?></span>
                        <span class="wve-tech-value"><?php echo esc_html( $stats['internal_links'] ); ?></span>
                    </div>
                    <div class="wve-tech-item">
                        <span class="wve-tech-label"><?php esc_html_e( 'Внешних ссылок', 'website-value-estimator' ); ?></span>
                        <span class="wve-tech-value"><?php echo esc_html( $stats['external_links'] ); ?></span>
                    </div>
                </div>
            </div>

            <!-- SEO и маркетинг -->
            <div class="wve-section">
                <h4 class="wve-section-title"><?php esc_html_e( 'SEO и маркетинг', 'website-value-estimator' ); ?></h4>
                <div class="wve-checks-grid">
                    <?php
                    $checks = array(
                        'Meta description'   => $stats['has_meta_desc'],
                        'Open Graph'         => $stats['has_og'],
                        'Twitter Card'       => $stats['has_twitter_card'],
                        'Schema.org'         => $stats['has_schema'],
                        'Google Analytics'   => $stats['has_ga'],
                        'Яндекс.Метрика'     => $stats['has_ym'],
                        'Google AdSense'     => $stats['has_ads'],
                        'Facebook Pixel'     => $stats['has_fb_pixel'],
                        'VK Pixel'           => $stats['has_vk_pixel'],
                    );
                    foreach ( $checks as $label => $on ) :
                    ?>
                        <div class="wve-check <?php echo $on ? 'wve-check-on' : 'wve-check-off'; ?>">
                            <span class="wve-check-icon">
                                <?php if ( $on ) : ?>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                <?php else : ?>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                <?php endif; ?>
                            </span>
                            <span class="wve-check-label"><?php echo esc_html( $label ); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ( ! empty( $stats['social_links'] ) ) : ?>
                    <div class="wve-social-block">
                        <span class="wve-social-title"><?php esc_html_e( 'Социальные сети:', 'website-value-estimator' ); ?></span>
                        <?php foreach ( array_keys( $stats['social_links'] ) as $soc ) : ?>
                            <span class="wve-social-tag"><?php echo esc_html( ucfirst( $soc ) ); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ( WVE_Settings::get( 'show_detailed', 1 ) ) : ?>
                <!-- Детальный расчёт стоимости -->
                <div class="wve-section">
                    <h4 class="wve-section-title"><?php esc_html_e( 'Как рассчитана стоимость', 'website-value-estimator' ); ?></h4>
                    <table class="wve-breakdown">
                        <thead>
                            <tr>
                                <th><?php esc_html_e( 'Показатель', 'website-value-estimator' ); ?></th>
                                <th class="wve-ta-right"><?php esc_html_e( 'Сумма', 'website-value-estimator' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $breakdown as $item ) : ?>
                                <tr class="wve-breakdown-<?php echo esc_attr( $item['type'] ); ?>">
                                    <td>
                                        <?php echo esc_html( $item['label'] ); ?>
                                        <?php if ( 'subtotal' === $item['type'] ) : ?>
                                            <span class="wve-breakdown-badge"><?php esc_html_e( 'промежуточный итог', 'website-value-estimator' ); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="wve-ta-right <?php echo $item['amount'] < 0 ? 'wve-amount-minus' : 'wve-amount-plus'; ?>">
                                        <?php echo esc_html( ( $item['amount'] >= 0 ? '+' : '' ) . number_format( $item['amount'], 0, ',', ' ' ) . ' ' . $symbol ); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td><strong><?php esc_html_e( 'ИТОГО', 'website-value-estimator' ); ?></strong></td>
                                <td class="wve-ta-right"><strong><?php echo esc_html( $formatted_value . ' ' . $symbol ); ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endif; ?>

            <!-- Disclaimer -->
            <div class="wve-disclaimer">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <p><?php esc_html_e( 'Оценка является приблизительной и рассчитана на основе публичных метрик. Реальная рыночная стоимость может отличаться в зависимости от доходов сайта, трафика, бренда и других факторов.', 'website-value-estimator' ); ?></p>
            </div>

            <div class="wve-result-actions">
                <button type="button" class="wve-btn wve-btn-secondary" id="wve-new-estimate">
                    <?php esc_html_e( 'Оценить другой сайт', 'website-value-estimator' ); ?>
                </button>
                <button type="button" class="wve-btn wve-btn-outline" id="wve-share-result">
                    <?php esc_html_e( 'Поделиться', 'website-value-estimator' ); ?>
                </button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Класс оценки по баллам.
     *
     * @param int $score Балл (0-100).
     * @return string
     */
    private function score_class( $score ) {
        if ( $score >= 90 ) return 'wve-score-good';
        if ( $score >= 50 ) return 'wve-score-average';
        return 'wve-score-poor';
    }

    /**
     * Класс Web Vital.
     *
     * @param float $value Значение.
     * @param array $thresholds [хорошо, средне].
     * @return string
     */
    private function vital_class( $value, $thresholds ) {
        if ( $value <= $thresholds[0] ) return 'wve-vital-good';
        if ( $value <= $thresholds[1] ) return 'wve-vital-average';
        return 'wve-vital-poor';
    }

    /**
     * IP-адрес клиента.
     *
     * @return string
     */
    private function get_client_ip() {
        $ips = array( 'HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR' );
        foreach ( $ips as $key ) {
            if ( ! empty( $_SERVER[ $key ] ) ) {
                $ip = sanitize_text_field( wp_unslash( $_SERVER[ $key ] ) );
                return trim( explode( ',', $ip )[0] );
            }
        }
        return '0.0.0.0';
    }

    /**
     * Rate limit — 5 запросов в минуту.
     *
     * @param string $ip IP-адрес.
     * @return bool
     */
    private function check_rate_limit( $ip ) {
        $key = 'wve_rl_' . md5( $ip );
        $count = intval( get_transient( $key ) );
        if ( $count >= 5 ) {
            return false;
        }
        set_transient( $key, $count + 1, 60 );
        return true;
    }
}
