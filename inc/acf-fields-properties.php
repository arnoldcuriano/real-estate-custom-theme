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
					'key'          => 'field_rect_property_bedrooms',
					'label'        => __( 'Bedrooms', 'real-estate-custom-theme' ),
					'name'         => 'property_bedrooms',
					'type'         => 'text',
					'instructions' => __( 'Example: 4-Bedroom', 'real-estate-custom-theme' ),
					'required'     => 0,
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
					'key'          => 'field_rect_property_type',
					'label'        => __( 'Property Type', 'real-estate-custom-theme' ),
					'name'         => 'property_type',
					'type'         => 'text',
					'instructions' => __( 'Example: Villa, Apartment, Townhouse', 'real-estate-custom-theme' ),
					'required'     => 0,
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
			<?php esc_html_e( 'Featured property fields require the "Advanced Custom Fields" plugin. Install and activate ACF to populate dynamic property card data.', 'real-estate-custom-theme' ); ?>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'real_estate_custom_theme_acf_missing_notice' );

