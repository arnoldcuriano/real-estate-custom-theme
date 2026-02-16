<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package real-estate-custom-theme
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'real-estate-custom-theme'); ?></a>

		<header id="masthead" class="site-header">
			<?php
			$use_front_header = function_exists( 'real_estate_custom_theme_is_front_header_context' )
				? real_estate_custom_theme_is_front_header_context()
				: ( is_front_page() || is_page( 'about-us' ) );

			if ( $use_front_header ) :
				$is_home_item_active       = is_front_page();
				$is_about_item_active      = is_page( 'about-us' );
				$is_properties_item_active = is_post_type_archive( 'property' ) || is_singular( 'property' );
				$is_services_item_active   = is_page( 'services' );
				$is_contact_item_active    = is_page( 'contact-us' );
				$about_page_url            = function_exists( 'real_estate_custom_theme_get_about_page_url' ) ? real_estate_custom_theme_get_about_page_url() : home_url( '/about-us/' );
				$properties_page_url       = function_exists( 'real_estate_custom_theme_get_properties_archive_url' ) ? real_estate_custom_theme_get_properties_archive_url() : home_url( '/properties/' );
				$services_page_url         = function_exists( 'real_estate_custom_theme_get_services_page_url' ) ? real_estate_custom_theme_get_services_page_url() : home_url( '/services/' );
				$contact_page_url          = function_exists( 'real_estate_custom_theme_get_contact_page_url' ) ? real_estate_custom_theme_get_contact_page_url() : home_url( '/contact-us/' );
			?>
				<?php
				$symbol_logo_url = '';
				$logo_candidates = array(
					'Estatein.png',
					'Estatein.svg',
					'Estatein.webp',
					'Estatein.jpg',
					'Estatein.jpeg',
					'estatein.png',
					'estatein.svg',
					'estatein.webp',
					'estatein.jpg',
					'estatein.jpeg',
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
						$symbol_logo_url = get_template_directory_uri() . '/assets/images/home/' . $logo_candidate;
						break;
					}
				}
				?>
				<div class="front-header-shell">
					<div class="front-top-banner" role="region" aria-label="<?php esc_attr_e( 'Announcement', 'real-estate-custom-theme' ); ?>">
						<div class="front-top-banner__inner">
							<p class="front-top-banner__text">
								<span aria-hidden="true">&#10024;</span>
								<?php esc_html_e( 'Discover Your Dream Property with Estatein', 'real-estate-custom-theme' ); ?>
								<a href="<?php echo esc_url( $about_page_url ); ?>"><?php esc_html_e( 'Learn More', 'real-estate-custom-theme' ); ?></a>
							</p>
						</div>
						<button type="button" class="front-top-banner__close" aria-label="<?php esc_attr_e( 'Close announcement', 'real-estate-custom-theme' ); ?>">
							<span aria-hidden="true">&times;</span>
						</button>
					</div><!-- .site-branding -->

					<div class="front-nav-row">
						<div class="site-branding">
							<a class="brand-link" href="<?php echo esc_url( home_url('/') ); ?>" rel="home">
								<?php if ( ! empty( $symbol_logo_url ) ) : ?>
									<img src="<?php echo esc_url( $symbol_logo_url ); ?>" alt="<?php esc_attr_e( 'Estatein logo', 'real-estate-custom-theme' ); ?>" class="brand-logo brand-logo--image">
								<?php else : ?>
									<span class="brand-logo brand-logo--shape" aria-hidden="true"></span>
								<?php endif; ?>
								<span class="brand-name"><?php esc_html_e( 'Estatein', 'real-estate-custom-theme' ); ?></span>
							</a>
						</div><!-- .site-branding -->

						<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'real-estate-custom-theme' ); ?>">
							<button class="menu-toggle front-menu-toggle" aria-controls="front-primary-menu" aria-expanded="false">
								<span class="screen-reader-text"><?php esc_html_e( 'Toggle navigation', 'real-estate-custom-theme' ); ?></span>
								<span aria-hidden="true"></span>
								<span aria-hidden="true"></span>
								<span aria-hidden="true"></span>
							</button>
							<ul id="front-primary-menu" class="front-menu-list">
								<li<?php echo $is_home_item_active ? ' class="current-menu-item"' : ''; ?>><a href="<?php echo esc_url( home_url('/') ); ?>"><?php esc_html_e( 'Home', 'real-estate-custom-theme' ); ?></a></li>
								<li<?php echo $is_about_item_active ? ' class="current-menu-item"' : ''; ?>><a href="<?php echo esc_url( $about_page_url ); ?>"><?php esc_html_e( 'About Us', 'real-estate-custom-theme' ); ?></a></li>
								<li<?php echo $is_properties_item_active ? ' class="current-menu-item"' : ''; ?>><a href="<?php echo esc_url( $properties_page_url ); ?>"><?php esc_html_e( 'Properties', 'real-estate-custom-theme' ); ?></a></li>
								<li<?php echo $is_services_item_active ? ' class="current-menu-item"' : ''; ?>><a href="<?php echo esc_url( $services_page_url ); ?>"><?php esc_html_e( 'Services', 'real-estate-custom-theme' ); ?></a></li>
								<li class="front-menu-contact<?php echo $is_contact_item_active ? ' current-menu-item' : ''; ?>"><a href="<?php echo esc_url( $contact_page_url ); ?>"><?php esc_html_e( 'Contact Us', 'real-estate-custom-theme' ); ?></a></li>
							</ul>
						</nav><!-- #site-navigation -->

						<a class="header-contact-btn" href="<?php echo esc_url( $contact_page_url ); ?>"><?php esc_html_e('Contact Us', 'real-estate-custom-theme'); ?></a>
					</div>
				</div>
			<?php else : ?>
				<div class="site-branding">
					<?php
					the_custom_logo();
					if (is_home()) :
					?>
						<h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
					<?php
					else :
					?>
						<p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
					<?php
					endif;
					$real_estate_custom_theme_description = get_bloginfo('description', 'display');
					if ($real_estate_custom_theme_description || is_customize_preview()) :
					?>
						<p class="site-description"><?php echo esc_html($real_estate_custom_theme_description); ?></p>
					<?php endif; ?>
				</div><!-- .site-branding -->

				<div class="header-nav-wrap">
					<nav id="site-navigation" class="main-navigation">
						<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e('Primary Menu', 'real-estate-custom-theme'); ?></button>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'menu-1',
								'menu_id'        => 'primary-menu',
								'fallback_cb'    => false,
							)
						);
						?>
					</nav><!-- #site-navigation -->
					<a class="header-contact-btn" href="<?php echo esc_url( function_exists( 'real_estate_custom_theme_get_contact_page_url' ) ? real_estate_custom_theme_get_contact_page_url() : home_url('/contact-us/') ); ?>"><?php esc_html_e('Contact Us', 'real-estate-custom-theme'); ?></a>
				</div>
			<?php endif; ?>
		</header><!-- #masthead -->
