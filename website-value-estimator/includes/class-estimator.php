<?php
/**
 * Класс-оценщик стоимости сайта.
 *
 * Алгоритм:
 *   - Базовая стоимость из настроек
 *   - Бонусы за PageSpeed scores (performance, accessibility, best-practices, seo)
 *   - Бонус за возраст домена
 *   - Бонус за SSL-сертификат
 *   - Бонус за наличие SEO-метатегов (description, og, schema, twitter card)
 *   - Бонус за аналитику и рекламу (сигналы монетизации)
 *   - Бонус за соцсети
 *   - Штраф за медленную загрузку
 *   - Штраф за большой размер HTML
 *   - Штраф за большое количество запросов
 *
 * @package WebsiteValueEstimator
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * WVE_Estimator — расчёт стоимости.
 */
class WVE_Estimator {

    /**
     * Экземпляр.
     *
     * @var WVE_Estimator|null
     */
    private static $instance = null;

    /**
     * Получить экземпляр.
     *
     * @return WVE_Estimator
     */
    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Основной метод оценки.
     *
     * @param array $data Данные анализа (от WVE_API_Client::analyze_site).
     * @return array
     */
    public function estimate( $data ) {
        $base = intval( WVE_Settings::get( 'base_value', 1000 ) );
        $breakdown = array();
        $value = $base;

        $breakdown[] = array(
            'label'  => 'Базовая стоимость',
            'amount' => $base,
            'type'   => 'base',
        );

        // === 1. PageSpeed scores ===
        $ps = isset( $data['pagespeed'] ) ? $data['pagespeed'] : array();
        if ( ! empty( $ps['categories'] ) ) {
            $score_bonus_total = 0;
            foreach ( $ps['categories'] as $key => $cat ) {
                $score = intval( $cat['score'] );
                // За каждый балл (0-100) — множитель к базе.
                $weight = $this->get_category_weight( $key );
                $bonus = round( $base * ( $score / 100 ) * $weight );
                $value += $bonus;
                $score_bonus_total += $bonus;
                $breakdown[] = array(
                    'label'  => sprintf( '%s (оценка %d/100)', $cat['title'], $score ),
                    'amount' => $bonus,
                    'type'   => 'bonus',
                    'score'  => $score,
                );
            }
            $breakdown[] = array(
                'label'  => 'Итого за технические показатели (PageSpeed)',
                'amount' => $score_bonus_total,
                'type'   => 'subtotal',
            );
        }

        // === 2. Возраст домена ===
        $domain_age = isset( $data['domain_info']['domain_age_years'] ) ? floatval( $data['domain_info']['domain_age_years'] ) : 0;
        if ( $domain_age > 0 ) {
            // Каждый год возраста добавляет 5% к базе, максимум 200%.
            $age_bonus = round( $base * min( 2.0, $domain_age * 0.05 ) );
            $value += $age_bonus;
            $breakdown[] = array(
                'label'  => sprintf( 'Возраст домена: %.1f лет', $domain_age ),
                'amount' => $age_bonus,
                'type'   => 'bonus',
            );
        }

        // === 3. SSL-сертификат ===
        if ( ! empty( $data['ssl_info']['has_ssl'] ) ) {
            $ssl_bonus = round( $base * 0.10 );
            $value += $ssl_bonus;
            $breakdown[] = array(
                'label'  => 'Наличие SSL-сертификата',
                'amount' => $ssl_bonus,
                'type'   => 'bonus',
            );

            // Дополнительный бонус за долгосрочный сертификат.
            if ( ! empty( $data['ssl_info']['days_left'] ) && $data['ssl_info']['days_left'] > 180 ) {
                $long_ssl_bonus = round( $base * 0.02 );
                $value += $long_ssl_bonus;
                $breakdown[] = array(
                    'label'  => sprintf( 'SSL действует ещё %d дней', $data['ssl_info']['days_left'] ),
                    'amount' => $long_ssl_bonus,
                    'type'   => 'bonus',
                );
            }
        } else {
            $ssl_penalty = round( $base * 0.15 );
            $value -= $ssl_penalty;
            $breakdown[] = array(
                'label'  => 'Штраф: нет SSL-сертификата',
                'amount' => -$ssl_penalty,
                'type'   => 'penalty',
            );
        }

        // === 4. SEO-метатеги ===
        $html = isset( $data['html_info'] ) ? $data['html_info'] : array();
        $seo_bonus = 0;
        if ( ! empty( $html['description'] ) ) {
            $b = round( $base * 0.03 );
            $seo_bonus += $b;
            $breakdown[] = array(
                'label'  => 'Meta description',
                'amount' => $b,
                'type'   => 'bonus',
            );
        }
        if ( ! empty( $html['og_tags'] ) ) {
            $b = round( $base * 0.04 );
            $seo_bonus += $b;
            $breakdown[] = array(
                'label'  => sprintf( 'Open Graph теги (%d)', count( $html['og_tags'] ) ),
                'amount' => $b,
                'type'   => 'bonus',
            );
        }
        if ( ! empty( $html['twitter_card'] ) ) {
            $b = round( $base * 0.02 );
            $seo_bonus += $b;
            $breakdown[] = array(
                'label'  => 'Twitter Card',
                'amount' => $b,
                'type'   => 'bonus',
            );
        }
        if ( ! empty( $html['schema_org'] ) ) {
            $b = round( $base * 0.05 );
            $seo_bonus += $b;
            $breakdown[] = array(
                'label'  => 'Schema.org разметка',
                'amount' => $b,
                'type'   => 'bonus',
            );
        }
        $value += $seo_bonus;

        // === 5. Аналитика и монетизация ===
        $monetization_bonus = 0;
        if ( ! empty( $html['has_google_analytics'] ) ) {
            $b = round( $base * 0.03 );
            $monetization_bonus += $b;
            $breakdown[] = array( 'label' => 'Google Analytics установлен', 'amount' => $b, 'type' => 'bonus' );
        }
        if ( ! empty( $html['has_ym'] ) ) {
            $b = round( $base * 0.03 );
            $monetization_bonus += $b;
            $breakdown[] = array( 'label' => 'Яндекс.Метрика установлена', 'amount' => $b, 'type' => 'bonus' );
        }
        if ( ! empty( $html['has_google_ads'] ) ) {
            $b = round( $base * 0.15 );
            $monetization_bonus += $b;
            $breakdown[] = array( 'label' => 'Google AdSense (монетизация)', 'amount' => $b, 'type' => 'bonus' );
        }
        if ( ! empty( $html['has_facebook_pixel'] ) ) {
            $b = round( $base * 0.05 );
            $monetization_bonus += $b;
            $breakdown[] = array( 'label' => 'Facebook Pixel (ретаргетинг)', 'amount' => $b, 'type' => 'bonus' );
        }
        if ( ! empty( $html['has_vk_pixel'] ) ) {
            $b = round( $base * 0.03 );
            $monetization_bonus += $b;
            $breakdown[] = array( 'label' => 'VK Pixel (ретаргетинг)', 'amount' => $b, 'type' => 'bonus' );
        }
        $value += $monetization_bonus;

        // === 6. Социальные сети ===
        $social_count = isset( $html['social_links_count'] ) ? intval( $html['social_links_count'] ) : 0;
        if ( $social_count > 0 ) {
            $social_bonus = round( $base * 0.02 * min( 5, $social_count ) );
            $value += $social_bonus;
            $breakdown[] = array(
                'label'  => sprintf( 'Социальные сети (%d шт.)', $social_count ),
                'amount' => $social_bonus,
                'type'   => 'bonus',
            );
        }

        // === 7. Штрафы за производительность ===
        $audits = isset( $ps['audits'] ) ? $ps['audits'] : array();

        // LCP — Largest Contentful Paint.
        if ( isset( $audits['largest-contentful-paint']['value'] ) ) {
            $lcp = $audits['largest-contentful-paint']['value'];
            if ( $lcp > 4000 ) {
                $penalty = round( $base * 0.10 * min( 1, ( $lcp - 4000 ) / 6000 ) );
                $value -= $penalty;
                $breakdown[] = array(
                    'label'  => sprintf( 'Штраф: медленный LCP (%.1fs)', $lcp / 1000 ),
                    'amount' => -$penalty,
                    'type'   => 'penalty',
                );
            }
        }

        // Размер HTML.
        if ( isset( $html['html_size'] ) && $html['html_size'] > 500000 ) {
            $penalty = round( $base * 0.05 * min( 1, ( $html['html_size'] - 500000 ) / 2000000 ) );
            $value -= $penalty;
            $breakdown[] = array(
                'label'  => sprintf( 'Штраф: тяжёлая страница (%s KB)', number_format( $html['html_size'] / 1024, 0 ) ),
                'amount' => -$penalty,
                'type'   => 'penalty',
            );
        }

        // Время ответа сервера.
        if ( isset( $html['response_time'] ) && $html['response_time'] > 2000 ) {
            $penalty = round( $base * 0.05 );
            $value -= $penalty;
            $breakdown[] = array(
                'label'  => sprintf( 'Штраф: долгий ответ сервера (%.2fs)', $html['response_time'] / 1000 ),
                'amount' => -$penalty,
                'type'   => 'penalty',
            );
        }

        // CLS — Cumulative Layout Shift.
        if ( isset( $audits['cumulative-layout-shift']['value'] ) ) {
            $cls = $audits['cumulative-layout-shift']['value'];
            if ( $cls > 0.25 ) {
                $penalty = round( $base * 0.05 * min( 1, $cls ) );
                $value -= $penalty;
                $breakdown[] = array(
                    'label'  => sprintf( 'Штраф: сильное смещение макета (CLS=%.2f)', $cls ),
                    'amount' => -$penalty,
                    'type'   => 'penalty',
                );
            }
        }

        // === 8. Бонус за контент ===
        $images = isset( $html['images_count'] ) ? intval( $html['images_count'] ) : 0;
        if ( $images > 0 && $images < 100 ) {
            $content_bonus = round( $base * 0.02 * min( 1, $images / 20 ) );
            $value += $content_bonus;
            $breakdown[] = array(
                'label'  => sprintf( 'Контент: %d изображений', $images ),
                'amount' => $content_bonus,
                'type'   => 'bonus',
            );
        }

        $internal = isset( $html['internal_links'] ) ? intval( $html['internal_links'] ) : 0;
        if ( $internal > 5 && $internal < 200 ) {
            $links_bonus = round( $base * 0.02 );
            $value += $links_bonus;
            $breakdown[] = array(
                'label'  => sprintf( 'Внутренняя перелинковка: %d ссылок', $internal ),
                'amount' => $links_bonus,
                'type'   => 'bonus',
            );
        }

        // Округляем.
        $value = max( 100, $value ); // Минимум $100.
        $value = round( $value / 50 ) * 50; // Округление до 50.

        // Формируем итоговый отчёт.
        $currency = WVE_Settings::get( 'currency', 'USD' );

        // Категория стоимости.
        $category = $this->get_value_category( $value );

        // Общая статистика.
        $stats = $this->build_stats( $data );

        return array(
            'estimated_value'  => $value,
            'currency'         => $currency,
            'category'         => $category,
            'breakdown'        => $breakdown,
            'stats'            => $stats,
            'data'             => $data,
            'analyzed_at'      => isset( $data['analyzed_at'] ) ? $data['analyzed_at'] : current_time( 'mysql' ),
            'formula_version'  => WVE_VERSION,
        );
    }

    /**
     * Вес каждой категории PageSpeed.
     *
     * @param string $category Категория.
     * @return float
     */
    private function get_category_weight( $category ) {
        $weights = array(
            'performance'    => 1.5,
            'accessibility'  => 0.8,
            'best-practices' => 0.6,
            'seo'            => 1.0,
            'pwa'            => 0.4,
        );
        return isset( $weights[ $category ] ) ? $weights[ $category ] : 0.5;
    }

    /**
     * Категория стоимости.
     *
     * @param float $value Стоимость.
     * @return array
     */
    private function get_value_category( $value ) {
        if ( $value < 2000 ) {
            return array(
                'label' => 'Низкая стоимость',
                'color' => '#ef4444',
                'description' => 'Сайт требует значительных улучшений.',
            );
        } elseif ( $value < 10000 ) {
            return array(
                'label' => 'Средняя стоимость',
                'color' => '#f59e0b',
                'description' => 'Хороший сайт с потенциалом для роста.',
            );
        } elseif ( $value < 50000 ) {
            return array(
                'label' => 'Высокая стоимость',
                'color' => '#10b981',
                'description' => 'Качественный сайт с отличными показателями.',
            );
        } elseif ( $value < 200000 ) {
            return array(
                'label' => 'Очень высокая стоимость',
                'color' => '#3b82f6',
                'description' => 'Превосходный сайт с высоким коммерческим потенциалом.',
            );
        } else {
            return array(
                'label' => 'Премиум-сегмент',
                'color' => '#8b5cf6',
                'description' => 'Топовый сайт с выдающимися показателями.',
            );
        }
    }

    /**
     * Сводная статистика для отображения.
     *
     * @param array $data Данные.
     * @return array
     */
    private function build_stats( $data ) {
        $ps = isset( $data['pagespeed'] ) ? $data['pagespeed'] : array();
        $html = isset( $data['html_info'] ) ? $data['html_info'] : array();
        $ssl = isset( $data['ssl_info'] ) ? $data['ssl_info'] : array();
        $domain = isset( $data['domain_info'] ) ? $data['domain_info'] : array();

        $categories = isset( $ps['categories'] ) ? $ps['categories'] : array();
        $audits = isset( $ps['audits'] ) ? $ps['audits'] : array();

        // Средний PageSpeed score.
        $avg_score = 0;
        if ( ! empty( $categories ) ) {
            $sum = 0;
            foreach ( $categories as $c ) {
                $sum += $c['score'];
            }
            $avg_score = round( $sum / count( $categories ) );
        }

        return array(
            'avg_score'         => $avg_score,
            'categories'        => $categories,
            'audits'            => $audits,
            'domain_age_years'  => isset( $domain['domain_age_years'] ) ? round( $domain['domain_age_years'], 1 ) : 0,
            'domain_reg_date'   => isset( $domain['registration_date'] ) ? $domain['registration_date'] : '',
            'registrar'         => isset( $domain['registrar'] ) ? $domain['registrar'] : '',
            'has_ssl'           => ! empty( $ssl['has_ssl'] ),
            'ssl_issuer'        => isset( $ssl['issuer'] ) ? $ssl['issuer'] : '',
            'ssl_days_left'     => isset( $ssl['days_left'] ) ? $ssl['days_left'] : 0,
            'html_size_kb'      => isset( $html['html_size'] ) ? round( $html['html_size'] / 1024 ) : 0,
            'response_time_ms'  => isset( $html['response_time'] ) ? $html['response_time'] : 0,
            'images_count'      => isset( $html['images_count'] ) ? $html['images_count'] : 0,
            'scripts_count'     => isset( $html['scripts_count'] ) ? $html['scripts_count'] : 0,
            'styles_count'      => isset( $html['styles_count'] ) ? $html['styles_count'] : 0,
            'internal_links'    => isset( $html['internal_links'] ) ? $html['internal_links'] : 0,
            'external_links'    => isset( $html['external_links'] ) ? $html['external_links'] : 0,
            'social_count'      => isset( $html['social_links_count'] ) ? $html['social_links_count'] : 0,
            'social_links'      => isset( $html['social_links'] ) ? $html['social_links'] : array(),
            'has_ga'            => ! empty( $html['has_google_analytics'] ),
            'has_ads'           => ! empty( $html['has_google_ads'] ),
            'has_ym'            => ! empty( $html['has_ym'] ),
            'has_fb_pixel'      => ! empty( $html['has_facebook_pixel'] ),
            'has_vk_pixel'      => ! empty( $html['has_vk_pixel'] ),
            'has_schema'        => ! empty( $html['schema_org'] ),
            'has_og'            => ! empty( $html['og_tags'] ),
            'has_twitter_card'  => ! empty( $html['twitter_card'] ),
            'has_meta_desc'     => ! empty( $html['description'] ),
            'title'             => isset( $html['title'] ) ? $html['title'] : '',
            'description'       => isset( $html['description'] ) ? $html['description'] : '',
            'language'          => isset( $html['language'] ) ? $html['language'] : '',
            'favicon'           => isset( $html['favicon'] ) ? $html['favicon'] : '',
            'lcp_ms'            => isset( $audits['largest-contentful-paint']['value'] ) ? $audits['largest-contentful-paint']['value'] : 0,
            'fcp_ms'            => isset( $audits['first-contentful-paint']['value'] ) ? $audits['first-contentful-paint']['value'] : 0,
            'cls'               => isset( $audits['cumulative-layout-shift']['value'] ) ? $audits['cumulative-layout-shift']['value'] : 0,
            'tbt_ms'            => isset( $audits['total-blocking-time']['value'] ) ? $audits['total-blocking-time']['value'] : 0,
            'speed_index_ms'    => isset( $audits['speed-index']['value'] ) ? $audits['speed-index']['value'] : 0,
            'total_byte_weight' => isset( $audits['total-byte-weight']['value'] ) ? $audits['total-byte-weight']['value'] : 0,
            'psi_error'         => isset( $ps['error'] ) ? $ps['error'] : null,
        );
    }

    /**
     * Сохранить оценку в историю.
     *
     * @param array $result Результат оценки.
     * @param string $ip IP-адрес.
     * @return int ID записи.
     */
    public function save_to_history( $result, $ip = '' ) {
        if ( ! WVE_Settings::get( 'enable_history', 1 ) ) {
            return 0;
        }
        global $wpdb;
        $table = $wpdb->prefix . 'wve_estimates';
        $wpdb->insert(
            $table,
            array(
                'url'             => $result['data']['url'],
                'domain'          => $result['data']['domain'],
                'estimated_value' => $result['estimated_value'],
                'currency'        => $result['currency'],
                'metrics'         => wp_json_encode( $result['stats'] ),
                'created_at'      => current_time( 'mysql' ),
                'ip_address'      => $ip,
            ),
            array( '%s', '%s', '%d', '%s', '%s', '%s', '%s' )
        );
        return $wpdb->insert_id;
    }

    /**
     * Получить последние оценки.
     *
     * @param int $limit Лимит.
     * @return array
     */
    public function get_recent_estimates( $limit = 10 ) {
        global $wpdb;
        $table = $wpdb->prefix . 'wve_estimates';
        return $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM {$table} ORDER BY created_at DESC LIMIT %d",
            $limit
        ) );
    }
}
