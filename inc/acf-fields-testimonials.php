<?php
/**
 * Local ACF field group for testimonials.
 *
 * @package real-estate-custom-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register local ACF field group for testimonial content.
 *
 * @return void
 */
function real_estate_custom_theme_register_testimonial_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'                   => 'group_rect_testimonial_data',
			'title'                 => __( 'Testimonial Data', 'real-estate-custom-theme' ),
			'fields'                => array(
				array(
					'key'           => 'field_rect_testimonial_rating',
					'label'         => __( 'Rating', 'real-estate-custom-theme' ),
					'name'          => 'testimonial_rating',
					'type'          => 'select',
					'required'      => 1,
					'choices'       => array(
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
					),
					'default_value' => '5',
					'allow_null'    => 0,
					'ui'            => 1,
				),
				array(
					'key'          => 'field_rect_testimonial_quote',
					'label'        => __( 'Quote', 'real-estate-custom-theme' ),
					'name'         => 'testimonial_quote',
					'type'         => 'textarea',
					'instructions' => __( 'Main testimonial quote displayed on cards.', 'real-estate-custom-theme' ),
					'required'     => 1,
					'rows'         => 5,
					'new_lines'    => 'br',
				),
				array(
					'key'          => 'field_rect_testimonial_client_name',
					'label'        => __( 'Client Name', 'real-estate-custom-theme' ),
					'name'         => 'client_name',
					'type'         => 'text',
					'instructions' => __( 'Example: Wade Warren', 'real-estate-custom-theme' ),
					'required'     => 1,
				),
				array(
					'key'          => 'field_rect_testimonial_client_location',
					'label'        => __( 'Client Location', 'real-estate-custom-theme' ),
					'name'         => 'client_location',
					'type'         => 'text',
					'instructions' => __( 'Example: USA, California', 'real-estate-custom-theme' ),
					'required'     => 0,
				),
				array(
					'key'           => 'field_rect_testimonial_client_photo',
					'label'         => __( 'Client Photo', 'real-estate-custom-theme' ),
					'name'          => 'client_photo',
					'type'          => 'image',
					'return_format' => 'id',
					'preview_size'  => 'thumbnail',
					'library'       => 'all',
					'required'      => 0,
				),
				array(
					'key'           => 'field_rect_testimonial_is_featured',
					'label'         => __( 'Feature on Home', 'real-estate-custom-theme' ),
					'name'          => 'is_featured',
					'type'          => 'true_false',
					'instructions'  => __( 'Enable to include this testimonial in the home slider.', 'real-estate-custom-theme' ),
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
						'value'    => 'testimonial',
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
			'description'           => __( 'Testimonial metadata for homepage slider and archive cards.', 'real-estate-custom-theme' ),
		)
	);
}
add_action( 'acf/init', 'real_estate_custom_theme_register_testimonial_acf_fields' );
