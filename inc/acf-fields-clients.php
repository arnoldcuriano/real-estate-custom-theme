<?php
/**
 * Local ACF field group for clients.
 *
 * @package real-estate-custom-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register local ACF field group for client cards.
 *
 * @return void
 */
function real_estate_custom_theme_register_client_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'                   => 'group_rect_client_card_data',
			'title'                 => __( 'Client Card Data', 'real-estate-custom-theme' ),
			'fields'                => array(
				array(
					'key'          => 'field_rect_client_since',
					'label'        => __( 'Since', 'real-estate-custom-theme' ),
					'name'         => 'client_since',
					'type'         => 'text',
					'instructions' => __( 'Example: 2019 or Since 2019', 'real-estate-custom-theme' ),
					'required'     => 0,
				),
				array(
					'key'          => 'field_rect_client_industry',
					'label'        => __( 'Industry', 'real-estate-custom-theme' ),
					'name'         => 'client_industry',
					'type'         => 'text',
					'instructions' => __( 'Example: Commercial Real Estate', 'real-estate-custom-theme' ),
					'required'     => 0,
				),
				array(
					'key'          => 'field_rect_client_service_type',
					'label'        => __( 'Service Type', 'real-estate-custom-theme' ),
					'name'         => 'client_service_type',
					'type'         => 'text',
					'instructions' => __( 'Example: Luxury Home Development', 'real-estate-custom-theme' ),
					'required'     => 0,
				),
				array(
					'key'          => 'field_rect_client_testimonial',
					'label'        => __( 'What They Said', 'real-estate-custom-theme' ),
					'name'         => 'client_testimonial',
					'type'         => 'textarea',
					'instructions' => __( 'Short testimonial shown on the About page client card.', 'real-estate-custom-theme' ),
					'required'     => 0,
					'rows'         => 4,
					'new_lines'    => 'br',
				),
				array(
					'key'          => 'field_rect_client_website',
					'label'        => __( 'Website URL', 'real-estate-custom-theme' ),
					'name'         => 'client_website',
					'type'         => 'url',
					'instructions' => __( 'External URL used by the "Visit Website" button.', 'real-estate-custom-theme' ),
					'required'     => 0,
				),
				array(
					'key'           => 'field_rect_client_is_featured',
					'label'         => __( 'Feature on About Page', 'real-estate-custom-theme' ),
					'name'          => 'is_featured',
					'type'          => 'true_false',
					'instructions'  => __( 'Enable to include this client in the About page slider.', 'real-estate-custom-theme' ),
					'required'      => 0,
					'ui'            => 1,
					'default_value' => 0,
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'client',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => __( 'Metadata for About page "Our Valued Clients" cards.', 'real-estate-custom-theme' ),
		)
	);
}
add_action( 'acf/init', 'real_estate_custom_theme_register_client_acf_fields' );

