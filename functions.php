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
}
add_action( 'after_setup_theme', 'real_estate_custom_theme_setup' );


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
	$home_js_version    = file_exists( $theme_dir . '/js/home.js' ) ? (string) filemtime( $theme_dir . '/js/home.js' ) : _S_VERSION;
	$nav_js_version     = file_exists( $theme_dir . '/js/navigation.js' ) ? (string) filemtime( $theme_dir . '/js/navigation.js' ) : _S_VERSION;

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

	if ( is_front_page() ) {
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

		wp_enqueue_script(
			'real-estate-custom-theme-alpine',
			$theme_uri . '/js/vendor/alpine.min.js',
			array( 'real-estate-custom-theme-home-script' ),
			'3.14.8',
			true
		);
		wp_script_add_data( 'real-estate-custom-theme-alpine', 'defer', true );
	}

	wp_enqueue_script('real-estate-custom-theme-navigation', $theme_uri . '/js/navigation.js', array(), $nav_js_version, true);

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
 * Register Property custom post type.
 */
require get_template_directory() . '/inc/cpt-property.php';

/**
 * Register local ACF field groups for property content.
 */
require get_template_directory() . '/inc/acf-fields-properties.php';

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
