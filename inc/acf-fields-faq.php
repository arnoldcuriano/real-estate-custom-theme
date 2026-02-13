<?php
/**
 * Local ACF field group for FAQs.
 *
 * @package real-estate-custom-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register local ACF field group for FAQ content.
 *
 * @return void
 */
function real_estate_custom_theme_register_faq_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'                   => 'group_rect_faq_data',
			'title'                 => __( 'FAQ Data', 'real-estate-custom-theme' ),
			'fields'                => array(
				array(
					'key'           => 'field_rect_faq_is_featured',
					'label'         => __( 'Feature on Home', 'real-estate-custom-theme' ),
					'name'          => 'is_featured',
					'type'          => 'true_false',
					'instructions'  => __( 'Enable to include this FAQ in the home slider.', 'real-estate-custom-theme' ),
					'required'      => 0,
					'ui'            => 1,
					'default_value' => 0,
				),
				array(
					'key'          => 'field_rect_faq_cta_label',
					'label'        => __( 'CTA Label', 'real-estate-custom-theme' ),
					'name'         => 'cta_label',
					'type'         => 'text',
					'instructions' => __( 'Optional button text shown on FAQ cards. Defaults to "Read More".', 'real-estate-custom-theme' ),
					'required'     => 0,
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'faq',
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
			'description'           => __( 'FAQ card metadata for homepage slider and archive cards.', 'real-estate-custom-theme' ),
		)
	);
}
add_action( 'acf/init', 'real_estate_custom_theme_register_faq_acf_fields' );
