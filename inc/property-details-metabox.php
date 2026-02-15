<?php
/**
 * Native property details metabox (Key Features + Amenities).
 *
 * @package real-estate-custom-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get details metabox groups and bound meta keys.
 *
 * @return array<string, array<string, string>>
 */
function real_estate_custom_theme_get_property_details_metabox_groups() {
	return array(
		'key_features' => array(
			'label'    => __( 'Key Features', 'real-estate-custom-theme' ),
			'meta_key' => '_rect_property_key_features',
		),
		'amenities'    => array(
			'label'    => __( 'Amenities', 'real-estate-custom-theme' ),
			'meta_key' => '_rect_property_amenities',
		),
	);
}

/**
 * Get details icon presets for metabox controls.
 *
 * @return array<string, array<string, string>>
 */
function real_estate_custom_theme_get_property_details_metabox_icon_presets() {
	if ( function_exists( 'real_estate_custom_theme_get_property_detail_icon_presets' ) ) {
		return real_estate_custom_theme_get_property_detail_icon_presets();
	}

	return array(
		'check' => array(
			'label' => __( 'Check', 'real-estate-custom-theme' ),
			'path'  => 'M20 6L9 17l-5-5',
		),
	);
}

/**
 * Render one details row in metabox UI.
 *
 * @param string $group_key    Group slug.
 * @param string $row_index    Row index.
 * @param array  $row_item     Row payload.
 * @param array  $icon_presets Icon presets.
 * @return void
 */
function real_estate_custom_theme_render_property_details_metabox_row( $group_key, $row_index, $row_item, $icon_presets ) {
	$icon_preset = isset( $row_item['icon_preset'] ) && isset( $icon_presets[ $row_item['icon_preset'] ] ) ? $row_item['icon_preset'] : 'check';
	$label       = isset( $row_item['label'] ) ? (string) $row_item['label'] : '';
	$value       = isset( $row_item['value'] ) ? (string) $row_item['value'] : '';
	$name_prefix = sprintf( 'rect_property_details[%s][%s]', $group_key, $row_index );
	?>
	<div class="rect-property-details-metabox__row" data-detail-row>
		<div class="rect-property-details-metabox__row-head">
			<span class="rect-property-details-metabox__drag dashicons dashicons-move" aria-hidden="true"></span>
			<strong><?php esc_html_e( 'Item', 'real-estate-custom-theme' ); ?></strong>
			<button type="button" class="button-link-delete rect-property-details-metabox__remove" data-detail-remove>
				<?php esc_html_e( 'Remove', 'real-estate-custom-theme' ); ?>
			</button>
		</div>

		<div class="rect-property-details-metabox__row-grid">
			<label>
				<span><?php esc_html_e( 'Label', 'real-estate-custom-theme' ); ?></span>
				<input
					type="text"
					value="<?php echo esc_attr( $label ); ?>"
					name="<?php echo esc_attr( $name_prefix . '[label]' ); ?>"
					data-field="label"
					placeholder="<?php esc_attr_e( 'Example: 24/7 Security', 'real-estate-custom-theme' ); ?>"
				>
			</label>

			<label>
				<span><?php esc_html_e( 'Value (Optional)', 'real-estate-custom-theme' ); ?></span>
				<input
					type="text"
					value="<?php echo esc_attr( $value ); ?>"
					name="<?php echo esc_attr( $name_prefix . '[value]' ); ?>"
					data-field="value"
					placeholder="<?php esc_attr_e( 'Example: Smart access control', 'real-estate-custom-theme' ); ?>"
				>
			</label>

			<label>
				<span><?php esc_html_e( 'Icon', 'real-estate-custom-theme' ); ?></span>
				<select
					name="<?php echo esc_attr( $name_prefix . '[icon_preset]' ); ?>"
					data-field="icon_preset"
				>
					<?php foreach ( $icon_presets as $icon_key => $icon_data ) : ?>
						<option value="<?php echo esc_attr( $icon_key ); ?>" <?php selected( $icon_key, $icon_preset ); ?>>
							<?php echo esc_html( $icon_data['label'] ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</label>
		</div>
	</div>
	<?php
}

/**
 * Register property details metabox.
 *
 * @return void
 */
function real_estate_custom_theme_register_property_details_metabox() {
	add_meta_box(
		'rect_property_details',
		__( 'Property Details', 'real-estate-custom-theme' ),
		'real_estate_custom_theme_render_property_details_metabox',
		'property',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'real_estate_custom_theme_register_property_details_metabox' );

/**
 * Render property details metabox.
 *
 * @param WP_Post $post Current post object.
 * @return void
 */
function real_estate_custom_theme_render_property_details_metabox( $post ) {
	$groups       = real_estate_custom_theme_get_property_details_metabox_groups();
	$icon_presets = real_estate_custom_theme_get_property_details_metabox_icon_presets();
	$map_embed_url = trim( (string) get_post_meta( $post->ID, '_rect_property_map_embed_url', true ) );

	wp_nonce_field( 'rect_property_details_save', 'rect_property_details_nonce' );
	?>
	<div class="rect-property-details-metabox" data-property-details-metabox>
		<?php foreach ( $groups as $group_key => $group_config ) : ?>
			<?php
			$rows = function_exists( 'real_estate_custom_theme_get_property_detail_items' )
				? real_estate_custom_theme_get_property_detail_items( $post->ID, $group_config['meta_key'] )
				: array();
			?>
			<section class="rect-property-details-metabox__group" data-detail-group="<?php echo esc_attr( $group_key ); ?>">
				<header class="rect-property-details-metabox__group-head">
					<h3><?php echo esc_html( $group_config['label'] ); ?></h3>
					<button type="button" class="button button-secondary" data-detail-add>
						<?php esc_html_e( 'Add Item', 'real-estate-custom-theme' ); ?>
					</button>
				</header>

				<div class="rect-property-details-metabox__rows" data-detail-rows>
					<?php foreach ( $rows as $row_index => $row_item ) : ?>
						<?php real_estate_custom_theme_render_property_details_metabox_row( $group_key, (string) $row_index, $row_item, $icon_presets ); ?>
					<?php endforeach; ?>
				</div>

				<template data-detail-template>
					<?php
					real_estate_custom_theme_render_property_details_metabox_row(
						$group_key,
						'__INDEX__',
						array(
							'label'       => '',
							'value'       => '',
							'icon_preset' => 'check',
						),
						$icon_presets
					);
					?>
				</template>
			</section>
		<?php endforeach; ?>

		<section class="rect-property-details-metabox__group rect-property-details-metabox__group--map">
			<header class="rect-property-details-metabox__group-head">
				<h3><?php esc_html_e( 'Property Map', 'real-estate-custom-theme' ); ?></h3>
			</header>

			<label class="rect-property-details-metabox__map-field" for="rect-property-map-embed-url">
				<span><?php esc_html_e( 'Map Embed URL (Optional)', 'real-estate-custom-theme' ); ?></span>
				<input
					id="rect-property-map-embed-url"
					type="url"
					name="rect_property_map_embed_url"
					value="<?php echo esc_attr( $map_embed_url ); ?>"
					placeholder="https://www.google.com/maps?..."
				>
			</label>
			<p class="description">
				<?php esc_html_e( 'Paste a Google Maps embed URL (Share > Embed a map > src). If empty, the single-property page auto-builds the map from the first property location term.', 'real-estate-custom-theme' ); ?>
			</p>
		</section>

		<p class="description">
			<?php esc_html_e( 'Use drag handles to reorder entries. Each item uses a selectable preset icon.', 'real-estate-custom-theme' ); ?>
		</p>
	</div>
	<?php
}

/**
 * Save property details metabox values.
 *
 * @param int $post_id Current post ID.
 * @return void
 */
function real_estate_custom_theme_save_property_details_metabox( $post_id ) {
	if ( ! isset( $_POST['rect_property_details_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( (string) $_POST['rect_property_details_nonce'] ) );
	if ( ! wp_verify_nonce( $nonce, 'rect_property_details_save' ) ) {
		return;
	}

	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$raw_details = isset( $_POST['rect_property_details'] ) ? wp_unslash( $_POST['rect_property_details'] ) : array();
	$groups      = real_estate_custom_theme_get_property_details_metabox_groups();

	foreach ( $groups as $group_key => $group_config ) {
		$raw_group = array();
		if ( is_array( $raw_details ) && isset( $raw_details[ $group_key ] ) && is_array( $raw_details[ $group_key ] ) ) {
			$raw_group = $raw_details[ $group_key ];
		}

		$normalized_group = function_exists( 'real_estate_custom_theme_normalize_property_detail_items' )
			? real_estate_custom_theme_normalize_property_detail_items( $raw_group )
			: array();

		if ( ! empty( $normalized_group ) ) {
			update_post_meta( $post_id, $group_config['meta_key'], $normalized_group );
			continue;
		}

		delete_post_meta( $post_id, $group_config['meta_key'] );
	}

	$map_embed_url = '';
	if ( isset( $_POST['rect_property_map_embed_url'] ) ) {
		$map_embed_url = trim( (string) wp_unslash( $_POST['rect_property_map_embed_url'] ) );
		$map_embed_url = '' !== $map_embed_url ? esc_url_raw( $map_embed_url ) : '';
	}

	if ( '' !== $map_embed_url ) {
		update_post_meta( $post_id, '_rect_property_map_embed_url', $map_embed_url );
		return;
	}

	delete_post_meta( $post_id, '_rect_property_map_embed_url' );
}
add_action( 'save_post_property', 'real_estate_custom_theme_save_property_details_metabox' );

/**
 * Enqueue admin assets for details metabox.
 *
 * @param string $hook_suffix Current admin screen hook.
 * @return void
 */
function real_estate_custom_theme_enqueue_property_details_metabox_assets( $hook_suffix ) {
	if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'property' !== $screen->post_type ) {
		return;
	}

	$theme_dir = get_template_directory();
	$theme_uri = get_template_directory_uri();

	$css_version = file_exists( $theme_dir . '/css/admin-property-details-metabox.css' )
		? (string) filemtime( $theme_dir . '/css/admin-property-details-metabox.css' )
		: _S_VERSION;

	$js_version = file_exists( $theme_dir . '/js/admin-property-details-metabox.js' )
		? (string) filemtime( $theme_dir . '/js/admin-property-details-metabox.js' )
		: _S_VERSION;

	wp_enqueue_script( 'jquery-ui-sortable' );

	wp_enqueue_style(
		'real-estate-custom-theme-admin-property-details-metabox',
		$theme_uri . '/css/admin-property-details-metabox.css',
		array(),
		$css_version
	);

	wp_enqueue_script(
		'real-estate-custom-theme-admin-property-details-metabox',
		$theme_uri . '/js/admin-property-details-metabox.js',
		array( 'jquery', 'jquery-ui-sortable' ),
		$js_version,
		true
	);

}
add_action( 'admin_enqueue_scripts', 'real_estate_custom_theme_enqueue_property_details_metabox_assets' );
