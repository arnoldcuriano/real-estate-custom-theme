<?php
/**
 * FAQ custom post type and taxonomy registration.
 *
 * @package real-estate-custom-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the FAQ custom post type.
 *
 * Archive: /faqs/
 * Single:  /faq/{slug}
 *
 * @return void
 */
function real_estate_custom_theme_register_faq_cpt() {
	$labels = array(
		'name'               => esc_html__( 'FAQs', 'real-estate-custom-theme' ),
		'singular_name'      => esc_html__( 'FAQ', 'real-estate-custom-theme' ),
		'menu_name'          => esc_html__( 'FAQs', 'real-estate-custom-theme' ),
		'name_admin_bar'     => esc_html__( 'FAQ', 'real-estate-custom-theme' ),
		'add_new'            => esc_html__( 'Add New', 'real-estate-custom-theme' ),
		'add_new_item'       => esc_html__( 'Add New FAQ', 'real-estate-custom-theme' ),
		'new_item'           => esc_html__( 'New FAQ', 'real-estate-custom-theme' ),
		'edit_item'          => esc_html__( 'Edit FAQ', 'real-estate-custom-theme' ),
		'view_item'          => esc_html__( 'View FAQ', 'real-estate-custom-theme' ),
		'all_items'          => esc_html__( 'All FAQs', 'real-estate-custom-theme' ),
		'search_items'       => esc_html__( 'Search FAQs', 'real-estate-custom-theme' ),
		'parent_item_colon'  => esc_html__( 'Parent FAQs:', 'real-estate-custom-theme' ),
		'not_found'          => esc_html__( 'No FAQs found.', 'real-estate-custom-theme' ),
		'not_found_in_trash' => esc_html__( 'No FAQs found in Trash.', 'real-estate-custom-theme' ),
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
			'slug'       => 'faq',
			'with_front' => false,
		),
		'has_archive'        => 'faqs',
		'hierarchical'       => false,
		'menu_position'      => 22,
		'menu_icon'          => 'dashicons-editor-help',
		'supports'           => array( 'title', 'editor', 'excerpt' ),
	);

	register_post_type( 'faq', $args );
}
add_action( 'init', 'real_estate_custom_theme_register_faq_cpt' );

/**
 * Register FAQ Category taxonomy.
 *
 * @return void
 */
function real_estate_custom_theme_register_faq_category_taxonomy() {
	$labels = array(
		'name'              => esc_html__( 'FAQ Categories', 'real-estate-custom-theme' ),
		'singular_name'     => esc_html__( 'FAQ Category', 'real-estate-custom-theme' ),
		'search_items'      => esc_html__( 'Search FAQ Categories', 'real-estate-custom-theme' ),
		'all_items'         => esc_html__( 'All FAQ Categories', 'real-estate-custom-theme' ),
		'parent_item'       => esc_html__( 'Parent FAQ Category', 'real-estate-custom-theme' ),
		'parent_item_colon' => esc_html__( 'Parent FAQ Category:', 'real-estate-custom-theme' ),
		'edit_item'         => esc_html__( 'Edit FAQ Category', 'real-estate-custom-theme' ),
		'update_item'       => esc_html__( 'Update FAQ Category', 'real-estate-custom-theme' ),
		'add_new_item'      => esc_html__( 'Add New FAQ Category', 'real-estate-custom-theme' ),
		'new_item_name'     => esc_html__( 'New FAQ Category Name', 'real-estate-custom-theme' ),
		'menu_name'         => esc_html__( 'FAQ Category', 'real-estate-custom-theme' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => 'faq_category',
		'show_in_rest'      => true,
		'public'            => true,
		'rewrite'           => array(
			'slug'       => 'faq-category',
			'with_front' => false,
		),
	);

	register_taxonomy( 'faq_category', array( 'faq' ), $args );
}
add_action( 'init', 'real_estate_custom_theme_register_faq_category_taxonomy' );

/**
 * Flush rewrite rules once when theme is switched.
 *
 * @return void
 */
function real_estate_custom_theme_faq_rewrite_flush() {
	real_estate_custom_theme_register_faq_cpt();
	real_estate_custom_theme_register_faq_category_taxonomy();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'real_estate_custom_theme_faq_rewrite_flush' );

/**
 * Warn admins about /faqs slug conflicts with static pages.
 *
 * @return void
 */
function real_estate_custom_theme_faqs_slug_conflict_notice() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( $screen && ! in_array( $screen->id, array( 'dashboard', 'edit-page', 'page' ), true ) ) {
		return;
	}

	$page = get_page_by_path( 'faqs', OBJECT, 'page' );
	if ( ! $page ) {
		return;
	}

	$edit_link = get_edit_post_link( $page->ID );
	?>
	<div class="notice notice-warning">
		<p>
			<?php esc_html_e( 'The "FAQs" custom post type archive uses /faqs/. Rename the existing page slug "faqs" to avoid URL conflicts.', 'real-estate-custom-theme' ); ?>
			<?php if ( $edit_link ) : ?>
				<a href="<?php echo esc_url( $edit_link ); ?>">
					<?php esc_html_e( 'Edit conflicting page', 'real-estate-custom-theme' ); ?>
				</a>
			<?php endif; ?>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'real_estate_custom_theme_faqs_slug_conflict_notice' );

/**
 * Filter FAQ archive by FAQ Category query arg.
 *
 * @param WP_Query $query Query instance.
 *
 * @return void
 */
function real_estate_custom_theme_filter_faq_archive_by_category( $query ) {
	if ( ! $query instanceof WP_Query ) {
		return;
	}

	if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'faq' ) ) {
		return;
	}

	$faq_category = (string) $query->get( 'faq_category' );
	if ( '' === $faq_category && isset( $_GET['faq_category'] ) ) {
		$faq_category = sanitize_text_field( wp_unslash( $_GET['faq_category'] ) );
	}

	$faq_category = sanitize_title( $faq_category );
	if ( '' === $faq_category || 'all' === $faq_category ) {
		return;
	}

	$tax_query = (array) $query->get( 'tax_query' );
	$tax_query[] = array(
		'taxonomy' => 'faq_category',
		'field'    => 'slug',
		'terms'    => $faq_category,
	);

	if ( count( $tax_query ) > 1 && ! isset( $tax_query['relation'] ) ) {
		$tax_query['relation'] = 'AND';
	}

	$query->set( 'tax_query', $tax_query );
}
add_action( 'pre_get_posts', 'real_estate_custom_theme_filter_faq_archive_by_category' );
