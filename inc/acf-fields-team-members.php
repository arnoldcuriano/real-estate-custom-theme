<?php
/**
 * Local ACF field group for team members.
 *
 * @package real-estate-custom-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register local ACF field group for team member cards.
 *
 * @return void
 */
function real_estate_custom_theme_register_team_member_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'                   => 'group_rect_team_member_card_data',
			'title'                 => __( 'Team Member Card Data', 'real-estate-custom-theme' ),
			'fields'                => array(
				array(
					'key'          => 'field_rect_team_member_position_title',
					'label'        => __( 'Position Title', 'real-estate-custom-theme' ),
					'name'         => 'position_title',
					'type'         => 'text',
					'instructions' => __( 'Example: Founder, Head of Property Management', 'real-estate-custom-theme' ),
					'required'     => 0,
				),
				array(
					'key'           => 'field_rect_team_member_photo',
					'label'         => __( 'Photo', 'real-estate-custom-theme' ),
					'name'          => 'photo',
					'type'          => 'image',
					'return_format' => 'id',
					'preview_size'  => 'thumbnail',
					'library'       => 'all',
					'instructions'  => __( 'Optional override image. Falls back to featured image.', 'real-estate-custom-theme' ),
					'required'      => 0,
				),
				array(
					'key'           => 'field_rect_team_member_profile_icon_source',
					'label'         => __( 'Profile Icon Source', 'real-estate-custom-theme' ),
					'name'          => 'profile_icon_source',
					'type'          => 'select',
					'choices'       => array(
						'default' => __( 'Default Platform Icon', 'real-estate-custom-theme' ),
						'custom'  => __( 'Custom Icon Upload', 'real-estate-custom-theme' ),
					),
					'default_value' => 'default',
					'allow_null'    => 0,
					'ui'            => 1,
					'instructions'  => __( 'Choose whether to use a platform icon or custom icon upload for the profile badge.', 'real-estate-custom-theme' ),
					'required'      => 0,
				),
				array(
					'key'               => 'field_rect_team_member_profile_icon_platform',
					'label'             => __( 'Default Profile Icon Platform', 'real-estate-custom-theme' ),
					'name'              => 'profile_icon_platform',
					'type'              => 'select',
					'choices'           => array(
						'linkedin' => __( 'LinkedIn', 'real-estate-custom-theme' ),
						'x'        => __( 'X', 'real-estate-custom-theme' ),
						'email'    => __( 'Email', 'real-estate-custom-theme' ),
					),
					'default_value'     => 'linkedin',
					'allow_null'        => 0,
					'ui'                => 1,
					'instructions'      => __( 'Used when Profile Icon Source is set to Default.', 'real-estate-custom-theme' ),
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_rect_team_member_profile_icon_source',
								'operator' => '==',
								'value'    => 'default',
							),
						),
					),
				),
				array(
					'key'               => 'field_rect_team_member_profile_icon_custom',
					'label'             => __( 'Custom Profile Icon', 'real-estate-custom-theme' ),
					'name'              => 'profile_icon_custom',
					'type'              => 'image',
					'return_format'     => 'id',
					'preview_size'      => 'thumbnail',
					'library'           => 'all',
					'instructions'      => __( 'Used when Profile Icon Source is set to Custom. Fallback is LinkedIn icon if empty.', 'real-estate-custom-theme' ),
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_rect_team_member_profile_icon_source',
								'operator' => '==',
								'value'    => 'custom',
							),
						),
					),
				),
				array(
					'key'          => 'field_rect_team_member_social_links',
					'label'        => __( 'Social Links', 'real-estate-custom-theme' ),
					'name'         => 'social_links',
					'type'         => 'repeater',
					'instructions' => __( 'Used for the profile badge icon and optional fallback CTA destination.', 'real-estate-custom-theme' ),
					'layout'       => 'table',
					'button_label' => __( 'Add Social Link', 'real-estate-custom-theme' ),
					'sub_fields'   => array(
						array(
							'key'           => 'field_rect_team_member_social_platform',
							'label'         => __( 'Platform', 'real-estate-custom-theme' ),
							'name'          => 'platform',
							'type'          => 'select',
							'choices'       => array(
								'linkedin' => __( 'LinkedIn', 'real-estate-custom-theme' ),
								'x'        => __( 'X', 'real-estate-custom-theme' ),
								'email'    => __( 'Email', 'real-estate-custom-theme' ),
							),
							'default_value' => 'linkedin',
							'allow_null'    => 0,
							'ui'            => 1,
							'required'      => 1,
						),
						array(
							'key'          => 'field_rect_team_member_social_url',
							'label'        => __( 'URL', 'real-estate-custom-theme' ),
							'name'         => 'url',
							'type'         => 'url',
							'instructions' => __( 'Use full URL or mailto: for email.', 'real-estate-custom-theme' ),
							'required'     => 1,
						),
					),
				),
				array(
					'key'           => 'field_rect_team_member_cta_label',
					'label'         => __( 'CTA Label', 'real-estate-custom-theme' ),
					'name'          => 'cta_label',
					'type'          => 'text',
					'instructions'  => __( 'Default fallback is "Say Hello".', 'real-estate-custom-theme' ),
					'default_value' => __( 'Say Hello', 'real-estate-custom-theme' ),
					'required'      => 0,
				),
				array(
					'key'          => 'field_rect_team_member_cta_url',
					'label'        => __( 'CTA URL or Email', 'real-estate-custom-theme' ),
					'name'         => 'cta_url',
					'type'         => 'text',
					'instructions' => __( 'Supports URLs and mailto links. Example: https://... or mailto:name@example.com', 'real-estate-custom-theme' ),
					'required'     => 0,
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'team_member',
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
			'description'           => __( 'Metadata for About page "Meet the Estatein Team" cards.', 'real-estate-custom-theme' ),
		)
	);
}
add_action( 'acf/init', 'real_estate_custom_theme_register_team_member_acf_fields' );
