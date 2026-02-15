<?php
/**
 * Property custom post type registration.
 *
 * @package real-estate-custom-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Property custom post type.
 *
 * Archive: /properties/
 * Single:  /property/{slug}
 *
 * @return void
 */
function real_estate_custom_theme_register_property_cpt() {
	$labels = array(
		'name'               => esc_html__( 'Properties', 'real-estate-custom-theme' ),
		'singular_name'      => esc_html__( 'Property', 'real-estate-custom-theme' ),
		'menu_name'          => esc_html__( 'Properties', 'real-estate-custom-theme' ),
		'name_admin_bar'     => esc_html__( 'Property', 'real-estate-custom-theme' ),
		'add_new'            => esc_html__( 'Add New', 'real-estate-custom-theme' ),
		'add_new_item'       => esc_html__( 'Add New Property', 'real-estate-custom-theme' ),
		'new_item'           => esc_html__( 'New Property', 'real-estate-custom-theme' ),
		'edit_item'          => esc_html__( 'Edit Property', 'real-estate-custom-theme' ),
		'view_item'          => esc_html__( 'View Property', 'real-estate-custom-theme' ),
		'all_items'          => esc_html__( 'All Properties', 'real-estate-custom-theme' ),
		'search_items'       => esc_html__( 'Search Properties', 'real-estate-custom-theme' ),
		'parent_item_colon'  => esc_html__( 'Parent Properties:', 'real-estate-custom-theme' ),
		'not_found'          => esc_html__( 'No properties found.', 'real-estate-custom-theme' ),
		'not_found_in_trash' => esc_html__( 'No properties found in Trash.', 'real-estate-custom-theme' ),
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
			'slug'       => 'property',
			'with_front' => false,
		),
		'has_archive'        => 'properties',
		'hierarchical'       => false,
		'menu_position'      => 20,
		'menu_icon'          => 'dashicons-building',
		'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
	);

	register_post_type( 'property', $args );
}
add_action( 'init', 'real_estate_custom_theme_register_property_cpt' );

/**
 * Register Property Location taxonomy.
 *
 * @return void
 */
function real_estate_custom_theme_register_property_location_taxonomy() {
	$labels = array(
		'name'              => esc_html__( 'Locations', 'real-estate-custom-theme' ),
		'singular_name'     => esc_html__( 'Location', 'real-estate-custom-theme' ),
		'search_items'      => esc_html__( 'Search Locations', 'real-estate-custom-theme' ),
		'all_items'         => esc_html__( 'All Locations', 'real-estate-custom-theme' ),
		'parent_item'       => esc_html__( 'Parent Location', 'real-estate-custom-theme' ),
		'parent_item_colon' => esc_html__( 'Parent Location:', 'real-estate-custom-theme' ),
		'edit_item'         => esc_html__( 'Edit Location', 'real-estate-custom-theme' ),
		'update_item'       => esc_html__( 'Update Location', 'real-estate-custom-theme' ),
		'add_new_item'      => esc_html__( 'Add New Location', 'real-estate-custom-theme' ),
		'new_item_name'     => esc_html__( 'New Location Name', 'real-estate-custom-theme' ),
		'menu_name'         => esc_html__( 'Locations', 'real-estate-custom-theme' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => 'property_location',
		'show_in_rest'      => true,
		'public'            => true,
		'rewrite'           => array(
			'slug'       => 'property-location',
			'with_front' => false,
		),
	);

	register_taxonomy( 'property_location', array( 'property' ), $args );
}
add_action( 'init', 'real_estate_custom_theme_register_property_location_taxonomy' );

/**
 * Register Property Type taxonomy.
 *
 * @return void
 */
function real_estate_custom_theme_register_property_type_taxonomy() {
	$labels = array(
		'name'              => esc_html__( 'Property Types', 'real-estate-custom-theme' ),
		'singular_name'     => esc_html__( 'Property Type', 'real-estate-custom-theme' ),
		'search_items'      => esc_html__( 'Search Property Types', 'real-estate-custom-theme' ),
		'all_items'         => esc_html__( 'All Property Types', 'real-estate-custom-theme' ),
		'parent_item'       => esc_html__( 'Parent Property Type', 'real-estate-custom-theme' ),
		'parent_item_colon' => esc_html__( 'Parent Property Type:', 'real-estate-custom-theme' ),
		'edit_item'         => esc_html__( 'Edit Property Type', 'real-estate-custom-theme' ),
		'update_item'       => esc_html__( 'Update Property Type', 'real-estate-custom-theme' ),
		'add_new_item'      => esc_html__( 'Add New Property Type', 'real-estate-custom-theme' ),
		'new_item_name'     => esc_html__( 'New Property Type Name', 'real-estate-custom-theme' ),
		'menu_name'         => esc_html__( 'Property Types', 'real-estate-custom-theme' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => 'property_type',
		'show_in_rest'      => true,
		'public'            => true,
		'rewrite'           => array(
			'slug'       => 'property-type',
			'with_front' => false,
		),
	);

	register_taxonomy( 'property_type', array( 'property' ), $args );
}
add_action( 'init', 'real_estate_custom_theme_register_property_type_taxonomy' );

/**
 * Flush rewrite rules once when theme is switched.
 *
 * @return void
 */
function real_estate_custom_theme_property_rewrite_flush() {
	real_estate_custom_theme_register_property_cpt();
	real_estate_custom_theme_register_property_location_taxonomy();
	real_estate_custom_theme_register_property_type_taxonomy();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'real_estate_custom_theme_property_rewrite_flush' );

/**
 * Warn admins about /properties slug conflicts with static pages.
 *
 * @return void
 */
function real_estate_custom_theme_properties_slug_conflict_notice() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( $screen && ! in_array( $screen->id, array( 'dashboard', 'edit-page', 'page' ), true ) ) {
		return;
	}

	$page = get_page_by_path( 'properties', OBJECT, 'page' );
	if ( ! $page ) {
		return;
	}

	$edit_link = get_edit_post_link( $page->ID );
	?>
	<div class="notice notice-warning">
		<p>
			<?php esc_html_e( 'The "Properties" custom post type archive uses /properties/. Rename the existing page slug "properties" to avoid URL conflicts.', 'real-estate-custom-theme' ); ?>
			<?php if ( $edit_link ) : ?>
				<a href="<?php echo esc_url( $edit_link ); ?>">
					<?php esc_html_e( 'Edit conflicting page', 'real-estate-custom-theme' ); ?>
				</a>
			<?php endif; ?>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'real_estate_custom_theme_properties_slug_conflict_notice' );

/**
 * Warn admins about taxonomy archive slug conflicts with static pages.
 *
 * @return void
 */
function real_estate_custom_theme_property_taxonomy_slug_conflict_notice() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( $screen && ! in_array( $screen->id, array( 'dashboard', 'edit-page', 'page' ), true ) ) {
		return;
	}

	$slugs = array(
		'property-location' => __( 'Property Location taxonomy', 'real-estate-custom-theme' ),
		'property-type'     => __( 'Property Type taxonomy', 'real-estate-custom-theme' ),
	);

	foreach ( $slugs as $slug => $taxonomy_label ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		if ( ! $page ) {
			continue;
		}

		$edit_link = get_edit_post_link( $page->ID );
		?>
		<div class="notice notice-warning">
			<p>
				<?php
				echo esc_html(
					sprintf(
						/* translators: 1: taxonomy label, 2: conflicting slug. */
						__( 'The %1$s archive uses "/%2$s/". Rename the existing page slug to avoid URL conflicts.', 'real-estate-custom-theme' ),
						$taxonomy_label,
						$slug
					)
				);
				?>
				<?php if ( $edit_link ) : ?>
					<a href="<?php echo esc_url( $edit_link ); ?>">
						<?php esc_html_e( 'Edit conflicting page', 'real-estate-custom-theme' ); ?>
					</a>
				<?php endif; ?>
			</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'real_estate_custom_theme_property_taxonomy_slug_conflict_notice' );

/**
 * Get configured price ranges used by the property archive filter UI/query.
 *
 * @return array<string, array<string, int|string>>
 */
function real_estate_custom_theme_get_property_price_ranges() {
	return array(
		'under-250k' => array(
			'label' => __( 'Under $250K', 'real-estate-custom-theme' ),
			'max'   => 250000,
		),
		'250k-500k' => array(
			'label' => __( '$250K - $500K', 'real-estate-custom-theme' ),
			'min'   => 250000,
			'max'   => 500000,
		),
		'500k-750k' => array(
			'label' => __( '$500K - $750K', 'real-estate-custom-theme' ),
			'min'   => 500000,
			'max'   => 750000,
		),
		'750k-1m' => array(
			'label' => __( '$750K - $1M', 'real-estate-custom-theme' ),
			'min'   => 750000,
			'max'   => 1000000,
		),
		'over-1m' => array(
			'label' => __( 'Over $1M', 'real-estate-custom-theme' ),
			'min'   => 1000000,
		),
	);
}

/**
 * Get configured size ranges used by the property archive filter UI/query.
 *
 * @return array<string, array<string, int|string>>
 */
function real_estate_custom_theme_get_property_size_ranges() {
	return array(
		'under-100' => array(
			'label' => __( 'Under 100 sqm', 'real-estate-custom-theme' ),
			'max'   => 100,
		),
		'100-200' => array(
			'label' => __( '100 - 200 sqm', 'real-estate-custom-theme' ),
			'min'   => 100,
			'max'   => 200,
		),
		'200-350' => array(
			'label' => __( '200 - 350 sqm', 'real-estate-custom-theme' ),
			'min'   => 200,
			'max'   => 350,
		),
		'350-500' => array(
			'label' => __( '350 - 500 sqm', 'real-estate-custom-theme' ),
			'min'   => 350,
			'max'   => 500,
		),
		'over-500' => array(
			'label' => __( 'Over 500 sqm', 'real-estate-custom-theme' ),
			'min'   => 500,
		),
	);
}

/**
 * Get configured build year ranges used by the property archive filter UI/query.
 *
 * @return array<string, array<string, int|string>>
 */
function real_estate_custom_theme_get_property_build_year_ranges() {
	return array(
		'before-2000' => array(
			'label' => __( 'Before 2000', 'real-estate-custom-theme' ),
			'max'   => 1999,
		),
		'2000-2009' => array(
			'label' => __( '2000 - 2009', 'real-estate-custom-theme' ),
			'min'   => 2000,
			'max'   => 2009,
		),
		'2010-2019' => array(
			'label' => __( '2010 - 2019', 'real-estate-custom-theme' ),
			'min'   => 2010,
			'max'   => 2019,
		),
		'2020-2029' => array(
			'label' => __( '2020 - 2029', 'real-estate-custom-theme' ),
			'min'   => 2020,
			'max'   => 2029,
		),
		'2030-plus' => array(
			'label' => __( '2030+', 'real-estate-custom-theme' ),
			'min'   => 2030,
		),
	);
}

/**
 * Read and sanitize current property archive filter state.
 *
 * @return array<string, string>
 */
function real_estate_custom_theme_get_property_archive_filter_state() {
	$state = array(
		's'                => '',
		'location'         => '',
		'type'             => '',
		'price_range'      => '',
		'size_range'       => '',
		'build_year_range' => '',
	);

	$param_keys = array_keys( $state );

	foreach ( $param_keys as $param_key ) {
		$value = '';
		if ( '' !== (string) get_query_var( $param_key ) ) {
			$value = (string) get_query_var( $param_key );
		} elseif ( isset( $_GET[ $param_key ] ) ) {
			$value = (string) wp_unslash( $_GET[ $param_key ] );
		}

		$value = trim( $value );
		if ( '' === $value ) {
			continue;
		}

		switch ( $param_key ) {
			case 's':
				$state[ $param_key ] = sanitize_text_field( $value );
				break;

			case 'location':
			case 'type':
				$state[ $param_key ] = sanitize_title( $value );
				break;

			case 'price_range':
				$ranges = real_estate_custom_theme_get_property_price_ranges();
				$range_key = sanitize_title( $value );
				if ( isset( $ranges[ $range_key ] ) ) {
					$state[ $param_key ] = $range_key;
				}
				break;

			case 'size_range':
				$ranges = real_estate_custom_theme_get_property_size_ranges();
				$range_key = sanitize_title( $value );
				if ( isset( $ranges[ $range_key ] ) ) {
					$state[ $param_key ] = $range_key;
				}
				break;

			case 'build_year_range':
				$ranges = real_estate_custom_theme_get_property_build_year_ranges();
				$range_key = sanitize_title( $value );
				if ( isset( $ranges[ $range_key ] ) ) {
					$state[ $param_key ] = $range_key;
				}
				break;
		}
	}

	return $state;
}

/**
 * Register property archive filter query vars.
 *
 * @param array<int, string> $vars Existing vars.
 *
 * @return array<int, string>
 */
function real_estate_custom_theme_property_filter_query_vars( $vars ) {
	$vars[] = 'location';
	$vars[] = 'type';
	$vars[] = 'price_range';
	$vars[] = 'size_range';
	$vars[] = 'build_year_range';

	return $vars;
}
add_filter( 'query_vars', 'real_estate_custom_theme_property_filter_query_vars' );

/**
 * Convert a range definition into a numeric meta query clause.
 *
 * @param string                  $meta_key Meta key.
 * @param array<string, int>      $range    Range definition.
 *
 * @return array<string, mixed>
 */
function real_estate_custom_theme_get_numeric_meta_clause_from_range( $meta_key, $range ) {
	$has_min = isset( $range['min'] );
	$has_max = isset( $range['max'] );

	if ( $has_min && $has_max ) {
		return array(
			'key'     => $meta_key,
			'value'   => array( (int) $range['min'], (int) $range['max'] ),
			'compare' => 'BETWEEN',
			'type'    => 'NUMERIC',
		);
	}

	if ( $has_min ) {
		return array(
			'key'     => $meta_key,
			'value'   => (int) $range['min'],
			'compare' => '>=',
			'type'    => 'NUMERIC',
		);
	}

	return array(
		'key'     => $meta_key,
		'value'   => (int) $range['max'],
		'compare' => '<=',
		'type'    => 'NUMERIC',
	);
}

/**
 * Apply archive filters for property taxonomy and numeric ranges.
 *
 * @param WP_Query $query Query instance.
 *
 * @return void
 */
function real_estate_custom_theme_filter_property_archive_query( $query ) {
	if ( ! $query instanceof WP_Query ) {
		return;
	}

	$post_type = $query->get( 'post_type' );
	if ( is_array( $post_type ) ) {
		$post_type = reset( $post_type );
	}

	$is_property_search = $query->is_search() && 'property' === (string) $post_type;

	if ( is_admin() || ! $query->is_main_query() || ( ! $query->is_post_type_archive( 'property' ) && ! $is_property_search ) ) {
		return;
	}

	if ( $is_property_search ) {
		$query->set( 'post_type', 'property' );
	}

	$filter_state = real_estate_custom_theme_get_property_archive_filter_state();

	$tax_query = (array) $query->get( 'tax_query' );

	if ( '' !== $filter_state['location'] ) {
		$tax_query[] = array(
			'taxonomy' => 'property_location',
			'field'    => 'slug',
			'terms'    => $filter_state['location'],
		);
	}

	if ( '' !== $filter_state['type'] ) {
		$tax_query[] = array(
			'taxonomy' => 'property_type',
			'field'    => 'slug',
			'terms'    => $filter_state['type'],
		);
	}

	if ( count( $tax_query ) > 1 && ! isset( $tax_query['relation'] ) ) {
		$tax_query['relation'] = 'AND';
	}

	if ( ! empty( $tax_query ) ) {
		$query->set( 'tax_query', $tax_query );
	}

	$meta_query = (array) $query->get( 'meta_query' );

	if ( '' !== $filter_state['price_range'] ) {
		$price_ranges = real_estate_custom_theme_get_property_price_ranges();
		if ( isset( $price_ranges[ $filter_state['price_range'] ] ) ) {
			$meta_query[] = real_estate_custom_theme_get_numeric_meta_clause_from_range( 'price', $price_ranges[ $filter_state['price_range'] ] );
		}
	}

	if ( '' !== $filter_state['size_range'] ) {
		$size_ranges = real_estate_custom_theme_get_property_size_ranges();
		if ( isset( $size_ranges[ $filter_state['size_range'] ] ) ) {
			$meta_query[] = real_estate_custom_theme_get_numeric_meta_clause_from_range( 'size_sqm', $size_ranges[ $filter_state['size_range'] ] );
		}
	}

	if ( '' !== $filter_state['build_year_range'] ) {
		$build_year_ranges = real_estate_custom_theme_get_property_build_year_ranges();
		if ( isset( $build_year_ranges[ $filter_state['build_year_range'] ] ) ) {
			$meta_query[] = real_estate_custom_theme_get_numeric_meta_clause_from_range( 'build_year', $build_year_ranges[ $filter_state['build_year_range'] ] );
		}
	}

	if ( count( $meta_query ) > 1 && ! isset( $meta_query['relation'] ) ) {
		$meta_query['relation'] = 'AND';
	}

	if ( ! empty( $meta_query ) ) {
		$query->set( 'meta_query', $meta_query );
	}
}
add_action( 'pre_get_posts', 'real_estate_custom_theme_filter_property_archive_query' );

/**
 * Backfill legacy property_type meta values into property_type taxonomy terms.
 *
 * @return void
 */
function real_estate_custom_theme_backfill_property_type_terms_once() {
	$backfill_option_key = 'real_estate_custom_theme_property_type_backfilled';

	if ( get_option( $backfill_option_key ) ) {
		return;
	}

	$post_ids = get_posts(
		array(
			'post_type'      => 'property',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_query'     => array(
				array(
					'key'     => 'property_type',
					'compare' => 'EXISTS',
				),
				array(
					'key'     => 'property_type',
					'value'   => '',
					'compare' => '!=',
				),
			),
		)
	);

	if ( empty( $post_ids ) ) {
		update_option( $backfill_option_key, gmdate( 'c' ), false );
		return;
	}

	foreach ( $post_ids as $post_id ) {
		$legacy_property_type = trim( (string) get_post_meta( (int) $post_id, 'property_type', true ) );
		if ( '' === $legacy_property_type ) {
			continue;
		}

		$raw_terms = preg_split( '/[,;|]+/', $legacy_property_type );
		if ( empty( $raw_terms ) ) {
			$raw_terms = array( $legacy_property_type );
		}

		$term_ids = array();
		foreach ( $raw_terms as $raw_term ) {
			$term_name = trim( sanitize_text_field( (string) $raw_term ) );
			if ( '' === $term_name ) {
				continue;
			}

			$term_data = term_exists( $term_name, 'property_type' );
			if ( ! $term_data ) {
				$term_data = wp_insert_term( $term_name, 'property_type' );
			}

			if ( is_wp_error( $term_data ) ) {
				continue;
			}

			$term_id = is_array( $term_data ) ? (int) $term_data['term_id'] : (int) $term_data;
			if ( $term_id > 0 ) {
				$term_ids[] = $term_id;
			}
		}

		if ( ! empty( $term_ids ) ) {
			wp_set_object_terms( (int) $post_id, array_values( array_unique( $term_ids ) ), 'property_type', false );
		}
	}

	update_option( $backfill_option_key, gmdate( 'c' ), false );
}
add_action( 'init', 'real_estate_custom_theme_backfill_property_type_terms_once', 40 );
