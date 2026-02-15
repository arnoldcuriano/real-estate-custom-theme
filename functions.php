<?php

/**
 * real-estate-custom-theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package real-estate-custom-theme
 */

if (! defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function real_estate_custom_theme_setup() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'admin-bar',
		array(
			'callback' => '__return_false',
		)
	);
}
add_action( 'after_setup_theme', 'real_estate_custom_theme_setup' );

/**
 * Disable core admin-bar bump styles in favor of theme-controlled offsets.
 *
 * @return void
 */
function real_estate_custom_theme_disable_core_admin_bar_bump() {
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_admin_bar_bump_styles' );
	remove_action( 'wp_head', '_admin_bar_bump_cb' );
}
add_action( 'wp', 'real_estate_custom_theme_disable_core_admin_bar_bump', 99 );

/**
 * Output deterministic admin-bar offset rules after all head styles.
 *
 * @return void
 */
function real_estate_custom_theme_admin_bar_offset_css() {
	if ( ! is_admin_bar_showing() ) {
		return;
	}
	?>
	<style media="screen">
		html {
			margin-top: 0 !important;
		}
		body.admin-bar {
			padding-top: 32px;
		}
		@media screen and (max-width: 782px) {
			body.admin-bar {
				padding-top: 46px;
			}
		}
	</style>
	<?php
}
add_action( 'wp_head', 'real_estate_custom_theme_admin_bar_offset_css', 999 );

/**
 * Get About Us page URL with permalink-safe fallback.
 *
 * @return string
 */
function real_estate_custom_theme_get_about_page_url() {
	$about_page = get_page_by_path( 'about-us' );

	if ( $about_page instanceof WP_Post ) {
		return get_permalink( $about_page );
	}

	return home_url( '/about-us/' );
}

/**
 * Get Services page URL with permalink-safe fallback.
 *
 * @return string
 */
function real_estate_custom_theme_get_services_page_url() {
	$services_page = get_page_by_path( 'services' );

	if ( $services_page instanceof WP_Post ) {
		return get_permalink( $services_page );
	}

	return home_url( '/services/' );
}

/**
 * Get Contact Us page URL with permalink-safe fallback.
 *
 * @return string
 */
function real_estate_custom_theme_get_contact_page_url() {
	$contact_page = get_page_by_path( 'contact-us' );

	if ( $contact_page instanceof WP_Post ) {
		return get_permalink( $contact_page );
	}

	return home_url( '/contact-us/' );
}

/**
 * Get Property archive URL with permalink-safe fallback.
 *
 * @return string
 */
function real_estate_custom_theme_get_properties_archive_url() {
	$archive_url = get_post_type_archive_link( 'property' );

	if ( ! empty( $archive_url ) ) {
		return $archive_url;
	}

	return home_url( '/properties/' );
}

/**
 * Resolve Contact Form 7 shortcode for the Properties inquiry form.
 *
 * Form title is intentionally fixed to keep theme integration stable:
 * - Property Inquiry Form
 *
 * @return string
 */
function real_estate_custom_theme_get_property_inquiry_form_shortcode() {
	if ( ! shortcode_exists( 'contact-form-7' ) ) {
		return '';
	}

	$form_title = 'Property Inquiry Form';

	$forms = get_posts(
		array(
			'post_type'      => 'wpcf7_contact_form',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'title'          => $form_title,
			'orderby'        => 'ID',
			'order'          => 'DESC',
		)
	);

	// Fallback for environments where exact-title query var is unavailable.
	if ( empty( $forms ) ) {
		$candidate_forms = get_posts(
			array(
				'post_type'      => 'wpcf7_contact_form',
				'post_status'    => 'publish',
				'posts_per_page' => 20,
				's'              => $form_title,
				'orderby'        => 'ID',
				'order'          => 'DESC',
			)
		);

		foreach ( $candidate_forms as $candidate_form ) {
			if ( 0 === strcasecmp( trim( (string) $candidate_form->post_title ), $form_title ) ) {
				$forms = array( $candidate_form );
				break;
			}
		}
	}

	if ( empty( $forms ) || empty( $forms[0]->ID ) ) {
		return '';
	}

	return sprintf( '[contact-form-7 id="%d"]', absint( $forms[0]->ID ) );
}

/**
 * Normalize invalid acceptance tag syntax for the Property Inquiry CF7 form.
 *
 * CF7 acceptance tags do not support the required asterisk variant.
 * If `[acceptance* ...]` is present in the form template, CF7 renders it as raw
 * text. This normalizer rewrites it to `[acceptance ...]` before CF7 scans tags.
 *
 * @param array                $properties   Contact form properties.
 * @param WPCF7_ContactForm    $contact_form Contact form object.
 * @return array
 */
function real_estate_custom_theme_normalize_property_inquiry_acceptance_tag( $properties, $contact_form ) {
	if ( empty( $properties['form'] ) || ! is_string( $properties['form'] ) ) {
		return $properties;
	}

	if ( ! is_object( $contact_form ) || ! method_exists( $contact_form, 'title' ) ) {
		return $properties;
	}

	$form_title = trim( (string) $contact_form->title() );
	if ( 0 !== strcasecmp( $form_title, 'Property Inquiry Form' ) ) {
		return $properties;
	}

	if ( false === strpos( $properties['form'], '[acceptance*' ) ) {
		return $properties;
	}

	$normalized_form = preg_replace( '/\[acceptance\*\s+([^\]]+)\]/i', '[acceptance $1]', $properties['form'] );

	if ( is_string( $normalized_form ) && '' !== $normalized_form ) {
		$properties['form'] = $normalized_form;
	}

	return $properties;
}
add_filter( 'wpcf7_contact_form_properties', 'real_estate_custom_theme_normalize_property_inquiry_acceptance_tag', 10, 2 );

/**
 * Determine whether current route should use the front-style header shell.
 *
 * @return bool
 */
function real_estate_custom_theme_is_front_header_context() {
	return is_front_page()
		|| is_page( array( 'about-us', 'services', 'contact-us' ) )
		|| is_post_type_archive( 'property' )
		|| is_singular( 'property' );
}

/**
 * Create missing navigation placeholder pages.
 *
 * @return void
 */
function real_estate_custom_theme_get_route_page_ids_by_slug( $slug ) {
	global $wpdb;

	$ids = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT ID FROM {$wpdb->posts} WHERE post_type = 'page' AND post_status NOT IN ( 'trash', 'auto-draft', 'inherit' ) AND post_name = %s ORDER BY ID ASC",
			$slug
		)
	);

	return array_map( 'intval', $ids );
}

/**
 * Show one-time admin notice when duplicate route pages are repaired.
 *
 * @return void
 */
function real_estate_custom_theme_route_slug_conflicts_admin_notice() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$conflicts = get_transient( 'real_estate_custom_theme_route_slug_conflicts' );
	if ( ! is_array( $conflicts ) || empty( $conflicts ) ) {
		return;
	}

	delete_transient( 'real_estate_custom_theme_route_slug_conflicts' );

	echo '<div class="notice notice-warning"><p>';
	echo esc_html__( 'Route page slug conflicts were detected and repaired automatically.', 'real-estate-custom-theme' );
	echo '</p><ul style="margin:0.4rem 0 0 1.2rem;list-style:disc;">';

	foreach ( $conflicts as $slug => $conflict ) {
		$canonical_id = isset( $conflict['canonical'] ) ? (int) $conflict['canonical'] : 0;
		$duplicates   = isset( $conflict['duplicates'] ) && is_array( $conflict['duplicates'] ) ? implode( ', ', array_map( 'intval', $conflict['duplicates'] ) ) : '';

		echo '<li>';
		echo esc_html(
			sprintf(
				/* translators: 1: route slug, 2: canonical page ID, 3: duplicate page IDs. */
				__( 'Slug "%1$s": canonical page #%2$d kept, duplicates re-slugged (%3$s).', 'real-estate-custom-theme' ),
				$slug,
				$canonical_id,
				$duplicates
			)
		);
		echo '</li>';
	}

	echo '</ul></div>';
}
add_action( 'admin_notices', 'real_estate_custom_theme_route_slug_conflicts_admin_notice' );

/**
 * Create or repair required route pages used by template-controlled shells.
 *
 * @return void
 */
function real_estate_custom_theme_seed_navigation_placeholder_pages() {
	$required_pages = array(
		'about-us'   => esc_html__( 'About Us', 'real-estate-custom-theme' ),
		'services'   => esc_html__( 'Services', 'real-estate-custom-theme' ),
		'contact-us' => esc_html__( 'Contact Us', 'real-estate-custom-theme' ),
	);
	$slug_conflicts = array();

	foreach ( $required_pages as $slug => $title ) {
		$page_ids = real_estate_custom_theme_get_route_page_ids_by_slug( $slug );

		if ( empty( $page_ids ) ) {
			$inserted_page_id = wp_insert_post(
				array(
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_title'   => $title,
					'post_name'    => $slug,
					'post_content' => '',
				)
			);

			if ( ! is_wp_error( $inserted_page_id ) && $inserted_page_id > 0 ) {
				$page_ids = array( (int) $inserted_page_id );
			}
		}

		if ( ! empty( $page_ids ) ) {
			$canonical_id = (int) $page_ids[0];
			if ( 'publish' !== get_post_status( $canonical_id ) ) {
				wp_update_post(
					array(
						'ID'          => $canonical_id,
						'post_status' => 'publish',
					)
				);
			}
		}

		if ( count( $page_ids ) <= 1 ) {
			continue;
		}

		$canonical_id  = (int) $page_ids[0];
		$duplicate_ids = array_slice( $page_ids, 1 );

		foreach ( $duplicate_ids as $duplicate_id ) {
			$duplicate_id = (int) $duplicate_id;
			wp_update_post(
				array(
					'ID'        => $duplicate_id,
					'post_name' => sanitize_title( $slug . '-duplicate-' . $duplicate_id ),
				)
			);
		}

		$slug_conflicts[ $slug ] = array(
			'canonical'  => $canonical_id,
			'duplicates' => array_map( 'intval', $duplicate_ids ),
		);

		error_log(
			sprintf(
				'[%s] Repaired route slug conflict for "%s": canonical page #%d, duplicates re-slugged (%s).',
				__FUNCTION__,
				$slug,
				$canonical_id,
				implode( ',', array_map( 'intval', $duplicate_ids ) )
			)
		);
	}

	if ( ! empty( $slug_conflicts ) ) {
		set_transient( 'real_estate_custom_theme_route_slug_conflicts', $slug_conflicts, DAY_IN_SECONDS );
	}
}
add_action( 'after_switch_theme', 'real_estate_custom_theme_seed_navigation_placeholder_pages', 20 );

/**
 * Ensure placeholder pages exist on already-active installs.
 *
 * @return void
 */
function real_estate_custom_theme_ensure_navigation_placeholder_pages() {
	real_estate_custom_theme_seed_navigation_placeholder_pages();
}
add_action( 'init', 'real_estate_custom_theme_ensure_navigation_placeholder_pages', 20 );

/**
 * Force route shell templates so editor template changes cannot cause layout regressions.
 *
 * @param string $template Path to the template file.
 * @return string
 */
function real_estate_custom_theme_force_route_shell_templates( $template ) {
	if ( is_admin() ) {
		return $template;
	}

	$template_map = array(
		'about-us'   => 'page-about-us.php',
		'services'   => 'page-services.php',
		'contact-us' => 'page-contact-us.php',
	);

	foreach ( $template_map as $slug => $template_file ) {
		if ( ! is_page( $slug ) ) {
			continue;
		}

		$forced_template = get_theme_file_path( $template_file );
		if ( file_exists( $forced_template ) ) {
			return $forced_template;
		}

		break;
	}

	return $template;
}
add_filter( 'template_include', 'real_estate_custom_theme_force_route_shell_templates', 99 );

/**
 * Flush rewrite rules once per theme version to ensure archive/page routes resolve.
 *
 * @return void
 */
function real_estate_custom_theme_maybe_flush_rewrite_rules() {
	$flushed_version = get_option( 'real_estate_custom_theme_rewrite_flushed_version', '' );
	if ( _S_VERSION === $flushed_version ) {
		return;
	}

	flush_rewrite_rules( false );
	update_option( 'real_estate_custom_theme_rewrite_flushed_version', _S_VERSION, true );
}
add_action( 'init', 'real_estate_custom_theme_maybe_flush_rewrite_rules', 999 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function real_estate_custom_theme_widgets_init()
{
	register_sidebar(
		array(
			'name'          => esc_html__('Sidebar', 'real-estate-custom-theme'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here.', 'real-estate-custom-theme'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action('widgets_init', 'real_estate_custom_theme_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function real_estate_custom_theme_scripts()
{
	$theme_dir = get_template_directory();
	$theme_uri = get_template_directory_uri();

	$style_version      = file_exists( $theme_dir . '/style.css' ) ? (string) filemtime( $theme_dir . '/style.css' ) : _S_VERSION;
	$header_style_version = file_exists( $theme_dir . '/css/header.css' ) ? (string) filemtime( $theme_dir . '/css/header.css' ) : _S_VERSION;
	$home_style_version = file_exists( $theme_dir . '/css/home.css' ) ? (string) filemtime( $theme_dir . '/css/home.css' ) : _S_VERSION;
	$about_style_version = file_exists( $theme_dir . '/css/about.css' ) ? (string) filemtime( $theme_dir . '/css/about.css' ) : _S_VERSION;
	$home_js_version    = file_exists( $theme_dir . '/js/home.js' ) ? (string) filemtime( $theme_dir . '/js/home.js' ) : _S_VERSION;
	$about_js_version   = file_exists( $theme_dir . '/js/about.js' ) ? (string) filemtime( $theme_dir . '/js/about.js' ) : _S_VERSION;
	$property_filters_js_version = file_exists( $theme_dir . '/js/property-filters.js' ) ? (string) filemtime( $theme_dir . '/js/property-filters.js' ) : _S_VERSION;
	$property_inquiry_form_js_version = file_exists( $theme_dir . '/js/property-inquiry-form.js' ) ? (string) filemtime( $theme_dir . '/js/property-inquiry-form.js' ) : _S_VERSION;
	$nav_js_version     = file_exists( $theme_dir . '/js/navigation.js' ) ? (string) filemtime( $theme_dir . '/js/navigation.js' ) : _S_VERSION;
	$stats_js_version   = file_exists( $theme_dir . '/js/stats-counter.js' ) ? (string) filemtime( $theme_dir . '/js/stats-counter.js' ) : _S_VERSION;

	wp_enqueue_style(
		'real-estate-custom-theme-fonts',
		'https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap',
		array(),
		null
	);

	wp_enqueue_style('real-estate-custom-theme-style', get_stylesheet_uri(), array(), $style_version);
	wp_style_add_data('real-estate-custom-theme-style', 'rtl', 'replace');

	wp_enqueue_style(
		'real-estate-custom-theme-header',
		$theme_uri . '/css/header.css',
		array( 'real-estate-custom-theme-style' ),
		$header_style_version
	);

	$should_load_home_experience_assets = is_front_page() || is_page( 'services' ) || is_post_type_archive( 'property' );

	if ( is_page( 'about-us' ) ) {
		wp_enqueue_style(
			'real-estate-custom-theme-about',
			$theme_uri . '/css/about.css',
			array( 'real-estate-custom-theme-style', 'real-estate-custom-theme-header' ),
			$about_style_version
		);

		wp_enqueue_script(
			'real-estate-custom-theme-about-script',
			$theme_uri . '/js/about.js',
			array(),
			$about_js_version,
			true
		);
	}

	if ( $should_load_home_experience_assets ) {
		wp_enqueue_style(
			'real-estate-custom-theme-home',
			$theme_uri . '/css/home.css',
			array( 'real-estate-custom-theme-style' ),
			$home_style_version
		);

		wp_enqueue_script(
			'real-estate-custom-theme-home-script',
			$theme_uri . '/js/home.js',
			array(),
			$home_js_version,
			true
		);

		if ( is_front_page() ) {
			wp_enqueue_script(
				'real-estate-custom-theme-alpine',
				$theme_uri . '/js/vendor/alpine.min.js',
				array( 'real-estate-custom-theme-home-script' ),
				'3.14.8',
				true
			);
			wp_script_add_data( 'real-estate-custom-theme-alpine', 'defer', true );
		}
	}

	if ( is_post_type_archive( 'property' ) ) {
		wp_enqueue_script(
			'real-estate-custom-theme-property-filters',
			$theme_uri . '/js/property-filters.js',
			array(),
			$property_filters_js_version,
			true
		);

		wp_enqueue_script(
			'real-estate-custom-theme-property-inquiry-form',
			$theme_uri . '/js/property-inquiry-form.js',
			array(),
			$property_inquiry_form_js_version,
			true
		);
	}

	wp_enqueue_script('real-estate-custom-theme-navigation', $theme_uri . '/js/navigation.js', array(), $nav_js_version, true);

	if ( is_front_page() || is_page( 'about-us' ) ) {
		wp_enqueue_script(
			'real-estate-custom-theme-stats-counter',
			$theme_uri . '/js/stats-counter.js',
			array(),
			$stats_js_version,
			true
		);
	}

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'real_estate_custom_theme_scripts');

/**
 * Get the Symbol asset URL used for branding/favicon.
 *
 * @return string
 */
function real_estate_custom_theme_get_symbol_asset_url() {
	$logo_candidates = array(
		'Symbol.png',
		'Symbol.svg',
		'Symbol.webp',
		'Symbol.jpg',
		'Symbol.jpeg',
		'symbol.png',
		'symbol.svg',
		'symbol.webp',
		'symbol.jpg',
		'symbol.jpeg',
	);

	foreach ( $logo_candidates as $logo_candidate ) {
		$logo_asset_path = get_template_directory() . '/assets/images/home/' . $logo_candidate;
		if ( file_exists( $logo_asset_path ) ) {
			return get_template_directory_uri() . '/assets/images/home/' . $logo_candidate;
		}
	}

	return '';
}

/**
 * Get fallback image URL for property cards.
 *
 * @return string
 */
function real_estate_custom_theme_get_property_fallback_image_url() {
	$image_candidates = array(
		'featured-property-placeholder.png',
		'featured-property-placeholder.webp',
		'featured-property-placeholder.jpg',
		'hero-building.png',
	);

	foreach ( $image_candidates as $candidate ) {
		$asset_path = get_template_directory() . '/assets/images/home/' . $candidate;
		if ( file_exists( $asset_path ) ) {
			return get_template_directory_uri() . '/assets/images/home/' . $candidate;
		}
	}

	return '';
}

/**
 * Output theme favicon when Site Icon is not configured in Customizer.
 *
 * @return void
 */
function real_estate_custom_theme_output_favicon() {
	if ( function_exists( 'has_site_icon' ) && has_site_icon() ) {
		return;
	}

	$symbol_logo_url = real_estate_custom_theme_get_symbol_asset_url();
	if ( empty( $symbol_logo_url ) ) {
		return;
	}

	echo '<link rel="icon" href="' . esc_url( $symbol_logo_url ) . '" type="image/png">';
	echo '<link rel="apple-touch-icon" href="' . esc_url( $symbol_logo_url ) . '">';
}
add_action( 'wp_head', 'real_estate_custom_theme_output_favicon' );
add_action( 'admin_head', 'real_estate_custom_theme_output_favicon' );
add_action( 'login_head', 'real_estate_custom_theme_output_favicon' );

/**
 * Property helpers (meta icons and card excerpt truncation).
 */
require get_template_directory() . '/inc/property-helpers.php';

/**
 * Register Property custom post type.
 */
require get_template_directory() . '/inc/cpt-property.php';

/**
 * Register Testimonial custom post type.
 */
require get_template_directory() . '/inc/cpt-testimonial.php';

/**
 * Register Client custom post type.
 */
require get_template_directory() . '/inc/cpt-client.php';

/**
 * Register Team Member custom post type.
 */
require get_template_directory() . '/inc/cpt-team-member.php';

/**
 * Register FAQ custom post type and taxonomy.
 */
require get_template_directory() . '/inc/cpt-faq.php';

/**
 * Register local ACF field groups for property content.
 */
require get_template_directory() . '/inc/acf-fields-properties.php';

/**
 * Register local ACF field groups for testimonial content.
 */
require get_template_directory() . '/inc/acf-fields-testimonials.php';

/**
 * Register local ACF field groups for client content.
 */
require get_template_directory() . '/inc/acf-fields-clients.php';

/**
 * Register local ACF field groups for team member content.
 */
require get_template_directory() . '/inc/acf-fields-team-members.php';

/**
 * Register local ACF field groups for FAQ content.
 */
require get_template_directory() . '/inc/acf-fields-faq.php';

/**
 * Register local ACF field groups for About page content.
 */
require get_template_directory() . '/inc/acf-fields-about.php';

/**
 * Register local ACF field groups for Services page content.
 */
require get_template_directory() . '/inc/acf-fields-services.php';

/**
 * Testimonial helpers.
 */
require get_template_directory() . '/inc/testimonial-helpers.php';

/**
 * Client helpers.
 */
require get_template_directory() . '/inc/client-helpers.php';

/**
 * Team Member helpers.
 */
require get_template_directory() . '/inc/team-member-helpers.php';

/**
 * FAQ helpers.
 */
require get_template_directory() . '/inc/faq-helpers.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}
