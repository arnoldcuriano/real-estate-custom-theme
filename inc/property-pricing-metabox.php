<?php
/**
 * Native property pricing metabox (accordion pricing groups).
 *
 * @package real-estate-custom-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get pricing groups and bound meta keys.
 *
 * @return array<string, array<string, string>>
 */
function real_estate_custom_theme_get_property_pricing_metabox_panels() {
	if ( function_exists( 'real_estate_custom_theme_get_property_pricing_panels_config' ) ) {
		return real_estate_custom_theme_get_property_pricing_panels_config();
	}

	return array(
		'additional_fees'     => array(
			'label'    => __( 'Additional Fees', 'real-estate-custom-theme' ),
			'meta_key' => '_rect_property_pricing_additional_fees',
		),
		'monthly_cost'        => array(
			'label'    => __( 'Monthly Cost', 'real-estate-custom-theme' ),
			'meta_key' => '_rect_property_pricing_monthly_cost',
		),
		'total_initial_cost'  => array(
			'label'    => __( 'Total Initial Cost', 'real-estate-custom-theme' ),
			'meta_key' => '_rect_property_pricing_total_initial_cost',
		),
		'monthly_expenses'    => array(
			'label'    => __( 'Monthly Expenses', 'real-estate-custom-theme' ),
			'meta_key' => '_rect_property_pricing_monthly_expenses',
		),
	);
}

/**
 * Render one pricing row in metabox UI.
 *
 * @param string $panel_key Panel key.
 * @param string $row_index Row index.
 * @param array  $row_item  Row payload.
 * @return void
 */
function real_estate_custom_theme_render_property_pricing_metabox_row( $panel_key, $row_index, $row_item ) {
	$label       = isset( $row_item['label'] ) ? (string) $row_item['label'] : '';
	$amount      = isset( $row_item['amount'] ) ? (string) $row_item['amount'] : '';
	$note        = isset( $row_item['note'] ) ? (string) $row_item['note'] : '';
	$name_prefix = sprintf( 'rect_property_pricing[%s][%s]', $panel_key, $row_index );
	?>
	<div class="rect-property-pricing-metabox__row" data-pricing-row>
		<div class="rect-property-pricing-metabox__row-head">
			<span class="rect-property-pricing-metabox__drag dashicons dashicons-move" aria-hidden="true"></span>
			<strong><?php esc_html_e( 'Row', 'real-estate-custom-theme' ); ?></strong>
			<button type="button" class="button-link-delete rect-property-pricing-metabox__remove" data-pricing-remove>
				<?php esc_html_e( 'Remove', 'real-estate-custom-theme' ); ?>
			</button>
		</div>

		<div class="rect-property-pricing-metabox__row-grid">
			<label>
				<span><?php esc_html_e( 'Label', 'real-estate-custom-theme' ); ?></span>
				<input
					type="text"
					value="<?php echo esc_attr( $label ); ?>"
					name="<?php echo esc_attr( $name_prefix . '[label]' ); ?>"
					data-pricing-field="label"
					placeholder="<?php esc_attr_e( 'Example: Property Transfer Tax', 'real-estate-custom-theme' ); ?>"
				>
			</label>

			<label>
				<span><?php esc_html_e( 'Amount / Value (Optional)', 'real-estate-custom-theme' ); ?></span>
				<input
					type="text"
					value="<?php echo esc_attr( $amount ); ?>"
					name="<?php echo esc_attr( $name_prefix . '[amount]' ); ?>"
					data-pricing-field="amount"
					placeholder="<?php esc_attr_e( 'Example: $25,000 or Varies', 'real-estate-custom-theme' ); ?>"
				>
			</label>

			<label class="rect-property-pricing-metabox__row-note">
				<span><?php esc_html_e( 'Note / Description (Optional)', 'real-estate-custom-theme' ); ?></span>
				<input
					type="text"
					value="<?php echo esc_attr( $note ); ?>"
					name="<?php echo esc_attr( $name_prefix . '[note]' ); ?>"
					data-pricing-field="note"
					placeholder="<?php esc_attr_e( 'Example: Based on local regulations', 'real-estate-custom-theme' ); ?>"
				>
			</label>
		</div>
	</div>
	<?php
}

/**
 * Register property pricing metabox.
 *
 * @return void
 */
function real_estate_custom_theme_register_property_pricing_metabox() {
	add_meta_box(
		'rect_property_pricing_details',
		__( 'Property Pricing Details', 'real-estate-custom-theme' ),
		'real_estate_custom_theme_render_property_pricing_metabox',
		'property',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'real_estate_custom_theme_register_property_pricing_metabox' );

/**
 * Render property pricing metabox.
 *
 * @param WP_Post $post Current post object.
 * @return void
 */
function real_estate_custom_theme_render_property_pricing_metabox( $post ) {
	$panels = real_estate_custom_theme_get_property_pricing_metabox_panels();

	wp_nonce_field( 'rect_property_pricing_save', 'rect_property_pricing_nonce' );
	?>
	<div class="rect-property-pricing-metabox" data-property-pricing-metabox>
		<?php foreach ( $panels as $panel_key => $panel_config ) : ?>
			<?php
			$rows = function_exists( 'real_estate_custom_theme_get_property_pricing_panel_items' )
				? real_estate_custom_theme_get_property_pricing_panel_items( $post->ID, $panel_config['meta_key'] )
				: array();
			?>
			<section class="rect-property-pricing-metabox__panel" data-pricing-group="<?php echo esc_attr( $panel_key ); ?>">
				<header class="rect-property-pricing-metabox__panel-head">
					<h3><?php echo esc_html( $panel_config['label'] ); ?></h3>
					<button type="button" class="button button-secondary" data-pricing-add>
						<?php esc_html_e( 'Add Row', 'real-estate-custom-theme' ); ?>
					</button>
				</header>

				<div class="rect-property-pricing-metabox__rows" data-pricing-rows>
					<?php foreach ( $rows as $row_index => $row_item ) : ?>
						<?php real_estate_custom_theme_render_property_pricing_metabox_row( $panel_key, (string) $row_index, $row_item ); ?>
					<?php endforeach; ?>
				</div>

				<template data-pricing-template>
					<?php
					real_estate_custom_theme_render_property_pricing_metabox_row(
						$panel_key,
						'__INDEX__',
						array(
							'label'  => '',
							'amount' => '',
							'note'   => '',
						)
					);
					?>
				</template>
			</section>
		<?php endforeach; ?>
		<p class="description">
			<?php esc_html_e( 'Add, reorder, and remove pricing rows. These values feed the single property pricing accordion.', 'real-estate-custom-theme' ); ?>
		</p>
	</div>
	<?php
}

/**
 * Save property pricing metabox values.
 *
 * @param int $post_id Current post ID.
 * @return void
 */
function real_estate_custom_theme_save_property_pricing_metabox( $post_id ) {
	if ( ! isset( $_POST['rect_property_pricing_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( (string) $_POST['rect_property_pricing_nonce'] ) );
	if ( ! wp_verify_nonce( $nonce, 'rect_property_pricing_save' ) ) {
		return;
	}

	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$raw_pricing = isset( $_POST['rect_property_pricing'] ) ? wp_unslash( $_POST['rect_property_pricing'] ) : array();
	$panels      = real_estate_custom_theme_get_property_pricing_metabox_panels();

	foreach ( $panels as $panel_key => $panel_config ) {
		$raw_panel = array();
		if ( is_array( $raw_pricing ) && isset( $raw_pricing[ $panel_key ] ) && is_array( $raw_pricing[ $panel_key ] ) ) {
			$raw_panel = $raw_pricing[ $panel_key ];
		}

		$normalized_panel = function_exists( 'real_estate_custom_theme_normalize_property_pricing_items' )
			? real_estate_custom_theme_normalize_property_pricing_items( $raw_panel )
			: array();

		if ( ! empty( $normalized_panel ) ) {
			update_post_meta( $post_id, $panel_config['meta_key'], $normalized_panel );
			continue;
		}

		delete_post_meta( $post_id, $panel_config['meta_key'] );
	}
}
add_action( 'save_post_property', 'real_estate_custom_theme_save_property_pricing_metabox' );

/**
 * Enqueue admin assets for pricing metabox.
 *
 * @param string $hook_suffix Current admin screen hook.
 * @return void
 */
function real_estate_custom_theme_enqueue_property_pricing_metabox_assets( $hook_suffix ) {
	if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'property' !== $screen->post_type ) {
		return;
	}

	$theme_dir = get_template_directory();
	$theme_uri = get_template_directory_uri();

	$css_version = file_exists( $theme_dir . '/css/admin-property-pricing-metabox.css' )
		? (string) filemtime( $theme_dir . '/css/admin-property-pricing-metabox.css' )
		: _S_VERSION;

	$js_version = file_exists( $theme_dir . '/js/admin-property-pricing-metabox.js' )
		? (string) filemtime( $theme_dir . '/js/admin-property-pricing-metabox.js' )
		: _S_VERSION;

	wp_enqueue_script( 'jquery-ui-sortable' );

	wp_enqueue_style(
		'real-estate-custom-theme-admin-property-pricing-metabox',
		$theme_uri . '/css/admin-property-pricing-metabox.css',
		array(),
		$css_version
	);

	wp_enqueue_script(
		'real-estate-custom-theme-admin-property-pricing-metabox',
		$theme_uri . '/js/admin-property-pricing-metabox.js',
		array( 'jquery', 'jquery-ui-sortable' ),
		$js_version,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'real_estate_custom_theme_enqueue_property_pricing_metabox_assets' );
