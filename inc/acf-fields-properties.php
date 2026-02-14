<?php
/**
 * Local ACF field groups for properties and front-page featured section.
 *
 * Field names are intentionally stable to support both:
 * - code-registered field groups, and
 * - manual ACF UI setup with matching names.
 *
 * @package real-estate-custom-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register local ACF field groups.
 *
 * @return void
 */
function real_estate_custom_theme_register_property_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'                   => 'group_rect_featured_section',
			'title'                 => __( 'Featured Section Content', 'real-estate-custom-theme' ),
			'fields'                => array(
				array(
					'key'           => 'field_rect_featured_section_description',
					'label'         => __( 'Featured Section Description', 'real-estate-custom-theme' ),
					'name'          => 'featured_section_description',
					'type'          => 'textarea',
					'instructions'  => __( 'Text shown under "Featured Properties" on the home page.', 'real-estate-custom-theme' ),
					'required'      => 0,
					'rows'          => 3,
					'new_lines'     => 'wpautop',
					'wrapper'       => array(
						'width' => 100,
					),
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'page_type',
						'operator' => '==',
						'value'    => 'front_page',
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
			'description'           => __( 'Front-page content controls for Featured section copy.', 'real-estate-custom-theme' ),
		)
	);

	acf_add_local_field_group(
		array(
			'key'                   => 'group_rect_property_card_data',
			'title'                 => __( 'Property Card Data', 'real-estate-custom-theme' ),
			'fields'                => array(
				array(
					'key'          => 'field_rect_property_price',
					'label'        => __( 'Price', 'real-estate-custom-theme' ),
					'name'         => 'property_price',
					'type'         => 'text',
					'instructions' => __( 'Example: $550,000', 'real-estate-custom-theme' ),
					'required'     => 0,
				),
				array(
					'key'          => 'field_rect_property_price_numeric',
					'label'        => __( 'Price (Numeric)', 'real-estate-custom-theme' ),
					'name'         => 'price',
					'type'         => 'number',
					'instructions' => __( 'Numeric value used by archive filters. Example: 550000', 'real-estate-custom-theme' ),
					'required'     => 0,
					'min'          => 0,
					'step'         => 1,
				),
				array(
					'key'          => 'field_rect_property_size_sqm',
					'label'        => __( 'Property Size (sqm)', 'real-estate-custom-theme' ),
					'name'         => 'size_sqm',
					'type'         => 'number',
					'instructions' => __( 'Numeric size value in square meters used by archive filters.', 'real-estate-custom-theme' ),
					'required'     => 0,
					'min'          => 0,
					'step'         => 0.01,
				),
				array(
					'key'          => 'field_rect_property_build_year',
					'label'        => __( 'Build Year', 'real-estate-custom-theme' ),
					'name'         => 'build_year',
					'type'         => 'number',
					'instructions' => __( 'Construction year used by archive filters. Example: 2021', 'real-estate-custom-theme' ),
					'required'     => 0,
					'min'          => 1800,
					'max'          => (int) gmdate( 'Y' ) + 2,
					'step'         => 1,
				),
				array(
					'key'          => 'field_rect_property_bedrooms',
					'label'        => __( 'Bedrooms', 'real-estate-custom-theme' ),
					'name'         => 'property_bedrooms',
					'type'         => 'text',
					'instructions' => __( 'Example: 4-Bedroom', 'real-estate-custom-theme' ),
					'required'     => 0,
				),
				array(
					'key'           => 'field_rect_property_bedrooms_icon_source',
					'label'         => __( 'Bedrooms Icon Source', 'real-estate-custom-theme' ),
					'name'          => 'property_bedrooms_icon_source',
					'type'          => 'select',
					'choices'       => array(
						'predefined' => __( 'Predefined Icon', 'real-estate-custom-theme' ),
						'custom'     => __( 'Custom Upload', 'real-estate-custom-theme' ),
					),
					'default_value' => 'predefined',
					'allow_null'    => 0,
					'ui'            => 1,
				),
				array(
					'key'               => 'field_rect_property_bedrooms_icon_preset',
					'label'             => __( 'Bedrooms Predefined Icon', 'real-estate-custom-theme' ),
					'name'              => 'property_bedrooms_icon_preset',
					'type'              => 'select',
					'choices'           => array(
						'bed'      => __( 'Bed', 'real-estate-custom-theme' ),
						'building' => __( 'Building', 'real-estate-custom-theme' ),
						'tag'      => __( 'Tag', 'real-estate-custom-theme' ),
					),
					'default_value'     => 'bed',
					'allow_null'        => 0,
					'ui'                => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_rect_property_bedrooms_icon_source',
								'operator' => '==',
								'value'    => 'predefined',
							),
						),
					),
				),
				array(
					'key'               => 'field_rect_property_bedrooms_icon_custom',
					'label'             => __( 'Bedrooms Custom Icon', 'real-estate-custom-theme' ),
					'name'              => 'property_bedrooms_icon_custom',
					'type'              => 'image',
					'return_format'     => 'id',
					'preview_size'      => 'thumbnail',
					'library'           => 'all',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_rect_property_bedrooms_icon_source',
								'operator' => '==',
								'value'    => 'custom',
							),
						),
					),
				),
				array(
					'key'          => 'field_rect_property_bathrooms',
					'label'        => __( 'Bathrooms', 'real-estate-custom-theme' ),
					'name'         => 'property_bathrooms',
					'type'         => 'text',
					'instructions' => __( 'Example: 3-Bathroom', 'real-estate-custom-theme' ),
					'required'     => 0,
				),
				array(
					'key'           => 'field_rect_property_bathrooms_icon_source',
					'label'         => __( 'Bathrooms Icon Source', 'real-estate-custom-theme' ),
					'name'          => 'property_bathrooms_icon_source',
					'type'          => 'select',
					'choices'       => array(
						'predefined' => __( 'Predefined Icon', 'real-estate-custom-theme' ),
						'custom'     => __( 'Custom Upload', 'real-estate-custom-theme' ),
					),
					'default_value' => 'predefined',
					'allow_null'    => 0,
					'ui'            => 1,
				),
				array(
					'key'               => 'field_rect_property_bathrooms_icon_preset',
					'label'             => __( 'Bathrooms Predefined Icon', 'real-estate-custom-theme' ),
					'name'              => 'property_bathrooms_icon_preset',
					'type'              => 'select',
					'choices'           => array(
						'bath'     => __( 'Bath', 'real-estate-custom-theme' ),
						'building' => __( 'Building', 'real-estate-custom-theme' ),
						'tag'      => __( 'Tag', 'real-estate-custom-theme' ),
					),
					'default_value'     => 'bath',
					'allow_null'        => 0,
					'ui'                => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_rect_property_bathrooms_icon_source',
								'operator' => '==',
								'value'    => 'predefined',
							),
						),
					),
				),
				array(
					'key'               => 'field_rect_property_bathrooms_icon_custom',
					'label'             => __( 'Bathrooms Custom Icon', 'real-estate-custom-theme' ),
					'name'              => 'property_bathrooms_icon_custom',
					'type'              => 'image',
					'return_format'     => 'id',
					'preview_size'      => 'thumbnail',
					'library'           => 'all',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_rect_property_bathrooms_icon_source',
								'operator' => '==',
								'value'    => 'custom',
							),
						),
					),
				),
				array(
					'key'          => 'field_rect_property_type',
					'label'        => __( 'Property Type', 'real-estate-custom-theme' ),
					'name'         => 'property_type',
					'type'         => 'text',
					'instructions' => __( 'Example: Villa, Apartment, Townhouse', 'real-estate-custom-theme' ),
					'required'     => 0,
				),
				array(
					'key'           => 'field_rect_property_type_icon_source',
					'label'         => __( 'Property Type Icon Source', 'real-estate-custom-theme' ),
					'name'          => 'property_type_icon_source',
					'type'          => 'select',
					'choices'       => array(
						'predefined' => __( 'Predefined Icon', 'real-estate-custom-theme' ),
						'custom'     => __( 'Custom Upload', 'real-estate-custom-theme' ),
					),
					'default_value' => 'predefined',
					'allow_null'    => 0,
					'ui'            => 1,
				),
				array(
					'key'               => 'field_rect_property_type_icon_preset',
					'label'             => __( 'Property Type Predefined Icon', 'real-estate-custom-theme' ),
					'name'              => 'property_type_icon_preset',
					'type'              => 'select',
					'choices'           => array(
						'building' => __( 'Building', 'real-estate-custom-theme' ),
						'tag'      => __( 'Tag', 'real-estate-custom-theme' ),
						'bed'      => __( 'Bed', 'real-estate-custom-theme' ),
						'bath'     => __( 'Bath', 'real-estate-custom-theme' ),
					),
					'default_value'     => 'building',
					'allow_null'        => 0,
					'ui'                => 1,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_rect_property_type_icon_source',
								'operator' => '==',
								'value'    => 'predefined',
							),
						),
					),
				),
				array(
					'key'               => 'field_rect_property_type_icon_custom',
					'label'             => __( 'Property Type Custom Icon', 'real-estate-custom-theme' ),
					'name'              => 'property_type_icon_custom',
					'type'              => 'image',
					'return_format'     => 'id',
					'preview_size'      => 'thumbnail',
					'library'           => 'all',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_rect_property_type_icon_source',
								'operator' => '==',
								'value'    => 'custom',
							),
						),
					),
				),
				array(
					'key'          => 'field_rect_property_card_excerpt',
					'label'        => __( 'Card Excerpt (Optional)', 'real-estate-custom-theme' ),
					'name'         => 'property_card_excerpt',
					'type'         => 'textarea',
					'instructions' => __( 'Optional short description shown on cards. Falls back to post excerpt.', 'real-estate-custom-theme' ),
					'required'     => 0,
					'rows'         => 3,
					'new_lines'    => 'br',
				),
				array(
					'key'           => 'field_rect_featured_on_home',
					'label'         => __( 'Feature on Home', 'real-estate-custom-theme' ),
					'name'          => 'featured_on_home',
					'type'          => 'true_false',
					'instructions'  => __( 'Enable to include this property in the home Featured slider.', 'real-estate-custom-theme' ),
					'required'      => 0,
					'ui'            => 1,
					'default_value' => 0,
				),
				array(
					'key'          => 'field_rect_featured_order',
					'label'        => __( 'Featured Order', 'real-estate-custom-theme' ),
					'name'         => 'featured_order',
					'type'         => 'number',
					'instructions' => __( 'Lower values appear first in the home Featured slider.', 'real-estate-custom-theme' ),
					'required'     => 0,
					'min'          => 0,
					'step'         => 1,
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'property',
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
			'description'           => __( 'Card metadata for Featured Properties and property listings.', 'real-estate-custom-theme' ),
		)
	);
}
add_action( 'acf/init', 'real_estate_custom_theme_register_property_acf_fields' );

/**
 * Show admin guidance when ACF is not active.
 *
 * @return void
 */
function real_estate_custom_theme_acf_missing_notice() {
	if ( ! is_admin() || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	if ( function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}
	?>
	<div class="notice notice-info">
		<p>
			<?php esc_html_e( 'Property, testimonial, client, team member, about, and FAQ custom fields require the "Advanced Custom Fields" plugin. Install and activate ACF to populate dynamic slider and card data.', 'real-estate-custom-theme' ); ?>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'real_estate_custom_theme_acf_missing_notice' );
