<?php
/**
 * Native property gallery metabox (ACF-free).
 *
 * @package real-estate-custom-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Normalize a property gallery IDs payload into unique attachment IDs.
 *
 * @param mixed $raw_value Raw post meta payload.
 * @return int[]
 */
function real_estate_custom_theme_normalize_property_gallery_ids( $raw_value ) {
	$raw_ids = array();

	if ( is_array( $raw_value ) ) {
		$raw_ids = $raw_value;
	} elseif ( is_string( $raw_value ) ) {
		$raw_ids = explode( ',', $raw_value );
	} elseif ( is_numeric( $raw_value ) ) {
		$raw_ids = array( $raw_value );
	}

	$normalized = array();
	$seen       = array();

	foreach ( $raw_ids as $raw_id ) {
		$attachment_id = absint( $raw_id );
		if ( $attachment_id <= 0 || isset( $seen[ $attachment_id ] ) ) {
			continue;
		}

		$seen[ $attachment_id ] = true;
		$normalized[]           = $attachment_id;
	}

	return $normalized;
}

/**
 * Get ordered property gallery IDs from native post meta.
 *
 * @param int $post_id Property post ID.
 * @return int[]
 */
function real_estate_custom_theme_get_property_gallery_ids( $post_id ) {
	return real_estate_custom_theme_normalize_property_gallery_ids(
		get_post_meta( absint( $post_id ), '_rect_property_gallery_ids', true )
	);
}

/**
 * Register metabox for property photo gallery.
 *
 * @return void
 */
function real_estate_custom_theme_register_property_gallery_metabox() {
	add_meta_box(
		'rect_property_gallery',
		__( 'Property Photos', 'real-estate-custom-theme' ),
		'real_estate_custom_theme_render_property_gallery_metabox',
		'property',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'real_estate_custom_theme_register_property_gallery_metabox' );

/**
 * Render property gallery metabox UI.
 *
 * @param WP_Post $post Current post.
 * @return void
 */
function real_estate_custom_theme_render_property_gallery_metabox( $post ) {
	$gallery_ids = real_estate_custom_theme_get_property_gallery_ids( $post->ID );

	wp_nonce_field( 'rect_property_gallery_save', 'rect_property_gallery_nonce' );
	?>
	<div class="rect-property-gallery-metabox" data-property-gallery-metabox>
		<input
			type="hidden"
			name="rect_property_gallery_ids"
			value="<?php echo esc_attr( implode( ',', $gallery_ids ) ); ?>"
			data-gallery-ids
		>

		<ul class="rect-property-gallery-metabox__list" data-gallery-list>
			<?php foreach ( $gallery_ids as $attachment_id ) : ?>
				<?php
				$thumb_url = (string) wp_get_attachment_image_url( $attachment_id, 'thumbnail' );
				$alt_text  = trim( (string) get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) );

				if ( '' === $thumb_url ) {
					$thumb_url = (string) wp_get_attachment_image_url( $attachment_id, 'medium' );
				}

				if ( '' === $thumb_url ) {
					continue;
				}

				if ( '' === $alt_text ) {
					$alt_text = get_the_title( $attachment_id );
				}
				?>
				<li class="rect-property-gallery-metabox__item" data-image-id="<?php echo esc_attr( $attachment_id ); ?>">
					<span class="rect-property-gallery-metabox__drag dashicons dashicons-move" aria-hidden="true"></span>
					<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $alt_text ); ?>" loading="lazy">
					<button type="button" class="button-link-delete rect-property-gallery-metabox__remove" data-gallery-remove>
						<?php esc_html_e( 'Remove', 'real-estate-custom-theme' ); ?>
					</button>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="rect-property-gallery-metabox__actions">
			<button type="button" class="button button-secondary" data-gallery-add>
				<?php esc_html_e( 'Add Photos', 'real-estate-custom-theme' ); ?>
			</button>
			<button type="button" class="button-link-delete rect-property-gallery-metabox__clear" data-gallery-clear>
				<?php esc_html_e( 'Clear All', 'real-estate-custom-theme' ); ?>
			</button>
		</div>

		<p class="description">
			<?php esc_html_e( 'Select multiple photos, drag to reorder, then save the property.', 'real-estate-custom-theme' ); ?>
		</p>
	</div>
	<?php
}

/**
 * Save gallery IDs for property posts.
 *
 * @param int $post_id Current post ID.
 * @return void
 */
function real_estate_custom_theme_save_property_gallery_metabox( $post_id ) {
	if ( ! isset( $_POST['rect_property_gallery_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( (string) $_POST['rect_property_gallery_nonce'] ) );
	if ( ! wp_verify_nonce( $nonce, 'rect_property_gallery_save' ) ) {
		return;
	}

	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$raw_ids = isset( $_POST['rect_property_gallery_ids'] ) ? wp_unslash( (string) $_POST['rect_property_gallery_ids'] ) : '';
	$ids     = real_estate_custom_theme_normalize_property_gallery_ids( $raw_ids );

	if ( ! empty( $ids ) ) {
		update_post_meta( $post_id, '_rect_property_gallery_ids', implode( ',', $ids ) );
		return;
	}

	delete_post_meta( $post_id, '_rect_property_gallery_ids' );
}
add_action( 'save_post_property', 'real_estate_custom_theme_save_property_gallery_metabox' );

/**
 * Enqueue admin assets for the property gallery metabox.
 *
 * @param string $hook_suffix Current admin screen hook suffix.
 * @return void
 */
function real_estate_custom_theme_enqueue_property_gallery_metabox_assets( $hook_suffix ) {
	if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'property' !== $screen->post_type ) {
		return;
	}

	$theme_dir = get_template_directory();
	$theme_uri = get_template_directory_uri();

	$css_version = file_exists( $theme_dir . '/css/admin-property-gallery-metabox.css' )
		? (string) filemtime( $theme_dir . '/css/admin-property-gallery-metabox.css' )
		: _S_VERSION;

	$js_version = file_exists( $theme_dir . '/js/admin-property-gallery-metabox.js' )
		? (string) filemtime( $theme_dir . '/js/admin-property-gallery-metabox.js' )
		: _S_VERSION;

	wp_enqueue_media();
	wp_enqueue_script( 'jquery-ui-sortable' );

	wp_enqueue_style(
		'real-estate-custom-theme-admin-property-gallery-metabox',
		$theme_uri . '/css/admin-property-gallery-metabox.css',
		array(),
		$css_version
	);

	wp_enqueue_script(
		'real-estate-custom-theme-admin-property-gallery-metabox',
		$theme_uri . '/js/admin-property-gallery-metabox.js',
		array( 'jquery', 'jquery-ui-sortable' ),
		$js_version,
		true
	);

	wp_localize_script(
		'real-estate-custom-theme-admin-property-gallery-metabox',
		'rectPropertyGalleryMetabox',
		array(
			'frameTitle' => __( 'Select Property Photos', 'real-estate-custom-theme' ),
			'frameButton' => __( 'Use Selected Photos', 'real-estate-custom-theme' ),
			'removeLabel' => __( 'Remove', 'real-estate-custom-theme' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'real_estate_custom_theme_enqueue_property_gallery_metabox_assets' );

