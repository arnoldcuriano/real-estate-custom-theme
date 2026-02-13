<?php
/**
 * Testimonial helper functions.
 *
 * @package real-estate-custom-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get testimonial rating value in range 1..5.
 *
 * @param int $post_id Testimonial post ID.
 *
 * @return int
 */
function real_estate_custom_theme_get_testimonial_rating( $post_id ) {
	$rating = (int) get_post_meta( $post_id, 'testimonial_rating', true );
	if ( $rating < 1 || $rating > 5 ) {
		$rating = 5;
	}

	return $rating;
}

/**
 * Get testimonial quote text with safe fallbacks.
 *
 * @param int $post_id Testimonial post ID.
 *
 * @return string
 */
function real_estate_custom_theme_get_testimonial_quote( $post_id ) {
	$quote = trim( (string) get_post_meta( $post_id, 'testimonial_quote', true ) );
	if ( '' !== $quote ) {
		return $quote;
	}

	$excerpt = trim( (string) get_the_excerpt( $post_id ) );
	if ( '' !== $excerpt ) {
		return $excerpt;
	}

	$content = trim( (string) get_post_field( 'post_content', $post_id ) );
	if ( '' === $content ) {
		return '';
	}

	return wp_trim_words( wp_strip_all_tags( $content ), 32 );
}

/**
 * Get testimonial client name with fallback.
 *
 * @param int $post_id Testimonial post ID.
 *
 * @return string
 */
function real_estate_custom_theme_get_testimonial_client_name( $post_id ) {
	$client_name = trim( (string) get_post_meta( $post_id, 'client_name', true ) );
	if ( '' !== $client_name ) {
		return $client_name;
	}

	return get_the_title( $post_id );
}

/**
 * Get testimonial client location.
 *
 * @param int $post_id Testimonial post ID.
 *
 * @return string
 */
function real_estate_custom_theme_get_testimonial_client_location( $post_id ) {
	return trim( (string) get_post_meta( $post_id, 'client_location', true ) );
}

/**
 * Get testimonial client photo URL with fallback.
 *
 * @param int $post_id Testimonial post ID.
 *
 * @return string
 */
function real_estate_custom_theme_get_testimonial_photo_url( $post_id ) {
	$photo_id = (int) get_post_meta( $post_id, 'client_photo', true );
	if ( $photo_id > 0 ) {
		$photo_url = wp_get_attachment_image_url( $photo_id, 'thumbnail' );
		if ( $photo_url ) {
			return $photo_url;
		}
	}

	$featured_image = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
	if ( $featured_image ) {
		return $featured_image;
	}

	if ( function_exists( 'real_estate_custom_theme_get_symbol_asset_url' ) ) {
		return real_estate_custom_theme_get_symbol_asset_url();
	}

	return '';
}
