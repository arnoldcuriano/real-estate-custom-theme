<?php
/**
 * Team Member helper functions.
 *
 * @package real-estate-custom-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Normalize URL/email values for team actions and social links.
 *
 * @param string $raw_value Raw URL or email value.
 *
 * @return string
 */
function real_estate_custom_theme_normalize_team_member_action_url( $raw_value ) {
	$value = trim( (string) $raw_value );
	if ( '' === $value ) {
		return '';
	}

	if ( is_email( $value ) ) {
		return 'mailto:' . sanitize_email( $value );
	}

	$normalized_url = esc_url_raw( $value, array( 'http', 'https', 'mailto' ) );
	if ( '' !== $normalized_url ) {
		return $normalized_url;
	}

	return '';
}

/**
 * Get team member photo URL with fallback chain.
 *
 * @param int $post_id Team member post ID.
 *
 * @return string
 */
function real_estate_custom_theme_get_team_member_photo_url( $post_id ) {
	$photo_value = get_post_meta( $post_id, 'photo', true );

	if ( function_exists( 'get_field' ) ) {
		$field_value = get_field( 'photo', $post_id );
		if ( ! empty( $field_value ) ) {
			$photo_value = $field_value;
		}
	}

	if ( is_array( $photo_value ) && ! empty( $photo_value['url'] ) ) {
		return esc_url_raw( (string) $photo_value['url'] );
	}

	if ( is_numeric( $photo_value ) ) {
		$photo_url = wp_get_attachment_image_url( (int) $photo_value, 'medium_large' );
		if ( ! empty( $photo_url ) ) {
			return esc_url_raw( $photo_url );
		}
	}

	if ( is_string( $photo_value ) && '' !== trim( $photo_value ) ) {
		$photo_url = esc_url_raw( (string) $photo_value );
		if ( '' !== $photo_url ) {
			return $photo_url;
		}
	}

	if ( has_post_thumbnail( $post_id ) ) {
		$thumbnail_url = get_the_post_thumbnail_url( $post_id, 'medium_large' );
		if ( ! empty( $thumbnail_url ) ) {
			return esc_url_raw( $thumbnail_url );
		}
	}

	if ( function_exists( 'real_estate_custom_theme_get_symbol_asset_url' ) ) {
		$symbol_url = real_estate_custom_theme_get_symbol_asset_url();
		if ( ! empty( $symbol_url ) ) {
			return esc_url_raw( $symbol_url );
		}
	}

	return '';
}

/**
 * Get team member role title.
 *
 * @param int $post_id Team member post ID.
 *
 * @return string
 */
function real_estate_custom_theme_get_team_member_position_title( $post_id ) {
	return trim( (string) get_post_meta( $post_id, 'position_title', true ) );
}

/**
 * Get normalized team member social links.
 *
 * @param int $post_id Team member post ID.
 *
 * @return array<int, array<string, string>>
 */
function real_estate_custom_theme_get_team_member_social_links( $post_id ) {
	$rows = array();
	if ( function_exists( 'get_field' ) ) {
		$field_rows = get_field( 'social_links', $post_id );
		if ( is_array( $field_rows ) ) {
			$rows = $field_rows;
		}
	}

	$normalized_rows = array();

	foreach ( $rows as $row ) {
		$platform = isset( $row['platform'] ) ? sanitize_key( (string) $row['platform'] ) : '';
		$url      = isset( $row['url'] ) ? real_estate_custom_theme_normalize_team_member_action_url( $row['url'] ) : '';

		if ( '' === $platform || '' === $url ) {
			continue;
		}

		$normalized_rows[] = array(
			'platform' => $platform,
			'url'      => $url,
		);
	}

	return $normalized_rows;
}

/**
 * Get primary social link (first row) for team member.
 *
 * @param int $post_id Team member post ID.
 *
 * @return array<string, string>|null
 */
function real_estate_custom_theme_get_team_member_primary_social( $post_id ) {
	$social_links = real_estate_custom_theme_get_team_member_social_links( $post_id );
	if ( empty( $social_links ) ) {
		return null;
	}

	return $social_links[0];
}

/**
 * Resolve team member custom profile icon URL.
 *
 * @param int $post_id Team member post ID.
 *
 * @return string
 */
function real_estate_custom_theme_get_team_member_profile_icon_custom_url( $post_id ) {
	$icon_value = get_post_meta( $post_id, 'profile_icon_custom', true );

	if ( function_exists( 'get_field' ) ) {
		$field_value = get_field( 'profile_icon_custom', $post_id );
		if ( ! empty( $field_value ) ) {
			$icon_value = $field_value;
		}
	}

	if ( is_array( $icon_value ) && ! empty( $icon_value['url'] ) ) {
		return esc_url_raw( (string) $icon_value['url'] );
	}

	if ( is_numeric( $icon_value ) ) {
		$icon_url = wp_get_attachment_image_url( (int) $icon_value, 'thumbnail' );
		if ( ! empty( $icon_url ) ) {
			return esc_url_raw( $icon_url );
		}
	}

	if ( is_string( $icon_value ) ) {
		$icon_url = esc_url_raw( trim( $icon_value ) );
		if ( '' !== $icon_url ) {
			return $icon_url;
		}
	}

	return '';
}

/**
 * Get normalized profile icon data for team member card badge.
 *
 * @param int $post_id Team member post ID.
 *
 * @return array<string, string>
 */
function real_estate_custom_theme_get_team_member_profile_icon_data( $post_id ) {
	$icon_source   = trim( (string) get_post_meta( $post_id, 'profile_icon_source', true ) );
	$icon_platform = trim( (string) get_post_meta( $post_id, 'profile_icon_platform', true ) );

	if ( function_exists( 'get_field' ) ) {
		$source_field = get_field( 'profile_icon_source', $post_id );
		if ( ! empty( $source_field ) ) {
			$icon_source = (string) $source_field;
		}

		$platform_field = get_field( 'profile_icon_platform', $post_id );
		if ( ! empty( $platform_field ) ) {
			$icon_platform = (string) $platform_field;
		}
	}

	$icon_source   = sanitize_key( $icon_source );
	$icon_platform = sanitize_key( $icon_platform );

	if ( ! in_array( $icon_source, array( 'default', 'custom' ), true ) ) {
		$icon_source = 'default';
	}

	if ( ! in_array( $icon_platform, array( 'linkedin', 'x', 'email' ), true ) ) {
		$icon_platform = 'linkedin';
	}

	$custom_icon_url = '';
	$icon_type       = 'svg';

	if ( 'custom' === $icon_source ) {
		$custom_icon_url = real_estate_custom_theme_get_team_member_profile_icon_custom_url( $post_id );
		if ( '' !== $custom_icon_url ) {
			$icon_type = 'image';
		} else {
			$icon_platform = 'linkedin';
		}
	}

	$primary_social = real_estate_custom_theme_get_team_member_primary_social( $post_id );
	$link_url       = is_array( $primary_social ) && ! empty( $primary_social['url'] ) ? (string) $primary_social['url'] : '';

	$aria_label = __( 'Open social profile', 'real-estate-custom-theme' );
	if ( 'linkedin' === $icon_platform ) {
		$aria_label = __( 'Open LinkedIn profile', 'real-estate-custom-theme' );
	} elseif ( 'x' === $icon_platform ) {
		$aria_label = __( 'Open X profile', 'real-estate-custom-theme' );
	} elseif ( 'email' === $icon_platform ) {
		$aria_label = __( 'Send email', 'real-estate-custom-theme' );
	}

	return array(
		'type'      => $icon_type,
		'platform'  => $icon_platform,
		'icon_url'  => $custom_icon_url,
		'link_url'  => $link_url,
		'aria_label' => $aria_label,
	);
}

/**
 * Get team member CTA label with fallback.
 *
 * @param int $post_id Team member post ID.
 *
 * @return string
 */
function real_estate_custom_theme_get_team_member_cta_label( $post_id ) {
	$cta_label = trim( (string) get_post_meta( $post_id, 'cta_label', true ) );
	if ( '' !== $cta_label ) {
		return $cta_label;
	}

	return __( 'Say Hello', 'real-estate-custom-theme' );
}

/**
 * Get team member CTA URL with fallback chain.
 *
 * @param int $post_id Team member post ID.
 *
 * @return string
 */
function real_estate_custom_theme_get_team_member_cta_url( $post_id ) {
	$cta_url = real_estate_custom_theme_normalize_team_member_action_url( get_post_meta( $post_id, 'cta_url', true ) );
	if ( '' !== $cta_url ) {
		return $cta_url;
	}

	$primary_social = real_estate_custom_theme_get_team_member_primary_social( $post_id );
	if ( is_array( $primary_social ) && ! empty( $primary_social['url'] ) ) {
		return (string) $primary_social['url'];
	}

	if ( function_exists( 'real_estate_custom_theme_get_contact_page_url' ) ) {
		return real_estate_custom_theme_get_contact_page_url();
	}

	return home_url( '/contact-us/' );
}
