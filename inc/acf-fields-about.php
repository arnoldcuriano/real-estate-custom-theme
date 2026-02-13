<?php
/**
 * Local ACF field group for About page sections.
 *
 * @package real-estate-custom-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register local ACF field group for About page content blocks.
 *
 * @return void
 */
function real_estate_custom_theme_register_about_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$about_page          = get_page_by_path( 'about-us' );
	$about_page_location = array(
		array(
			array(
				'param'    => 'post_type',
				'operator' => '==',
				'value'    => 'page',
			),
		),
	);

	if ( $about_page instanceof WP_Post ) {
		$about_page_location = array(
			array(
				array(
					'param'    => 'page',
					'operator' => '==',
					'value'    => (string) $about_page->ID,
				),
			),
		);
	}

	acf_add_local_field_group(
		array(
			'key'                   => 'group_rect_about_sections',
			'title'                 => __( 'About Page Sections', 'real-estate-custom-theme' ),
			'fields'                => array(
				array(
					'key'          => 'field_rect_achievements_title',
					'label'        => __( 'Achievements Title', 'real-estate-custom-theme' ),
					'name'         => 'achievements_title',
					'type'         => 'text',
					'instructions' => __( 'Main title for the achievements section.', 'real-estate-custom-theme' ),
					'required'     => 0,
				),
				array(
					'key'          => 'field_rect_achievements_description',
					'label'        => __( 'Achievements Description', 'real-estate-custom-theme' ),
					'name'         => 'achievements_description',
					'type'         => 'textarea',
					'instructions' => __( 'Short paragraph shown below the achievements title.', 'real-estate-custom-theme' ),
					'required'     => 0,
					'rows'         => 3,
					'new_lines'    => 'br',
				),
				array(
					'key'          => 'field_rect_achievements_items',
					'label'        => __( 'Achievement Items', 'real-estate-custom-theme' ),
					'name'         => 'achievements_items',
					'type'         => 'repeater',
					'instructions' => __( 'Add achievement cards for the About page.', 'real-estate-custom-theme' ),
					'required'     => 0,
					'layout'       => 'row',
					'button_label' => __( 'Add Achievement', 'real-estate-custom-theme' ),
					'sub_fields'   => array(
						array(
							'key'      => 'field_rect_achievement_title',
							'label'    => __( 'Achievement Title', 'real-estate-custom-theme' ),
							'name'     => 'achievement_title',
							'type'     => 'text',
							'required' => 0,
						),
						array(
							'key'       => 'field_rect_achievement_description',
							'label'     => __( 'Achievement Description', 'real-estate-custom-theme' ),
							'name'      => 'achievement_description',
							'type'      => 'textarea',
							'required'  => 0,
							'rows'      => 3,
							'new_lines' => 'br',
						),
					),
				),
				array(
					'key'          => 'field_rect_steps_section_title',
					'label'        => __( 'Steps Section Title', 'real-estate-custom-theme' ),
					'name'         => 'steps_section_title',
					'type'         => 'text',
					'instructions' => __( 'Main title for the process/steps section.', 'real-estate-custom-theme' ),
					'required'     => 0,
				),
				array(
					'key'          => 'field_rect_steps_section_description',
					'label'        => __( 'Steps Section Description', 'real-estate-custom-theme' ),
					'name'         => 'steps_section_description',
					'type'         => 'textarea',
					'instructions' => __( 'Short paragraph shown below the process title.', 'real-estate-custom-theme' ),
					'required'     => 0,
					'rows'         => 3,
					'new_lines'    => 'br',
				),
				array(
					'key'          => 'field_rect_process_steps',
					'label'        => __( 'Process Steps', 'real-estate-custom-theme' ),
					'name'         => 'process_steps',
					'type'         => 'repeater',
					'instructions' => __( 'Add process steps for the About page.', 'real-estate-custom-theme' ),
					'required'     => 0,
					'layout'       => 'row',
					'button_label' => __( 'Add Step', 'real-estate-custom-theme' ),
					'sub_fields'   => array(
						array(
							'key'          => 'field_rect_step_number',
							'label'        => __( 'Step Number Label', 'real-estate-custom-theme' ),
							'name'         => 'step_number',
							'type'         => 'text',
							'instructions' => __( 'Optional. Example: Step 01. If empty, it will be auto-generated.', 'real-estate-custom-theme' ),
							'required'     => 0,
						),
						array(
							'key'      => 'field_rect_step_title',
							'label'    => __( 'Step Title', 'real-estate-custom-theme' ),
							'name'     => 'step_title',
							'type'     => 'text',
							'required' => 0,
						),
						array(
							'key'       => 'field_rect_step_description',
							'label'     => __( 'Step Description', 'real-estate-custom-theme' ),
							'name'      => 'step_description',
							'type'      => 'textarea',
							'required'  => 0,
							'rows'      => 3,
							'new_lines' => 'br',
						),
					),
				),
				array(
					'key'          => 'field_rect_about_cta_heading',
					'label'        => __( 'CTA Heading', 'real-estate-custom-theme' ),
					'name'         => 'cta_heading',
					'type'         => 'text',
					'instructions' => __( 'Optional heading for the CTA block below the process section.', 'real-estate-custom-theme' ),
					'required'     => 0,
				),
				array(
					'key'          => 'field_rect_about_cta_button_label',
					'label'        => __( 'CTA Button Label', 'real-estate-custom-theme' ),
					'name'         => 'cta_button_label',
					'type'         => 'text',
					'instructions' => __( 'Optional button text for the CTA block.', 'real-estate-custom-theme' ),
					'required'     => 0,
				),
				array(
					'key'          => 'field_rect_about_cta_button_link',
					'label'        => __( 'CTA Button Link', 'real-estate-custom-theme' ),
					'name'         => 'cta_button_link',
					'type'         => 'link',
					'instructions' => __( 'Optional button destination for the CTA block.', 'real-estate-custom-theme' ),
					'required'     => 0,
					'return_format'=> 'array',
				),
			),
			'location'              => $about_page_location,
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => __( 'Configures Achievements and Process sections for the About page.', 'real-estate-custom-theme' ),
		)
	);
}
add_action( 'acf/init', 'real_estate_custom_theme_register_about_acf_fields' );
