<?php
/**
 * Local ACF field group for Services page content.
 *
 * @package real-estate-custom-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register local ACF field group for Services hero content.
 *
 * @return void
 */
function real_estate_custom_theme_register_services_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$services_page          = get_page_by_path( 'services' );
	$services_page_location = array(
		array(
			array(
				'param'    => 'post_type',
				'operator' => '==',
				'value'    => 'page',
			),
		),
	);

	if ( $services_page instanceof WP_Post ) {
		$services_page_location = array(
			array(
				array(
					'param'    => 'page',
					'operator' => '==',
					'value'    => (string) $services_page->ID,
				),
			),
		);
	}

	acf_add_local_field_group(
		array(
			'key'                   => 'group_rect_services_page_hero',
			'title'                 => __( 'Services Page Hero', 'real-estate-custom-theme' ),
			'fields'                => array(
				array(
					'key'          => 'field_rect_services_hero_title',
					'label'        => __( 'Hero Title', 'real-estate-custom-theme' ),
					'name'         => 'services_hero_title',
					'type'         => 'text',
					'instructions' => __( 'Main heading shown in the Services hero banner.', 'real-estate-custom-theme' ),
					'required'     => 0,
				),
				array(
					'key'          => 'field_rect_services_hero_description',
					'label'        => __( 'Hero Description', 'real-estate-custom-theme' ),
					'name'         => 'services_hero_description',
					'type'         => 'textarea',
					'instructions' => __( 'Short supporting paragraph shown below the Services hero heading.', 'real-estate-custom-theme' ),
					'required'     => 0,
					'rows'         => 3,
					'new_lines'    => 'br',
				),
			),
			'location'              => $services_page_location,
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => __( 'Configures editable heading and description content for the Services hero section.', 'real-estate-custom-theme' ),
		)
	);
}
add_action( 'acf/init', 'real_estate_custom_theme_register_services_acf_fields' );
