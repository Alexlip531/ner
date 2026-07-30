<?php
/**
 * API-клиент для получения данных о сайте.
 *
 * @package WebsiteValueEstimator
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * WVE_API_Client — получает данные с различных сервисов.
 */
class WVE_API_Client {

    /**
     * Экземпляр.
     *
     * @var WVE_API_Client|null
     */
    private static $instance = null;

    /**
     * Получить экземпляр.
     *
     * @return WVE_API_Client
     */
    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Нормализовать URL.
     *
     * @param string $url URL.
     * @return string|false
     */
    public function normalize_url( $url ) {
        $url = trim( $url );
        if ( empty( $url ) ) {
            return false;
        }
        // Если нет схемы — добавляем https://
        if ( ! preg_match( '#^https?://#i', $url ) ) {
            $url = 'https://' . $url;
        }
        $parsed = wp_parse_url( $url );
        if ( empty( $parsed['host'] ) ) {
            return false;
        }
        // Проверяем, что host валидный.
        if ( ! preg_match( '/^([a-z0-9\-]+\.)+[a-z]{2,}$/i', $parsed['host'] ) ) {
            return false;
        }
        return $parsed['scheme'] . '://' . $parsed['host'] . ( isset( $parsed['path'] ) ? $parsed['path'] : '' );
    }

    /**
     * Извлечь домен из URL.
     *
     * @param string $url URL.
     * @return string
     */
    public function get_domain( $url ) {
        $parsed = wp_parse_url( $url );
        return isset( $parsed['host'] ) ? $parsed['host'] : '';
    }

    /**
     * Получить WHOIS / RDAP данные о домене.
     *
     * @param string $domain Домен.
     * @return array
     */
    public function get_domain_info( $domain ) {
        $cache_key = 'wve_domain_' . md5( $domain );
        $cached = get_transient( $cache_key );
        if ( false !== $cached ) {
            return $cached;
        }

        $info = array(
            'registration_date' => null,
            'domain_age_years'  => 0,
            'registrar'         => '',
            'status'            => '',
            'raw'               => null,
        );

        // Используем RDAP (современная замена WHOIS).
        $rdap_urls = array(
            'https://rdap.org/domain/' . $domain,
            'https://www.rdap.net/domain/' . $domain,
        );

        foreach ( $rdap_urls as $rdap_url ) {
            $response = wp_remote_get( $rdap_url, array(
                'timeout' => 15,
                'headers' => array( 'Accept' => 'application/rdap+json' ),
            ) );

            if ( is_wp_error( $response ) ) {
                continue;
            }
            $code = wp_remote_retrieve_response_code( $response );
            if ( 200 !== $code ) {
                continue;
            }
            $body = wp_remote_retrieve_body( $response );
            $data = json_decode( $body, true );
            if ( ! is_array( $data ) ) {
                continue;
            }

            // Регистратор.
            if ( isset( $data['entities'] ) && is_array( $data['entities'] ) ) {
                foreach ( $data['entities'] as $entity ) {
                    if ( isset( $entity['roles'] ) && in_array( 'registrar', $entity['roles'], true ) ) {
                        if ( isset( $entity['vcardArray'][1] ) ) {
                            foreach ( $entity['vcardArray'][1] as $vcard ) {
                                if ( 'fn' === $vcard[0] ) {
                                    $info['registrar'] = $vcard[3];
                                }
                            }
                        }
                    }
                }
            }

            // Дата регистрации и окончания.
            if ( isset( $data['events'] ) && is_array( $data['events'] ) ) {
                foreach ( $data['events'] as $event ) {
                    if ( 'registration' === $event['eventAction'] ) {
                        $info['registration_date'] = $event['eventDate'];
                    }
                }
            }

            // Статус.
            if ( isset( $data['status'] ) && is_array( $data['status'] ) ) {
                $info['status'] = implode( ', ', $data['status'] );
            }

            // Считаем возраст домена.
            if ( ! empty( $info['registration_date'] ) ) {
                try {
                    $reg_date = new DateTime( $info['registration_date'] );
                    $now = new DateTime();
                    $diff = $reg_date->diff( $now );
                    $info['domain_age_years'] = $diff->y + ( $diff->m / 12 );
                } catch ( Exception $e ) {
                    $info['domain_age_years'] = 0;
                }
            }

            $info['raw'] = $data;
            break;
        }

        // Кэшируем на 24 часа.
        set_transient( $cache_key, $info, DAY_IN_SECONDS );
        return $info;
    }

    /**
     * Получить данные Google PageSpeed Insights.
     *
     * @param string $url URL сайта.
     * @return array
     */
    public function get_pagespeed_data( $url ) {
        $api_key = WVE_Settings::get( 'api_key', '' );
        $cache_key = 'wve_psi_' . md5( $url . $api_key );
        $cached = get_transient( $cache_key );
        if ( false !== $cached ) {
            return $cached;
        }

        $result = array(
            'categories'   => array(),
            'audits'       => array(),
            'final_url'    => '',
            'lighthouse_version' => '',
            'error'        => null,
        );

        // Запрашиваем для mobile и desktop.
        $strategies = array( 'mobile', 'desktop' );
        $combined = array(
            'mobile'  => null,
            'desktop' => null,
        );

        foreach ( $strategies as $strategy ) {
            // Категории — несколько параметров category=... в одной строке.
            $api_url = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=' . rawurlencode( $url ) . '&strategy=' . $strategy . '&category=performance&category=accessibility&category=best-practices&category=seo';
            if ( ! empty( $api_key ) ) {
                $api_url .= '&key=' . $api_key;
            }

            $response = wp_remote_get( $api_url, array(
                'timeout' => WVE_Settings::get( 'request_timeout', 30 ),
            ) );

            if ( is_wp_error( $response ) ) {
                $result['error'] = $response->get_error_message();
                continue;
            }
            $code = wp_remote_retrieve_response_code( $response );
            $body = wp_remote_retrieve_body( $response );
            $data = json_decode( $body, true );

            if ( 429 === $code ) {
                $result['error'] = 'rate_limit';
                break;
            }
            if ( 200 !== $code || ! is_array( $data ) ) {
                $result['error'] = sprintf( 'HTTP %d: %s', $code, wp_strip_all_tags( $body ) );
                continue;
            }

            $combined[ $strategy ] = $data;
        }

        // Если оба запроса провалились.
        if ( null === $combined['mobile'] && null === $combined['desktop'] ) {
            $result['error'] = $result['error'] ? $result['error'] : 'no_data';
            set_transient( $cache_key, $result, 300 ); // 5 минут при ошибке.
            return $result;
        }

        // Используем mobile как основной источник (он более критичен).
        $main = null !== $combined['mobile'] ? $combined['mobile'] : $combined['desktop'];
        $result['final_url'] = isset( $main['id'] ) ? $main['id'] : $url;
        $result['lighthouse_version'] = isset( $main['lighthouseResult']['lighthouseVersion'] ) ? $main['lighthouseResult']['lighthouseVersion'] : '';

        if ( isset( $main['lighthouseResult']['categories'] ) ) {
            foreach ( $main['lighthouseResult']['categories'] as $key => $cat ) {
                $result['categories'][ $key ] = array(
                    'title' => isset( $cat['title'] ) ? $cat['title'] : $key,
                    'score' => isset( $cat['score'] ) ? round( $cat['score'] * 100 ) : 0,
                );
            }
        }

        // Извлекаем ключевые метрики.
        $audits = isset( $main['lighthouseResult']['audits'] ) ? $main['lighthouseResult']['audits'] : array();
        $key_audits = array(
            'first-contentful-paint',
            'largest-contentful-paint',
            'first-meaningful-paint',
            'speed-index',
            'total-blocking-time',
            'cumulative-layout-shift',
            'interactive',
            'total-byte-weight',
            'dom-size',
            'uses-responsive-images',
            'uses-optimized-images',
            'number-of-resources',
            'redirects',
            'uses-text-compression',
            'uses-long-cache-ttl',
            'server-response-time',
            'font-display',
            'canonical',
            'meta-description',
            'document-title',
            'is-on-https',
            'viewport',
            'robots-txt',
        );

        foreach ( $key_audits as $audit_key ) {
            if ( isset( $audits[ $audit_key ] ) ) {
                $a = $audits[ $audit_key ];
                $result['audits'][ $audit_key ] = array(
                    'title'      => isset( $a['title'] ) ? $a['title'] : $audit_key,
                    'score'      => isset( $a['score'] ) ? $a['score'] : null,
                    'value'      => isset( $a['numericValue'] ) ? $a['numericValue'] : null,
                    'display'    => isset( $a['displayValue'] ) ? $a['displayValue'] : '',
                    'unit'       => isset( $a['numericUnit'] ) ? $a['numericUnit'] : '',
                );
            }
        }

        // Статистика по запросам (для desktop если есть — берём как основной, mobile для fallback).
        $desktop_data = $combined['desktop'];
        if ( null !== $desktop_data && isset( $desktop_data['lighthouseResult']['audits']['network-requests'] ) ) {
            $result['audits']['network-requests'] = array(
                'title'   => 'Network Requests',
                'value'   => isset( $desktop_data['lighthouseResult']['audits']['network-requests']['numericValue'] ) ? $desktop_data['lighthouseResult']['audits']['network-requests']['numericValue'] : 0,
                'display' => isset( $desktop_data['lighthouseResult']['audits']['network-requests']['displayValue'] ) ? $desktop_data['lighthouseResult']['audits']['network-requests']['displayValue'] : '',
            );
        }

        $cache_ttl = WVE_Settings::get( 'enable_caching', 1 ) ? WVE_Settings::get( 'cache_ttl', 3600 ) : 60;
        set_transient( $cache_key, $result, $cache_ttl );
        return $result;
    }

    /**
     * Парсинг HTML главной страницы сайта.
     *
     * @param string $url URL.
     * @return array
     */
    public function get_html_info( $url ) {
        $cache_key = 'wve_html_' . md5( $url );
        $cached = get_transient( $cache_key );
        if ( false !== $cached ) {
            return $cached;
        }

        $info = array(
            'title'             => '',
            'description'       => '',
            'og_tags'           => array(),
            'twitter_card'      => false,
            'schema_org'        => false,
            'has_google_analytics' => false,
            'has_google_ads'    => false,
            'has_ym'            => false, // Яндекс.Метрика
            'has_vk_pixel'      => false,
            'has_facebook_pixel' => false,
            'social_links_count' => 0,
            'social_links'      => array(),
            'html_size'         => 0,
            'language'          => '',
            'favicon'           => '',
            'charset'           => '',
            'external_links'    => 0,
            'internal_links'    => 0,
            'images_count'      => 0,
            'scripts_count'     => 0,
            'styles_count'      => 0,
            'https'             => false,
            'content_type'      => '',
            'response_time'     => 0,
            'headers'           => array(),
            'error'             => null,
        );

        $start = microtime( true );
        $response = wp_remote_get( $url, array(
            'timeout'    => WVE_Settings::get( 'request_timeout', 30 ),
            'redirection' => 5,
            'sslverify'  => false,
            'user-agent' => 'Mozilla/5.0 (compatible; WebsiteValueEstimator/1.0; +https://github.com/Alexlip531/ner)',
        ) );
        $info['response_time'] = round( ( microtime( true ) - $start ) * 1000 );

        if ( is_wp_error( $response ) ) {
            $info['error'] = $response->get_error_message();
            set_transient( $cache_key, $info, 300 );
            return $info;
        }

        $code = wp_remote_retrieve_response_code( $response );
        if ( $code >= 400 ) {
            $info['error'] = sprintf( 'HTTP %d', $code );
            set_transient( $cache_key, $info, 300 );
            return $info;
        }

        $body = wp_remote_retrieve_body( $response );
        $info['html_size'] = strlen( $body );
        $info['https'] = ( 0 === strpos( $url, 'https://' ) );

        // Заголовки.
        $info['headers'] = wp_remote_retrieve_headers( $response );
        if ( is_object( $info['headers'] ) ) {
            $info['headers'] = (array) $info['headers'];
        }
        if ( isset( $info['headers']['content-type'] ) ) {
            $info['content_type'] = $info['headers']['content-type'];
        }

        // Если это не HTML — пропускаем парсинг.
        if ( false === strpos( $info['content_type'], 'text/html' ) ) {
            set_transient( $cache_key, $info, 600 );
            return $info;
        }

        // <title>
        if ( preg_match( '#<title[^>]*>([^<]+)</title>#i', $body, $m ) ) {
            $info['title'] = trim( html_entity_decode( $m[1], ENT_QUOTES | ENT_HTML5 ) );
        }

        // description
        if ( preg_match( '#<meta[^>]+name=["\']description["\'][^>]+content=["\']([^"\']*)["\']#i', $body, $m ) ) {
            $info['description'] = trim( html_entity_decode( $m[1], ENT_QUOTES | ENT_HTML5 ) );
        }

        // Open Graph
        if ( preg_match_all( '#<meta[^>]+property=["\']og:([^"\']+)["\'][^>]+content=["\']([^"\']*)["\']#i', $body, $m ) ) {
            foreach ( $m[1] as $i => $key ) {
                $info['og_tags'][ $key ] = $m[2][ $i ];
            }
        }

        // Twitter card
        $info['twitter_card'] = (bool) preg_match( '#<meta[^>]+name=["\']twitter:card#i', $body );

        // Schema.org
        $info['schema_org'] = (bool) preg_match( '#application/ld\+json#i', $body );

        // Analytics / ads
        $info['has_google_analytics'] = (bool) preg_match( '#(gtag\(|google-analytics\.com/analytics|UA-\d+|G-[A-Z0-9]+)#i', $body );
        $info['has_google_ads']       = (bool) preg_match( '#(pagead2\.googlesyndication|google_ad_client|adsbygoogle)#i', $body );
        $info['has_ym']               = (bool) preg_match( '#(mc\.yandex\.ru|ym\(\d+#i', $body );
        $info['has_vk_pixel']         = (bool) preg_match( '#(vk\.com/rtrg|VK\.Retargeting)#i', $body );
        $info['has_facebook_pixel']   = (bool) preg_match( '#(connect\.facebook\.net|fbq\()#i', $body );

        // Social links
        $social_patterns = array(
            'facebook'  => 'facebook\.com',
            'twitter'   => '(?:twitter|x)\.com',
            'instagram' => 'instagram\.com',
            'youtube'   => 'youtube\.com|youtu\.be',
            'telegram'  => 't\.me',
            'vk'        => 'vk\.com',
            'linkedin'  => 'linkedin\.com',
            'tiktok'    => 'tiktok\.com',
            'discord'   => 'discord\.(?:com|gg)',
        );
        foreach ( $social_patterns as $name => $pattern ) {
            if ( preg_match( '#href=["\'][^"\']*(' . $pattern . ')/[^"\']+["\']#i', $body ) ) {
                $info['social_links'][ $name ] = true;
                $info['social_links_count']++;
            }
        }

        // language
        if ( preg_match( '#<html[^>]+lang=["\']([a-z\-]+)["\']#i', $body, $m ) ) {
            $info['language'] = $m[1];
        }

        // charset
        if ( preg_match( '#<meta[^>]+charset=["\']?([\w\-]+)["\']?#i', $body, $m ) ) {
            $info['charset'] = $m[1];
        }

        // favicon
        if ( preg_match( '#<link[^>]+rel=["\'](?:shortcut )?icon["\'][^>]+href=["\']([^"\']+)["\']#i', $body, $m ) ) {
            $info['favicon'] = $m[1];
        }

        // Считаем ссылки/изображения/скрипты.
        $info['external_links']  = preg_match_all( '#<a[^>]+href=["\']https?://(?!' . preg_quote( $this->get_domain( $url ), '#' ) . ')#i', $body );
        $info['internal_links']  = preg_match_all( '#<a[^>]+href=["\'](?:https?://[^/]*' . preg_quote( $this->get_domain( $url ), '#' ) . '|/)[^"\']*["\']#i', $body );
        $info['images_count']    = preg_match_all( '#<img[^>]+src=#i', $body );
        $info['scripts_count']   = preg_match_all( '#<script\b#i', $body );
        $info['styles_count']    = preg_match_all( '#<link[^>]+rel=["\']stylesheet["\']#i', $body );

        $cache_ttl = WVE_Settings::get( 'enable_caching', 1 ) ? WVE_Settings::get( 'cache_ttl', 3600 ) : 60;
        set_transient( $cache_key, $info, $cache_ttl );
        return $info;
    }

    /**
     * Получить SSL-сертификат информацию (через curl-эмуляцию не получится,
     * поэтому проверяем через HTTP заголовки).
     *
     * @param string $url URL.
     * @return array
     */
    public function get_ssl_info( $url ) {
        $info = array(
            'has_ssl'      => false,
            'issuer'       => '',
            'valid_from'   => '',
            'valid_to'     => '',
            'days_left'    => 0,
        );

        if ( 0 !== strpos( $url, 'https://' ) ) {
            return $info;
        }

        $info['has_ssl'] = true;

        // Проверяем через стрим-контекст SSL.
        $context = stream_context_create( array(
            'ssl' => array(
                'capture_peer_cert' => true,
                'verify_peer'       => false,
                'verify_peer_name'  => false,
            ),
        ) );

        $parsed = wp_parse_url( $url );
        $host = $parsed['host'];
        $port = isset( $parsed['port'] ) ? $parsed['port'] : 443;

        $errno = 0;
        $errstr = '';
        $start = microtime( true );
        $socket = @stream_socket_client( 'ssl://' . $host . ':' . $port, $errno, $errstr, 10, STREAM_CLIENT_CONNECT, $context );
        if ( false === $socket ) {
            $info['has_ssl'] = false;
            return $info;
        }

        $params = stream_context_get_params( $socket );
        if ( isset( $params['options']['ssl']['peer_certificate'] ) ) {
            $cert = openssl_x509_parse( $params['options']['ssl']['peer_certificate'] );
            if ( is_array( $cert ) ) {
                $info['issuer'] = isset( $cert['issuer']['O'] ) ? $cert['issuer']['O'] : ( isset( $cert['issuer']['CN'] ) ? $cert['issuer']['CN'] : '' );
                $info['valid_from'] = isset( $cert['validFrom_time_t'] ) ? gmdate( 'Y-m-d', $cert['validFrom_time_t'] ) : '';
                $info['valid_to']   = isset( $cert['validTo_time_t'] ) ? gmdate( 'Y-m-d', $cert['validTo_time_t'] ) : '';
                $info['days_left']  = isset( $cert['validTo_time_t'] ) ? max( 0, floor( ( $cert['validTo_time_t'] - time() ) / 86400 ) ) : 0;
            }
        }
        fclose( $socket );
        return $info;
    }

    /**
     * Полный анализ сайта — собрать все данные.
     *
     * @param string $url URL.
     * @return array
     */
    public function analyze_site( $url ) {
        $url = $this->normalize_url( $url );
        if ( ! $url ) {
            return array( 'error' => 'invalid_url' );
        }

        $domain = $this->get_domain( $url );

        return array(
            'url'          => $url,
            'domain'       => $domain,
            'html_info'    => $this->get_html_info( $url ),
            'domain_info'  => $this->get_domain_info( $domain ),
            'ssl_info'     => $this->get_ssl_info( $url ),
            'pagespeed'    => $this->get_pagespeed_data( $url ),
            'analyzed_at'  => current_time( 'mysql' ),
        );
    }
}
