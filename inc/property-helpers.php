<?php
/**
 * Property helpers for icon rendering and card excerpt handling.
 *
 * @package real-estate-custom-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get predefined SVG icon paths for property meta fields.
 *
 * @return array<string, array<string, string>>
 */
function real_estate_custom_theme_get_property_icon_presets() {
	return array(
		'bed'      => array(
			'label' => __( 'Bed', 'real-estate-custom-theme' ),
			'path'  => 'M3 11V7a2 2 0 0 1 2-2h5a2 2 0 0 1 2 2v4M3 11h18M3 11v5M21 11v5M7 16v-2M17 16v-2',
		),
		'bath'     => array(
			'label' => __( 'Bath', 'real-estate-custom-theme' ),
			'path'  => 'M4 11h16M6 11V8a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v3M5 11v4a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4M9 17v2M15 17v2',
		),
		'building' => array(
			'label' => __( 'Building', 'real-estate-custom-theme' ),
			'path'  => 'M4 20h16M6 20V5a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v15M9 8h2M13 8h2M9 12h2M13 12h2M11 20v-4h2v4',
		),
		'tag'      => array(
			'label' => __( 'Tag', 'real-estate-custom-theme' ),
			'path'  => 'M20 10l-8 8-8-8V4h6l10 10zM8 8h.01',
		),
	);
}

/**
 * Resolve icon selection for a property meta field.
 *
 * @param int    $property_id Property post ID.
 * @param string $field_slug  Base field slug (for example: property_bedrooms).
 * @param string $fallback    Fallback preset key.
 *
 * @return array<string, string>
 */
function real_estate_custom_theme_get_property_meta_icon_data( $property_id, $field_slug, $fallback = 'building' ) {
	$icon_presets = real_estate_custom_theme_get_property_icon_presets();
	$source       = (string) get_post_meta( $property_id, $field_slug . '_icon_source', true );
	$preset       = (string) get_post_meta( $property_id, $field_slug . '_icon_preset', true );
	$custom_raw   = get_post_meta( $property_id, $field_slug . '_icon_custom', true );

	$custom_url = '';
	if ( is_numeric( $custom_raw ) ) {
		$custom_url = (string) wp_get_attachment_image_url( (int) $custom_raw, 'thumbnail' );
	} elseif ( is_string( $custom_raw ) && '' !== trim( $custom_raw ) ) {
		$custom_url = (string) $custom_raw;
	}

	if ( 'custom' === $source && '' !== $custom_url ) {
		return array(
			'type' => 'image',
			'url'  => esc_url( $custom_url ),
		);
	}

	$preset_key = isset( $icon_presets[ $preset ] ) ? $preset : $fallback;
	if ( ! isset( $icon_presets[ $preset_key ] ) ) {
		$preset_key = 'building';
	}

	return array(
		'type'  => 'svg',
		'path'  => $icon_presets[ $preset_key ]['path'],
		'label' => $icon_presets[ $preset_key ]['label'],
	);
}

/**
 * Build icon markup for property meta items.
 *
 * @param int    $property_id Property post ID.
 * @param string $field_slug  Base field slug (for example: property_bedrooms).
 * @param string $fallback    Fallback preset key.
 *
 * @return string
 */
function real_estate_custom_theme_get_property_meta_icon_markup( $property_id, $field_slug, $fallback = 'building' ) {
	$icon_data = real_estate_custom_theme_get_property_meta_icon_data( $property_id, $field_slug, $fallback );

	if ( 'image' === $icon_data['type'] && ! empty( $icon_data['url'] ) ) {
		return '<img class="meta-icon meta-icon--image" src="' . esc_url( $icon_data['url'] ) . '" alt="" loading="lazy" aria-hidden="true">';
	}

	$path = isset( $icon_data['path'] ) ? $icon_data['path'] : '';
	if ( '' === $path ) {
		return '';
	}

	return '<svg class="meta-icon meta-icon--svg" viewBox="0 0 24 24" focusable="false" aria-hidden="true"><path d="' . esc_attr( $path ) . '"></path></svg>';
}

/**
 * Resolve property type display label from taxonomy with meta fallback.
 *
 * @param int $property_id Property post ID.
 *
 * @return string
 */
function real_estate_custom_theme_get_property_type_label( $property_id ) {
	$terms = get_the_terms( $property_id, 'property_type' );
	if ( ! is_wp_error( $terms ) && ! empty( $terms ) && isset( $terms[0]->name ) ) {
		return trim( (string) $terms[0]->name );
	}

	return trim( (string) get_post_meta( $property_id, 'property_type', true ) );
}

/**
 * Character length helper with multibyte fallback.
 *
 * @param string $text Text content.
 *
 * @return int
 */
function real_estate_custom_theme_text_length( $text ) {
	if ( function_exists( 'mb_strlen' ) ) {
		return (int) mb_strlen( $text, 'UTF-8' );
	}

	return strlen( $text );
}

/**
 * Build card excerpt text and truncation state.
 *
 * @param int    $property_id    Property post ID.
 * @param string $fallback_text  Fallback source text.
 * @param int    $character_limit Character limit for truncation.
 *
 * @return array<string, mixed>
 */
function real_estate_custom_theme_get_property_card_excerpt_data( $property_id, $fallback_text = '', $character_limit = 155 ) {
	$raw_excerpt = trim( (string) get_post_meta( $property_id, 'property_card_excerpt', true ) );
	if ( '' === $raw_excerpt ) {
		$raw_excerpt = (string) $fallback_text;
	}

	$clean_excerpt = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( $raw_excerpt ) ) );
	if ( '' === $clean_excerpt ) {
		return array(
			'text'       => '',
			'has_more'   => false,
			'max_chars'  => $character_limit,
			'full_text'  => '',
		);
	}

	$length   = real_estate_custom_theme_text_length( $clean_excerpt );
	$has_more = $length > $character_limit;
	$text     = $has_more ? wp_html_excerpt( $clean_excerpt, $character_limit, '...' ) : $clean_excerpt;

	return array(
		'text'      => $text,
		'has_more'  => $has_more,
		'max_chars' => $character_limit,
		'full_text' => $clean_excerpt,
	);
}
