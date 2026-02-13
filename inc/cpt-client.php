<?php
/**
 * Client custom post type registration.
 *
 * @package real-estate-custom-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Client custom post type.
 *
 * Archive: /clients/
 * Single:  /client/{slug}
 *
 * @return void
 */
function real_estate_custom_theme_register_client_cpt() {
	$labels = array(
		'name'               => esc_html__( 'Clients', 'real-estate-custom-theme' ),
		'singular_name'      => esc_html__( 'Client', 'real-estate-custom-theme' ),
		'menu_name'          => esc_html__( 'Clients', 'real-estate-custom-theme' ),
		'name_admin_bar'     => esc_html__( 'Client', 'real-estate-custom-theme' ),
		'add_new'            => esc_html__( 'Add New', 'real-estate-custom-theme' ),
		'add_new_item'       => esc_html__( 'Add New Client', 'real-estate-custom-theme' ),
		'new_item'           => esc_html__( 'New Client', 'real-estate-custom-theme' ),
		'edit_item'          => esc_html__( 'Edit Client', 'real-estate-custom-theme' ),
		'view_item'          => esc_html__( 'View Client', 'real-estate-custom-theme' ),
		'all_items'          => esc_html__( 'All Clients', 'real-estate-custom-theme' ),
		'search_items'       => esc_html__( 'Search Clients', 'real-estate-custom-theme' ),
		'parent_item_colon'  => esc_html__( 'Parent Clients:', 'real-estate-custom-theme' ),
		'not_found'          => esc_html__( 'No clients found.', 'real-estate-custom-theme' ),
		'not_found_in_trash' => esc_html__( 'No clients found in Trash.', 'real-estate-custom-theme' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'show_in_rest'       => true,
		'rewrite'            => array(
			'slug'       => 'client',
			'with_front' => false,
		),
		'has_archive'        => 'clients',
		'hierarchical'       => false,
		'menu_position'      => 22,
		'menu_icon'          => 'dashicons-groups',
		'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
	);

	register_post_type( 'client', $args );
}
add_action( 'init', 'real_estate_custom_theme_register_client_cpt' );

/**
 * Flush rewrite rules once when theme is switched.
 *
 * @return void
 */
function real_estate_custom_theme_client_rewrite_flush() {
	real_estate_custom_theme_register_client_cpt();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'real_estate_custom_theme_client_rewrite_flush' );

/**
 * Warn admins about /clients slug conflicts with static pages.
 *
 * @return void
 */
function real_estate_custom_theme_clients_slug_conflict_notice() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( $screen && ! in_array( $screen->id, array( 'dashboard', 'edit-page', 'page' ), true ) ) {
		return;
	}

	$page = get_page_by_path( 'clients', OBJECT, 'page' );
	if ( ! $page ) {
		return;
	}

	$edit_link = get_edit_post_link( $page->ID );
	?>
	<div class="notice notice-warning">
		<p>
			<?php esc_html_e( 'The "Clients" custom post type archive uses /clients/. Rename the existing page slug "clients" to avoid URL conflicts.', 'real-estate-custom-theme' ); ?>
			<?php if ( $edit_link ) : ?>
				<a href="<?php echo esc_url( $edit_link ); ?>">
					<?php esc_html_e( 'Edit conflicting page', 'real-estate-custom-theme' ); ?>
				</a>
			<?php endif; ?>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'real_estate_custom_theme_clients_slug_conflict_notice' );

