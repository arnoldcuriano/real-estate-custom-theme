<?php
/**
 * Template for single property posts.
 *
 * @package real-estate-custom-theme
 */

get_header();
?>

<main id="primary" class="site-main property-single">
	<?php
	while ( have_posts() ) :
		the_post();

		$property_id           = get_the_ID();
		$property_price        = trim( (string) get_post_meta( $property_id, 'property_price', true ) );
		$property_bedrooms     = trim( (string) get_post_meta( $property_id, 'property_bedrooms', true ) );
		$property_bathroom     = trim( (string) get_post_meta( $property_id, 'property_bathrooms', true ) );
		$property_area_raw     = trim( (string) get_post_meta( $property_id, 'size_sqm', true ) );
		$property_location     = '';
		$property_gallery      = array();
		$property_key_features = function_exists( 'real_estate_custom_theme_get_property_detail_items' )
			? real_estate_custom_theme_get_property_detail_items( $property_id, '_rect_property_key_features' )
			: array();
		$property_amenities    = function_exists( 'real_estate_custom_theme_get_property_detail_items' )
			? real_estate_custom_theme_get_property_detail_items( $property_id, '_rect_property_amenities' )
			: array();
		$metabox_gallery_ids   = function_exists( 'real_estate_custom_theme_get_property_gallery_ids' )
			? real_estate_custom_theme_get_property_gallery_ids( $property_id )
			: array();
		$fallback_image_url    = function_exists( 'real_estate_custom_theme_get_property_fallback_image_url' )
			? real_estate_custom_theme_get_property_fallback_image_url()
			: '';
		$location_terms        = get_the_terms( $property_id, 'property_location' );
		$property_title_text   = get_the_title( $property_id );
		$frame_sizes_attr      = '(max-width: 680px) calc(100vw - 2.5rem), (max-width: 1024px) calc((100vw - 3rem) / 2), 50vw';
		$thumb_sizes_attr      = '(max-width: 680px) 22vw, (max-width: 1024px) 14vw, 10vw';
		$property_description  = '';
		$property_map_payload  = function_exists( 'real_estate_custom_theme_get_property_map_payload' )
			? real_estate_custom_theme_get_property_map_payload( $property_id )
			: array(
				'embed_url'      => '',
				'view_url'       => '',
				'location_label' => '',
			);

		$build_gallery_item_from_attachment = static function( $attachment_id, $alt_fallback, $frame_sizes, $thumb_sizes ) {
			$attachment_id = absint( $attachment_id );
			if ( $attachment_id <= 0 ) {
				return null;
			}

			$frame_src    = (string) wp_get_attachment_image_url( $attachment_id, 'full' );
			$thumb_src    = (string) wp_get_attachment_image_url( $attachment_id, 'medium' );
			$frame_srcset = (string) wp_get_attachment_image_srcset( $attachment_id, 'full' );
			$thumb_srcset = (string) wp_get_attachment_image_srcset( $attachment_id, 'medium' );
			$image_alt    = trim( (string) get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) );

			if ( '' === $frame_src ) {
				$frame_src = (string) wp_get_attachment_image_url( $attachment_id, 'large' );
			}

			if ( '' === $frame_src ) {
				return null;
			}

			if ( '' === $thumb_src ) {
				$thumb_src = (string) wp_get_attachment_image_url( $attachment_id, 'thumbnail' );
			}

			if ( '' === $thumb_src ) {
				$thumb_src = $frame_src;
			}

			if ( '' === $image_alt ) {
				$image_alt = (string) $alt_fallback;
			}

			return array(
				'id'           => $attachment_id,
				'frame_src'    => $frame_src,
				'frame_srcset' => $frame_srcset,
				'frame_sizes'  => $frame_sizes,
				'thumb_src'    => $thumb_src,
				'thumb_srcset' => $thumb_srcset,
				'thumb_sizes'  => $thumb_sizes,
				'alt'          => $image_alt,
			);
		};

		if ( '' === $property_price ) {
			$property_price = trim( (string) get_post_meta( $property_id, 'price', true ) );
		}

		if ( '' === $property_bedrooms ) {
			$property_bedrooms = trim( (string) get_post_meta( $property_id, 'bedrooms', true ) );
		}

		if ( '' === $property_bathroom ) {
			$property_bathroom = trim( (string) get_post_meta( $property_id, 'bathrooms', true ) );
		}

		if ( '' === $property_area_raw ) {
			$property_area_raw = trim( (string) get_post_meta( $property_id, 'lot_area', true ) );
		}

		if ( '' === $property_area_raw ) {
			$property_area_raw = trim( (string) get_post_meta( $property_id, 'property_area', true ) );
		}

		$property_description = trim( (string) get_post_meta( $property_id, 'property_card_excerpt', true ) );
		if ( '' === $property_description ) {
			$property_description = trim( (string) get_the_excerpt( $property_id ) );
		}
		if ( '' === $property_description ) {
			$property_description = trim( (string) wp_strip_all_tags( strip_shortcodes( get_the_content( null, false, $property_id ) ) ) );
		}
		if ( '' !== $property_description ) {
			$property_description = trim( preg_replace( '/\s+/', ' ', $property_description ) );
			$property_description = wp_trim_words( $property_description, 42, '...' );
		}

		if ( ! is_wp_error( $location_terms ) && ! empty( $location_terms ) ) {
			$property_location = trim( (string) $location_terms[0]->name );
		}

		if ( ! empty( $metabox_gallery_ids ) ) {
			foreach ( $metabox_gallery_ids as $image_id ) {
				$gallery_item = $build_gallery_item_from_attachment( $image_id, $property_title_text, $frame_sizes_attr, $thumb_sizes_attr );
				if ( $gallery_item ) {
					$property_gallery[] = $gallery_item;
				}
			}
		}

		if ( empty( $property_gallery ) && function_exists( 'get_field' ) ) {
			$gallery_field = get_field( 'property_gallery', $property_id );

			if ( is_array( $gallery_field ) ) {
				foreach ( $gallery_field as $gallery_item ) {
					$image_id    = 0;
					$image_full  = '';
					$image_thumb = '';
					$image_alt   = '';

					if ( is_numeric( $gallery_item ) ) {
						$image_id = absint( $gallery_item );
					} elseif ( is_array( $gallery_item ) ) {
						$image_id = isset( $gallery_item['ID'] ) ? absint( $gallery_item['ID'] ) : 0;

						if ( isset( $gallery_item['sizes'] ) && is_array( $gallery_item['sizes'] ) ) {
							$image_full  = isset( $gallery_item['sizes']['large'] ) ? (string) $gallery_item['sizes']['large'] : '';
							$image_thumb = isset( $gallery_item['sizes']['thumbnail'] ) ? (string) $gallery_item['sizes']['thumbnail'] : '';
						}

						if ( '' === $image_full && isset( $gallery_item['url'] ) ) {
							$image_full = (string) $gallery_item['url'];
						}

						if ( isset( $gallery_item['alt'] ) ) {
							$image_alt = trim( (string) $gallery_item['alt'] );
						}
					}

					if ( $image_id > 0 ) {
						$normalized_item = $build_gallery_item_from_attachment( $image_id, $property_title_text, $frame_sizes_attr, $thumb_sizes_attr );

						if ( ! $normalized_item ) {
							continue;
						}

						if ( '' !== $image_alt ) {
							$normalized_item['alt'] = $image_alt;
						}

						$property_gallery[] = $normalized_item;
						continue;
					}

					if ( '' === $image_alt ) {
						$image_alt = $property_title_text;
					}

					$property_gallery[] = array(
						'id'           => $image_id,
						'frame_src'    => $image_full,
						'frame_srcset' => '',
						'frame_sizes'  => $frame_sizes_attr,
						'thumb_src'    => '' !== $image_thumb ? $image_thumb : $image_full,
						'thumb_srcset' => '',
						'thumb_sizes'  => $thumb_sizes_attr,
						'alt'          => $image_alt,
					);
				}
			}
		}

		if ( empty( $property_gallery ) && has_post_thumbnail( $property_id ) ) {
			$featured_image_id = get_post_thumbnail_id( $property_id );
			$featured_item     = $build_gallery_item_from_attachment( $featured_image_id, $property_title_text, $frame_sizes_attr, $thumb_sizes_attr );

			if ( $featured_item ) {
				$property_gallery[] = $featured_item;
			}
		}

		if ( empty( $property_gallery ) && '' !== $fallback_image_url ) {
			$property_gallery[] = array(
				'id'           => 0,
				'frame_src'    => $fallback_image_url,
				'frame_srcset' => '',
				'frame_sizes'  => $frame_sizes_attr,
				'thumb_src'    => $fallback_image_url,
				'thumb_srcset' => '',
				'thumb_sizes'  => $thumb_sizes_attr,
				'alt'          => $property_title_text,
			);
		}

		$property_gallery_total     = count( $property_gallery );
		$property_gallery_can_slide = $property_gallery_total > 1;
		$property_gallery_first     = $property_gallery_total > 0 ? $property_gallery[0] : null;
		$property_gallery_second    = $property_gallery_total > 1 ? $property_gallery[1] : $property_gallery_first;
		$property_area_display      = '';
		$all_property_details       = array_merge( $property_key_features, $property_amenities );
		$has_property_details       = ! empty( $all_property_details );
		$property_map_embed_url     = isset( $property_map_payload['embed_url'] ) ? trim( (string) $property_map_payload['embed_url'] ) : '';
		$property_map_view_url      = isset( $property_map_payload['view_url'] ) ? trim( (string) $property_map_payload['view_url'] ) : '';
		$property_map_location      = isset( $property_map_payload['location_label'] ) ? trim( (string) $property_map_payload['location_label'] ) : '';
		$single_property_inquiry_form_shortcode = function_exists( 'real_estate_custom_theme_get_single_property_inquiry_form_shortcode' )
			? real_estate_custom_theme_get_single_property_inquiry_form_shortcode()
			: '';
		$properties_archive_url     = function_exists( 'real_estate_custom_theme_get_properties_archive_url' )
			? real_estate_custom_theme_get_properties_archive_url()
			: get_post_type_archive_link( 'property' );

		if ( '' === $property_map_location ) {
			$property_map_location = $property_location;
		}

		if ( '' !== $property_area_raw ) {
			if ( is_numeric( $property_area_raw ) ) {
				$property_area_display = sprintf(
					/* translators: %s: Area value. */
					__( '%s Square Feet', 'real-estate-custom-theme' ),
					number_format_i18n( (float) $property_area_raw, 0 )
				);
			} else {
				$property_area_display = $property_area_raw;
			}
		}
		?>
		<article <?php post_class( 'property-single__article' ); ?>>
			<nav class="property-single__breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'real-estate-custom-theme' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'real-estate-custom-theme' ); ?></a>
				<span class="property-single__crumb-separator" aria-hidden="true">/</span>
				<a href="<?php echo esc_url( $properties_archive_url ); ?>"><?php esc_html_e( 'Properties', 'real-estate-custom-theme' ); ?></a>
				<span class="property-single__crumb-separator" aria-hidden="true">/</span>
				<span class="property-single__crumb-current"><?php echo esc_html( $property_title_text ); ?></span>
			</nav>

			<header class="property-single__head">
				<div class="property-single__title-block">
					<h1>
						<span class="property-single__title-name"><?php echo esc_html( $property_title_text ); ?></span>
						<?php if ( '' !== $property_location ) : ?>
							<span class="property-single__title-location">
								<svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
									<path d="M12 21s7-5.3 7-11a7 7 0 1 0-14 0c0 5.7 7 11 7 11zM12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"></path>
								</svg>
								<span><?php echo esc_html( $property_location ); ?></span>
							</span>
						<?php endif; ?>
					</h1>
				</div>
				<?php if ( '' !== $property_price ) : ?>
					<p class="property-single__price">
						<span><?php esc_html_e( 'Price', 'real-estate-custom-theme' ); ?></span>
						<strong><?php echo esc_html( $property_price ); ?></strong>
					</p>
				<?php endif; ?>
			</header>

			<?php if ( $property_gallery_first ) : ?>
				<section class="property-single__gallery<?php echo $property_gallery_can_slide ? '' : ' is-single'; ?>" data-property-gallery>
					<div class="property-single__thumbs" role="tablist" aria-label="<?php esc_attr_e( 'Property image thumbnails', 'real-estate-custom-theme' ); ?>">
						<?php foreach ( $property_gallery as $gallery_index => $gallery_image ) : ?>
							<button
								type="button"
								class="property-single__thumb<?php echo 0 === $gallery_index ? ' is-active' : ''; ?>"
								data-gallery-thumb="<?php echo esc_attr( $gallery_index ); ?>"
								data-gallery-full="<?php echo esc_url( $gallery_image['frame_src'] ); ?>"
								data-gallery-srcset="<?php echo esc_attr( $gallery_image['frame_srcset'] ); ?>"
								data-gallery-sizes="<?php echo esc_attr( $gallery_image['frame_sizes'] ); ?>"
								data-gallery-alt="<?php echo esc_attr( $gallery_image['alt'] ); ?>"
								aria-label="<?php echo esc_attr( sprintf( __( 'Show image %1$d of %2$d', 'real-estate-custom-theme' ), $gallery_index + 1, $property_gallery_total ) ); ?>"
								aria-pressed="<?php echo 0 === $gallery_index ? 'true' : 'false'; ?>"
							>
								<img
									src="<?php echo esc_url( $gallery_image['thumb_src'] ); ?>"
									<?php if ( '' !== $gallery_image['thumb_srcset'] ) : ?>
										srcset="<?php echo esc_attr( $gallery_image['thumb_srcset'] ); ?>"
										sizes="<?php echo esc_attr( $gallery_image['thumb_sizes'] ); ?>"
									<?php endif; ?>
									alt="<?php echo esc_attr( $gallery_image['alt'] ); ?>"
									loading="<?php echo 0 === $gallery_index ? 'eager' : 'lazy'; ?>"
									decoding="async"
								>
							</button>
						<?php endforeach; ?>
					</div>

					<div class="property-single__stage">
						<figure class="property-single__frame">
							<img
								data-gallery-frame="0"
								src="<?php echo esc_url( $property_gallery_first['frame_src'] ); ?>"
								<?php if ( '' !== $property_gallery_first['frame_srcset'] ) : ?>
									srcset="<?php echo esc_attr( $property_gallery_first['frame_srcset'] ); ?>"
									sizes="<?php echo esc_attr( $property_gallery_first['frame_sizes'] ); ?>"
								<?php endif; ?>
								alt="<?php echo esc_attr( $property_gallery_first['alt'] ); ?>"
								loading="eager"
								fetchpriority="high"
								decoding="async"
							>
						</figure>
						<figure class="property-single__frame property-single__frame--secondary">
							<img
								data-gallery-frame="1"
								src="<?php echo esc_url( $property_gallery_second['frame_src'] ); ?>"
								<?php if ( '' !== $property_gallery_second['frame_srcset'] ) : ?>
									srcset="<?php echo esc_attr( $property_gallery_second['frame_srcset'] ); ?>"
									sizes="<?php echo esc_attr( $property_gallery_second['frame_sizes'] ); ?>"
								<?php endif; ?>
								alt="<?php echo esc_attr( $property_gallery_second['alt'] ); ?>"
								loading="lazy"
								decoding="async"
							>
						</figure>
					</div>

					<div class="property-single__gallery-controls">
						<button
							type="button"
							class="property-single__gallery-arrow"
							data-gallery-prev
							aria-label="<?php esc_attr_e( 'Previous property image', 'real-estate-custom-theme' ); ?>"
							<?php disabled( ! $property_gallery_can_slide ); ?>
						>
							<svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
								<path d="M15 18l-6-6 6-6"></path>
							</svg>
						</button>

						<p class="property-single__gallery-count">
							<span data-gallery-current>01</span>
							<span aria-hidden="true"> - </span>
							<span data-gallery-total><?php echo esc_html( str_pad( (string) $property_gallery_total, 2, '0', STR_PAD_LEFT ) ); ?></span>
						</p>

						<button
							type="button"
							class="property-single__gallery-arrow"
							data-gallery-next
							aria-label="<?php esc_attr_e( 'Next property image', 'real-estate-custom-theme' ); ?>"
							<?php disabled( ! $property_gallery_can_slide ); ?>
						>
							<svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
								<path d="M9 6l6 6-6 6"></path>
							</svg>
						</button>
					</div>
				</section>
			<?php endif; ?>
		</article>

		<?php if ( '' !== $property_description || '' !== $property_bedrooms || '' !== $property_bathroom || '' !== $property_area_display || $has_property_details ) : ?>
			<section class="property-single__details-shell">
				<section class="property-single__post-gallery<?php echo $has_property_details ? '' : ' is-overview-only'; ?>" aria-label="<?php esc_attr_e( 'Property details', 'real-estate-custom-theme' ); ?>">
					<?php
					$property_detail_icon_allowed_html = array(
						'svg'  => array(
							'class'       => array(),
							'viewBox'     => array(),
							'focusable'   => array(),
							'aria-hidden' => array(),
						),
						'path' => array(
							'd' => array(),
						),
						'img'  => array(
							'class'       => array(),
							'src'         => array(),
							'alt'         => array(),
							'loading'     => array(),
							'aria-hidden' => array(),
						),
					);
					?>
					<div class="property-single__overview-card">
						<h2><?php esc_html_e( 'Description', 'real-estate-custom-theme' ); ?></h2>
						<?php if ( '' !== $property_description ) : ?>
							<p class="property-single__description"><?php echo esc_html( $property_description ); ?></p>
						<?php endif; ?>
						<ul class="property-single__overview-meta">
							<?php if ( '' !== $property_bedrooms ) : ?>
								<li>
									<span class="property-single__overview-label">
										<?php
										if ( function_exists( 'real_estate_custom_theme_get_property_meta_icon_markup' ) ) {
											echo wp_kses(
												real_estate_custom_theme_get_property_meta_icon_markup( $property_id, 'property_bedrooms', 'bed' ),
												$property_detail_icon_allowed_html
											);
										}
										?>
										<?php esc_html_e( 'Bedrooms', 'real-estate-custom-theme' ); ?>
									</span>
									<strong><?php echo esc_html( $property_bedrooms ); ?></strong>
								</li>
							<?php endif; ?>

							<?php if ( '' !== $property_bathroom ) : ?>
								<li>
									<span class="property-single__overview-label">
										<?php
										if ( function_exists( 'real_estate_custom_theme_get_property_meta_icon_markup' ) ) {
											echo wp_kses(
												real_estate_custom_theme_get_property_meta_icon_markup( $property_id, 'property_bathrooms', 'bath' ),
												$property_detail_icon_allowed_html
											);
										}
										?>
										<?php esc_html_e( 'Bathrooms', 'real-estate-custom-theme' ); ?>
									</span>
									<strong><?php echo esc_html( $property_bathroom ); ?></strong>
								</li>
							<?php endif; ?>

							<?php if ( '' !== $property_area_display ) : ?>
								<li>
									<span class="property-single__overview-label">
										<svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
											<path d="M4 4h16v16H4zM9 4v4M15 4v4M9 16v4M15 16v4M4 9h4M16 9h4M4 15h4M16 15h4"></path>
										</svg>
										<?php esc_html_e( 'Area', 'real-estate-custom-theme' ); ?>
									</span>
									<strong><?php echo esc_html( $property_area_display ); ?></strong>
								</li>
							<?php endif; ?>
						</ul>
					</div>

					<?php if ( $has_property_details ) : ?>
						<div class="property-single__features-card">
							<h2><?php esc_html_e( 'Key Features and Amenities', 'real-estate-custom-theme' ); ?></h2>
							<ul class="property-single__features-list">
								<?php foreach ( $all_property_details as $detail_item ) : ?>
									<li class="property-single__feature-item">
										<span class="property-single__feature-icon" aria-hidden="true">
											<?php
											if ( function_exists( 'real_estate_custom_theme_get_property_detail_item_icon_markup' ) ) {
												echo wp_kses(
													real_estate_custom_theme_get_property_detail_item_icon_markup( $detail_item, 'spark' ),
													$property_detail_icon_allowed_html
												);
											}
											?>
										</span>
										<span class="property-single__feature-text">
											<?php
											$detail_label = isset( $detail_item['label'] ) ? (string) $detail_item['label'] : '';
											$detail_value = isset( $detail_item['value'] ) ? (string) $detail_item['value'] : '';
											echo esc_html( '' !== $detail_value ? $detail_label . ' - ' . $detail_value : $detail_label );
											?>
										</span>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
				</section>
			</section>
		<?php endif; ?>

		<?php if ( '' !== $property_map_embed_url ) : ?>
			<section class="property-single__map-shell" aria-label="<?php esc_attr_e( 'Property location map', 'real-estate-custom-theme' ); ?>">
				<div class="property-single__map-card">
					<header class="property-single__map-head">
						<div class="property-single__map-copy">
							<h3><?php esc_html_e( 'Property Location & Nearby Area', 'real-estate-custom-theme' ); ?></h3>
							<p><?php esc_html_e( 'View the exact location and explore what\'s around this property.', 'real-estate-custom-theme' ); ?></p>
						</div>
						<?php if ( '' !== $property_map_location ) : ?>
							<span class="property-single__map-chip">
								<svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
									<path d="M12 21s7-5.3 7-11a7 7 0 1 0-14 0c0 5.7 7 11 7 11zM12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"></path>
								</svg>
								<span><?php echo esc_html( $property_map_location ); ?></span>
							</span>
						<?php endif; ?>
					</header>

					<div class="property-single__map-frame">
						<iframe
							src="<?php echo esc_url( $property_map_embed_url ); ?>"
							title="<?php echo esc_attr( '' !== $property_map_location ? sprintf( __( 'Map for %1$s in %2$s', 'real-estate-custom-theme' ), $property_title_text, $property_map_location ) : sprintf( __( 'Map for %s', 'real-estate-custom-theme' ), $property_title_text ) ); ?>"
							loading="lazy"
							referrerpolicy="no-referrer-when-downgrade"
							allowfullscreen
						></iframe>
					</div>

					<?php if ( '' !== $property_map_view_url ) : ?>
						<p class="property-single__map-action">
							<a href="<?php echo esc_url( $property_map_view_url ); ?>" target="_blank" rel="noopener noreferrer">
								<?php esc_html_e( 'View on Google Maps', 'real-estate-custom-theme' ); ?>
							</a>
						</p>
					<?php endif; ?>
				</div>
			</section>
		<?php endif; ?>

		<section
			class="property-single__inquiry property-inquiry section-shell property-inquiry--single"
			aria-labelledby="property-single-inquiry-title"
			data-selected-property-title="<?php echo esc_attr( $property_title_text ); ?>"
			data-selected-property-location="<?php echo esc_attr( $property_location ); ?>"
		>
			<div class="property-inquiry__layout">
				<div class="property-inquiry__intro">
					<h2 id="property-single-inquiry-title">
						<?php
						echo esc_html(
							sprintf(
								/* translators: %s: property title. */
								__( 'Inquire About %s', 'real-estate-custom-theme' ),
								$property_title_text
							)
						);
						?>
					</h2>
					<p><?php esc_html_e( 'Interested in this property? Fill out the form below, and our real estate experts will get back to you with more details, including scheduling a viewing and answering any questions you may have.', 'real-estate-custom-theme' ); ?></p>
				</div>

				<div class="property-inquiry__panel">
					<?php if ( '' !== $single_property_inquiry_form_shortcode ) : ?>
						<div class="property-inquiry__form-wrap">
							<?php echo do_shortcode( $single_property_inquiry_form_shortcode ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					<?php else : ?>
						<p class="property-inquiry__fallback">
							<?php esc_html_e( 'Single Property Inquiry Form is not available yet. Install and activate Contact Form 7, then create a form titled "Single Property Inquiry Form".', 'real-estate-custom-theme' ); ?>
						</p>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endwhile; ?>
</main>

<?php
get_footer();
