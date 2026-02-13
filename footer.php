<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package real-estate-custom-theme
 */

?>

<?php
$symbol_logo_url = function_exists( 'real_estate_custom_theme_get_symbol_asset_url' ) ? real_estate_custom_theme_get_symbol_asset_url() : '';
$about_page_url = function_exists( 'real_estate_custom_theme_get_about_page_url' ) ? real_estate_custom_theme_get_about_page_url() : home_url( '/about-us/' );
$properties_page_url = function_exists( 'real_estate_custom_theme_get_properties_archive_url' ) ? real_estate_custom_theme_get_properties_archive_url() : home_url( '/properties/' );
$services_page_url = function_exists( 'real_estate_custom_theme_get_services_page_url' ) ? real_estate_custom_theme_get_services_page_url() : home_url( '/services/' );
$contact_page_url = function_exists( 'real_estate_custom_theme_get_contact_page_url' ) ? real_estate_custom_theme_get_contact_page_url() : home_url( '/contact-us/' );
$about_story_url = $about_page_url . '#about-journey-title';
$about_works_url = $about_page_url . '#about-achievements-title';
$about_process_url = $about_page_url . '#about-process-title';
$about_team_url = $about_page_url . '#about-team-title';
$about_clients_url = $about_page_url . '#about-clients-title';
?>
<footer id="colophon" class="site-footer home-footer">
	<div class="home-footer__cta" aria-labelledby="footer-cta-title">
		<div class="home-footer__cta-inner">
			<div class="home-footer__cta-content">
				<h2 id="footer-cta-title"><?php esc_html_e( 'Start Your Real Estate Journey Today', 'real-estate-custom-theme' ); ?></h2>
				<p><?php esc_html_e( 'Whether you are buying, selling, or investing, Estatein is here to guide you with expert advice and personalized assistance.', 'real-estate-custom-theme' ); ?></p>
			</div>
			<a class="home-footer__cta-action" href="<?php echo esc_url( $properties_page_url ); ?>"><?php esc_html_e( 'Explore Properties', 'real-estate-custom-theme' ); ?></a>
		</div>
	</div>

	<div class="home-footer__main">
		<div class="home-footer__brand-col">
			<a class="home-footer__brand-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php if ( ! empty( $symbol_logo_url ) ) : ?>
					<img src="<?php echo esc_url( $symbol_logo_url ); ?>" alt="<?php esc_attr_e( 'Estatein logo', 'real-estate-custom-theme' ); ?>" class="home-footer__brand-logo">
				<?php endif; ?>
				<span class="home-footer__brand-name"><?php esc_html_e( 'Estatein', 'real-estate-custom-theme' ); ?></span>
			</a>
			<form class="home-footer__subscribe" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="post">
				<label for="home-footer-email" class="screen-reader-text"><?php esc_html_e( 'Enter your email', 'real-estate-custom-theme' ); ?></label>
				<input id="home-footer-email" name="footer_email" type="email" placeholder="<?php esc_attr_e( 'Enter Your Email', 'real-estate-custom-theme' ); ?>">
				<button type="submit" aria-label="<?php esc_attr_e( 'Submit email', 'real-estate-custom-theme' ); ?>">
					<svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
						<path d="M21.8 3.4a1 1 0 0 0-1-.18L2.7 10.5a1 1 0 0 0 .08 1.88l7.12 2.37 2.37 7.12a1 1 0 0 0 .91.68h.06a1 1 0 0 0 .9-.54l7.34-18.2a1 1 0 0 0-.05-1.02zM10.7 14.1l-.99-2.98 6.63-4.89-5.64 5.64z"></path>
					</svg>
				</button>
			</form>
		</div>

		<div class="home-footer__links-grid">
			<div class="home-footer__col">
				<h3><?php esc_html_e( 'Home', 'real-estate-custom-theme' ); ?></h3>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/#hero-title' ) ); ?>"><?php esc_html_e( 'Hero Section', 'real-estate-custom-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/#featured-title' ) ); ?>"><?php esc_html_e( 'Features', 'real-estate-custom-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( $properties_page_url ); ?>"><?php esc_html_e( 'Properties', 'real-estate-custom-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/#testimonials-title' ) ); ?>"><?php esc_html_e( 'Testimonials', 'real-estate-custom-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/#faq-title' ) ); ?>"><?php esc_html_e( "FAQ's", 'real-estate-custom-theme' ); ?></a></li>
				</ul>
			</div>
			<div class="home-footer__col">
				<h3><?php esc_html_e( 'About Us', 'real-estate-custom-theme' ); ?></h3>
				<ul>
					<li><a href="<?php echo esc_url( $about_story_url ); ?>"><?php esc_html_e( 'Our Story', 'real-estate-custom-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( $about_works_url ); ?>"><?php esc_html_e( 'Our Works', 'real-estate-custom-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( $about_process_url ); ?>"><?php esc_html_e( 'How It Works', 'real-estate-custom-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( $about_team_url ); ?>"><?php esc_html_e( 'Our Team', 'real-estate-custom-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( $about_clients_url ); ?>"><?php esc_html_e( 'Our Clients', 'real-estate-custom-theme' ); ?></a></li>
				</ul>
			</div>
			<div class="home-footer__col">
				<h3><?php esc_html_e( 'Properties', 'real-estate-custom-theme' ); ?></h3>
				<ul>
					<li><a href="<?php echo esc_url( $properties_page_url ); ?>"><?php esc_html_e( 'Portfolio', 'real-estate-custom-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( $properties_page_url ); ?>"><?php esc_html_e( 'Categories', 'real-estate-custom-theme' ); ?></a></li>
				</ul>
			</div>
			<div class="home-footer__col">
				<h3><?php esc_html_e( 'Services', 'real-estate-custom-theme' ); ?></h3>
				<ul>
					<li><a href="<?php echo esc_url( $services_page_url ); ?>"><?php esc_html_e( 'Valuation Mastery', 'real-estate-custom-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( $services_page_url ); ?>"><?php esc_html_e( 'Strategic Marketing', 'real-estate-custom-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( $services_page_url ); ?>"><?php esc_html_e( 'Negotiation Wizardry', 'real-estate-custom-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( $services_page_url ); ?>"><?php esc_html_e( 'Closing Success', 'real-estate-custom-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( $services_page_url ); ?>"><?php esc_html_e( 'Property Management', 'real-estate-custom-theme' ); ?></a></li>
				</ul>
			</div>
			<div class="home-footer__col">
				<h3><?php esc_html_e( 'Contact Us', 'real-estate-custom-theme' ); ?></h3>
				<ul>
					<li><a href="<?php echo esc_url( $contact_page_url ); ?>"><?php esc_html_e( 'Contact Form', 'real-estate-custom-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( $contact_page_url ); ?>"><?php esc_html_e( 'Our Offices', 'real-estate-custom-theme' ); ?></a></li>
				</ul>
			</div>
		</div>
	</div>

	<div class="home-footer__bottom">
		<div class="home-footer__bottom-inner">
			<div class="home-footer__legal">
				<span><?php esc_html_e( '@Arnold Curiano. All Rights Reserved', 'real-estate-custom-theme' ); ?></span>
				<a href="<?php echo esc_url( home_url( '/terms-conditions/' ) ); ?>"><?php esc_html_e( 'Terms & Conditions', 'real-estate-custom-theme' ); ?></a>
			</div>
			<div class="home-footer__social">
				<a href="#" aria-label="<?php esc_attr_e( 'Facebook', 'real-estate-custom-theme' ); ?>">f</a>
				<a href="#" aria-label="<?php esc_attr_e( 'LinkedIn', 'real-estate-custom-theme' ); ?>">in</a>
				<a href="#" aria-label="<?php esc_attr_e( 'Twitter', 'real-estate-custom-theme' ); ?>">x</a>
				<a href="#" aria-label="<?php esc_attr_e( 'YouTube', 'real-estate-custom-theme' ); ?>">yt</a>
			</div>
		</div>
	</div>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
