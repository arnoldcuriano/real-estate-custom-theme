<?php
/**
 * FAQ helper functions.
 *
 * @package real-estate-custom-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build FAQ excerpt with fallback to content and character limit.
 *
 * @param int    $post_id          FAQ post ID.
 * @param string $fallback_excerpt Optional fallback source.
 * @param int    $char_limit       Character limit.
 *
 * @return string
 */
function real_estate_custom_theme_get_faq_excerpt( $post_id, $fallback_excerpt = '', $char_limit = 150 ) {
	$raw_excerpt = trim( (string) get_the_excerpt( $post_id ) );
	if ( '' === $raw_excerpt ) {
		$raw_excerpt = trim( (string) $fallback_excerpt );
	}

	if ( '' === $raw_excerpt ) {
		$raw_excerpt = trim( (string) get_post_field( 'post_content', $post_id ) );
	}

	$clean_excerpt = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( $raw_excerpt ) ) );
	if ( '' === $clean_excerpt ) {
		return '';
	}

	$length = function_exists( 'mb_strlen' )
		? (int) mb_strlen( $clean_excerpt, 'UTF-8' )
		: strlen( $clean_excerpt );

	if ( $length <= $char_limit ) {
		return $clean_excerpt;
	}

	return wp_html_excerpt( $clean_excerpt, $char_limit, '...' );
}

/**
 * Resolve FAQ card CTA label.
 *
 * @param int $post_id FAQ post ID.
 *
 * @return string
 */
function real_estate_custom_theme_get_faq_cta_label( $post_id ) {
	$cta_label = trim( (string) get_post_meta( $post_id, 'cta_label', true ) );
	if ( '' !== $cta_label ) {
		return $cta_label;
	}

	return __( 'Read More', 'real-estate-custom-theme' );
}
