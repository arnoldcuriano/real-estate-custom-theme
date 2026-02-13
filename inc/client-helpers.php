<?php
/**
 * Client helper functions.
 *
 * @package real-estate-custom-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get formatted client "Since" value.
 *
 * @param int $post_id Client post ID.
 *
 * @return string
 */
function real_estate_custom_theme_get_client_since( $post_id ) {
	$since = trim( (string) get_post_meta( $post_id, 'client_since', true ) );
	if ( '' === $since ) {
		return '';
	}

	if ( 0 === stripos( $since, 'since' ) ) {
		return $since;
	}

	return sprintf(
		/* translators: %s: year or label. */
		__( 'Since %s', 'real-estate-custom-theme' ),
		$since
	);
}

/**
 * Get client domain label with legacy fallback.
 *
 * @param int $post_id Client post ID.
 *
 * @return string
 */
function real_estate_custom_theme_get_client_domain( $post_id ) {
	$domain = trim( (string) get_post_meta( $post_id, 'client_domain', true ) );
	if ( '' !== $domain ) {
		return $domain;
	}

	return trim( (string) get_post_meta( $post_id, 'client_industry', true ) );
}

/**
 * Get client category label with legacy fallback.
 *
 * @param int $post_id Client post ID.
 *
 * @return string
 */
function real_estate_custom_theme_get_client_category( $post_id ) {
	$category = trim( (string) get_post_meta( $post_id, 'client_category', true ) );
	if ( '' !== $category ) {
		return $category;
	}

	return trim( (string) get_post_meta( $post_id, 'client_service_type', true ) );
}

/**
 * Get client industry label (legacy wrapper).
 *
 * @param int $post_id Client post ID.
 *
 * @return string
 */
function real_estate_custom_theme_get_client_industry( $post_id ) {
	return real_estate_custom_theme_get_client_domain( $post_id );
}

/**
 * Get client service type label (legacy wrapper).
 *
 * @param int $post_id Client post ID.
 *
 * @return string
 */
function real_estate_custom_theme_get_client_service_type( $post_id ) {
	return real_estate_custom_theme_get_client_category( $post_id );
}

/**
 * Get client testimonial text with fallback.
 *
 * @param int $post_id Client post ID.
 *
 * @return string
 */
function real_estate_custom_theme_get_client_testimonial( $post_id ) {
	$testimonial = trim( (string) get_post_meta( $post_id, 'client_testimonial', true ) );
	if ( '' !== $testimonial ) {
		return $testimonial;
	}

	$excerpt = trim( (string) get_the_excerpt( $post_id ) );
	if ( '' !== $excerpt ) {
		return $excerpt;
	}

	$content = trim( (string) get_post_field( 'post_content', $post_id ) );
	if ( '' === $content ) {
		return '';
	}

	return wp_trim_words( wp_strip_all_tags( $content ), 26 );
}

/**
 * Get validated client website URL.
 *
 * @param int $post_id Client post ID.
 *
 * @return string
 */
function real_estate_custom_theme_get_client_website_url( $post_id ) {
	$url = trim( (string) get_post_meta( $post_id, 'client_website', true ) );
	if ( '' === $url ) {
		return '';
	}

	return esc_url_raw( $url );
}
