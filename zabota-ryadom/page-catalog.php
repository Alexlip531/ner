<?php
/**
 * Template Name: Каталог пансионатов
 * Template Post Type: page
 *
 * Шаблон страницы каталога пансионатов с фильтрами и карточками.
 *
 * @package ZabotaRyadom
 */

get_header();
?>

<main id="main" class="site-content catalog-page">

        <!-- ХЛЕБНЫЕ КРОШКИ + ЗАГОЛОВОК -->
        <?php get_template_part( 'template-parts/catalog', 'head' ); ?>

        <div class="container">

                <div class="catalog-layout">

                        <button type="button" class="filters-toggle-mobile">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M2 4h12M4 8h8M6 12h4" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                                <?php esc_html_e( 'Показать фильтры', 'zabota-ryadom' ); ?>
                        </button>

                        <!-- ЛЕВЫЙ САЙДБАР С ФИЛЬТРАМИ -->
                        <aside class="catalog-sidebar" id="catalogFilters">
                                <?php get_template_part( 'template-parts/catalog', 'filters' ); ?>
                        </aside>

                        <!-- ОСНОВНОЙ КОНТЕНТ -->
                        <div class="catalog-content">

                                <!-- ПАНЕЛЬ УПРАВЛЕНИЯ (chips + сортировка) -->
                                <?php get_template_part( 'template-parts/catalog', 'toolbar' ); ?>

                                <!-- СПИСОК КАРТОЧЕК -->
                                <div class="catalog-list">
                                        <?php
                                        $pensions = array(
                                                array(
                                                        'title'       => 'Пансионат «Забота и уют»',
                                                        'address'     => 'м. Новокосино, ул. Салтыковская, 11',
                                                        'distance'    => '2,1 км от МКАД',
                                                        'rating'      => '4.9',
                                                        'reviews'     => 128,
                                                        'price'       => '45 000',
                                                        'extra_photos'=> 24,
                                                        'services'    => array(
                                                                array( 'icon' => '🏥', 'text' => 'Медицинский уход' ),
                                                                array( 'icon' => '👥', 'text' => 'Деменция' ),
                                                                array( 'icon' => '🔄', 'text' => 'Реабилитация' ),
                                                                array( 'icon' => '🧠', 'text' => 'ЛФК' ),
                                                                array( 'icon' => '💊', 'text' => 'Психолог' ),
                                                        ),
                                                        'desc'        => 'Современный пансионат с круглосуточным уходом. Просторные комнаты, прогулки на свежем воздухе, 5-разовое питание. Индивидуальный подход к каждому постояльцу.',
                                                        'benefits'    => array(
                                                                array( 'icon' => 'check', 'text' => 'Есть свободные места' ),
                                                                array( 'icon' => 'clock', 'text' => 'Заселение за 1 день' ),
                                                                array( 'icon' => 'car', 'text' => 'Трансфер' ),
                                                        ),
                                                        'bg'          => '#FED7AA',
                                                ),
                                                array(
                                                        'title'       => 'Пансионат «Солнечный дом»',
                                                        'address'     => 'м. Медведково, ул. Полярная, 5',
                                                        'distance'    => '5,3 км от МКАД',
                                                        'rating'      => '4.8',
                                                        'reviews'     => 96,
                                                        'price'       => '38 000',
                                                        'extra_photos'=> 14,
                                                        'services'    => array(
                                                                array( 'icon' => '🏥', 'text' => 'Медицинский уход' ),
                                                                array( 'icon' => '❤️', 'text' => 'После инсульта' ),
                                                                array( 'icon' => '🧠', 'text' => 'ЛФК' ),
                                                                array( 'icon' => '💊', 'text' => 'Психолог' ),
                                                                array( 'icon' => '🌳', 'text' => 'Терраса' ),
                                                        ),
                                                        'desc'        => 'Уютная атмосфера и профессиональный уход после болезней и операций. Контроль здоровья 24/7. Развивающие занятия и досуг.',
                                                        'benefits'    => array(
                                                                array( 'icon' => 'check', 'text' => 'Есть свободные места' ),
                                                                array( 'icon' => 'clock', 'text' => 'Временное проживание' ),
                                                                array( 'icon' => 'car', 'text' => 'Работаем с ТСП' ),
                                                        ),
                                                        'bg'          => '#BFDBFE',
                                                ),
                                                array(
                                                        'title'       => 'Пансионат «Милосердие»',
                                                        'address'     => 'м. Щёлковская, Сиреневый бульвар, 62',
                                                        'distance'    => '3,7 км от МКАД',
                                                        'rating'      => '4.7',
                                                        'reviews'     => 74,
                                                        'price'       => '42 000',
                                                        'extra_photos'=> 32,
                                                        'services'    => array(
                                                                array( 'icon' => '👥', 'text' => 'Деменция' ),
                                                                array( 'icon' => '🛏️', 'text' => 'Лежачие' ),
                                                                array( 'icon' => '🙏', 'text' => 'Паллиативный уход' ),
                                                                array( 'icon' => '💊', 'text' => 'Психолог' ),
                                                                array( 'icon' => '💆', 'text' => 'Массаж' ),
                                                        ),
                                                        'desc'        => 'Специализированный уход за пожилыми с деменцией и лежачими больными. Уютные комнаты, безопасная среда, внимание и забота персонала.',
                                                        'benefits'    => array(
                                                                array( 'icon' => 'check', 'text' => 'Есть свободные места' ),
                                                                array( 'icon' => 'clock', 'text' => 'Для лежачих' ),
                                                                array( 'icon' => 'car', 'text' => 'Паллиативный уход' ),
                                                        ),
                                                        'bg'          => '#FECDD3',
                                                ),
                                        );

                                        foreach ( $pensions as $pension ) {
                                                set_query_var( 'pension_data', $pension );
                                                get_template_part( 'template-parts/pension', 'list-card' );
                                        }
                                        ?>
                                </div>

                                <!-- ПАГИНАЦИЯ -->
                                <?php get_template_part( 'template-parts/catalog', 'pagination' ); ?>

                        </div>

                </div>

        </div>

</main>

<?php
get_footer();
