<?php
/**
 * Пустой файл шаблона для theme.json — данные темы.
 *
 * @package ZabotaRyadom
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Опции кастомайзера темы.
 */
function zr_customize_register( $wp_customize ) {

	// Секция контактов.
	$wp_customize->add_section(
		'zr_contacts',
		array(
			'title'    => __( 'Контакты', 'zabota-ryadom' ),
			'priority' => 30,
		)
	);

	$fields = array(
		'phone'   => __( 'Телефон', 'zabota-ryadom' ),
		'email'   => __( 'Email', 'zabota-ryadom' ),
		'address' => __( 'Адрес', 'zabota-ryadom' ),
	);

	foreach ( $fields as $key => $label ) {
		$wp_customize->add_setting(
			'zr_' . $key,
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'zr_' . $key,
			array(
				'label'   => $label,
				'section' => 'zr_contacts',
				'type'    => 'text',
			)
		);
	}

	// Секция hero.
	$wp_customize->add_section(
		'zr_hero',
		array(
			'title'    => __( 'Hero секция', 'zabota-ryadom' ),
			'priority' => 31,
		)
	);

	$hero_fields = array(
		'hero_title'    => __( 'Заголовок H1', 'zabota-ryadom' ),
		'hero_subtitle' => __( 'Подзаголовок', 'zabota-ryadom' ),
		'social_proof'  => __( 'Текст социального доказательства', 'zabota-ryadom' ),
	);

	foreach ( $hero_fields as $key => $label ) {
		$wp_customize->add_setting(
			'zr_' . $key,
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'zr_' . $key,
			array(
				'label'   => $label,
				'section' => 'zr_hero',
				'type'    => 'text',
			)
		);
	}
}
add_action( 'customize_register', 'zr_customize_register' );
