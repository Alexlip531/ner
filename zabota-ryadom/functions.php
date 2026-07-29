<?php
/**
 * Zabota Ryadom functions and definitions.
 *
 * @package ZabotaRyadom
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit;
}

define( 'ZR_VERSION', '1.0.0' );
define( 'ZR_DIR', get_template_directory() );
define( 'ZR_URI', get_template_directory_uri() );

/**
 * Настройки темы.
 */
function zr_setup() {
        // Поддержка перевода.
        load_theme_textdomain( 'zabota-ryadom', ZR_DIR . '/languages' );

        // Title-tag.
        add_theme_support( 'title-tag' );

        // Поддержка миниатюр записей.
        add_theme_support( 'post-thumbnails' );

        // Автоматический фид ссылок.
        add_theme_support( 'automatic-feed-links' );

        // HTML5 разметка.
        add_theme_support(
                'html5',
                array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
        );

        // Кастомный логотип.
        add_theme_support(
                'custom-logo',
                array(
                        'height'      => 48,
                        'width'       => 180,
                        'flex-height' => true,
                        'flex-width'  => true,
                )
        );

        // Регистрация меню.
        register_nav_menus(
                array(
                        'primary' => __( 'Главное меню', 'zabota-ryadom' ),
                        'footer'  => __( 'Меню в футере', 'zabota-ryadom' ),
                )
        );

        // Размеры изображений.
        add_image_size( 'pension-card', 400, 260, true );
        add_image_size( 'hero-image', 800, 700, true );
}
add_action( 'after_setup_theme', 'zr_setup' );

/**
 * Подключение стилей и скриптов.
 */
function zr_enqueue_assets() {
        // Google Fonts: Inter.
        wp_enqueue_style(
                'zr-fonts',
                'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
                array(),
                null
        );

        // Основной стиль темы.
        wp_enqueue_style( 'zr-style', get_stylesheet_uri(), array(), ZR_VERSION );
        wp_enqueue_style( 'zr-main', ZR_URI . '/assets/css/main.css', array( 'zr-style' ), ZR_VERSION );

        // Основной скрипт.
        wp_enqueue_script( 'zr-script', ZR_URI . '/assets/js/main.js', array(), ZR_VERSION, true );

        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
                wp_enqueue_script( 'comment-reply' );
        }
}
add_action( 'wp_enqueue_scripts', 'zr_enqueue_assets' );

/**
 * Регистрация виджетов.
 */
function zr_widgets_init() {
        register_sidebar(
                array(
                        'name'          => __( 'Боковая панель', 'zabota-ryadom' ),
                        'id'            => 'sidebar-1',
                        'description'   => __( 'Виджеты боковой панели', 'zabota-ryadom' ),
                        'before_widget' => '<section id="%1$s" class="widget %2$s">',
                        'after_widget'  => '</section>',
                        'before_title'  => '<h2 class="widget-title">',
                        'after_title'   => '</h2>',
                )
        );

        register_sidebar(
                array(
                        'name'          => __( 'Подвал', 'zabota-ryadom' ),
                        'id'            => 'footer-1',
                        'description'   => __( 'Виджеты в футере', 'zabota-ryadom' ),
                        'before_widget' => '<section id="%1$s" class="footer-widget %2$s">',
                        'after_widget'  => '</section>',
                        'before_title'  => '<h3 class="widget-title">',
                        'after_title'   => '</h3>',
                )
        );
}
add_action( 'widgets_init', 'zr_widgets_init' );

/**
 * Изменение excerpt length.
 *
 * @param int $length Длина.
 * @return int
 */
function zr_excerpt_length( $length ) {
        return 20;
}
add_filter( 'excerpt_length', 'zr_excerpt_length' );

/**
 * Изменение excerpt more.
 *
 * @param string $more Строка.
 * @return string
 */
function zr_excerpt_more( $more ) {
        return '…';
}
add_filter( 'excerpt_more', 'zr_excerpt_more' );

/**
 * Fallback для меню.
 */
function zr_menu_fallback() {
        echo '<ul class="menu">';
        echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Главная', 'zabota-ryadom' ) . '</a></li>';
        wp_list_pages( array( 'title_li' => '' ) );
        echo '</ul>';
}

/**
 * Получение настроек темы с дефолтами.
 *
 * @return array
 */
function zr_get_options() {
        $defaults = array(
                'phone'         => '+7 (495) 123-45-67',
                'email'         => 'info@zabota-ryadom.ru',
                'address'       => 'Москва, ул. Примерная, 1',
                'hero_title'    => 'Подберём лучший уход для вашего близкого',
                'hero_subtitle' => 'Пансионаты, сиделки и уход на дому с проверенными отзывами и реальными фото',
                'social_proof'  => 'Мы уже помогли 12 500 семьям найти подходящий уход',
        );
        return apply_filters( 'zr_options', $defaults );
}
