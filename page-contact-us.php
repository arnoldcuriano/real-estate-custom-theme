<?php
/**
 * The template for the Contact Us page.
 *
 * @package real-estate-custom-theme
 */

get_header();

$asset_base = trailingslashit( get_template_directory_uri() ) . 'assets/images/contact';
$asset_path = trailingslashit( get_template_directory() ) . 'assets/images/contact';

$contact_hero_title = __( 'Get in Touch with Estatein', 'real-estate-custom-theme' );
$contact_hero_description = __(
	'Welcome to Estatein\'s Contact Us page. We\'re here to assist you with any inquiries, requests, or feedback you may have. Whether you\'re looking to buy or sell a property, explore investment opportunities, or simply want to connect, we\'re just a message away. Reach out to us, and let\'s start a conversation.',
	'real-estate-custom-theme'
);
$contact_connect_form_shortcode = function_exists( 'real_estate_custom_theme_get_contact_connect_form_shortcode' )
	? real_estate_custom_theme_get_contact_connect_form_shortcode()
	: '';

$quick_links = array(
	array(
		'title' => 'info@estatein.com',
		'url'   => 'mailto:info@estatein.com',
		'icon'  => 'email_icon.png',
	),
	array(
		'title' => '+1 (123) 456-7890',
		'url'   => 'tel:+11234567890',
		'icon'  => 'phone_icon.png',
	),
	array(
		'title' => __( 'Main Headquarters', 'real-estate-custom-theme' ),
		'url'   => 'https://maps.google.com/?q=Main+Headquarters',
		'icon'  => 'location_icon.png',
	),
	array(
		'title' => __( 'EstateIn', 'real-estate-custom-theme' ),
		'url'   => home_url( '/' ),
		'icon'  => 'social_icon.png',
	),
);

$office_locations = array(
	array(
		'category'    => 'regional',
		'label'       => __( 'Main Headquarters', 'real-estate-custom-theme' ),
		'address'     => __( '123 Estatein Plaza, City Center, Metropolis', 'real-estate-custom-theme' ),
		'description' => __( 'Our main headquarters serves as the heart of Estatein. Located in the bustling city center, this is where our core team of experts operates, driving the excellence and innovation that define us.', 'real-estate-custom-theme' ),
		'email'       => 'info@estatein.com',
		'phone'       => '+1 (123) 456-7890',
		'city'        => __( 'Metropolis', 'real-estate-custom-theme' ),
		'direction'   => 'https://maps.google.com/?q=123+Estatein+Plaza+City+Center+Metropolis',
	),
	array(
		'category'    => 'regional',
		'label'       => __( 'Regional Offices', 'real-estate-custom-theme' ),
		'address'     => __( '456 Urban Avenue, Downtown District, Metropolis', 'real-estate-custom-theme' ),
		'description' => __( 'Estatein\'s presence extends to multiple regions, each with its own dynamic real estate landscape. Discover our regional offices, staffed by local experts who understand the nuances of their respective markets.', 'real-estate-custom-theme' ),
		'email'       => 'info@estatein.com',
		'phone'       => '+1 (123) 628-7890',
		'city'        => __( 'Metropolis', 'real-estate-custom-theme' ),
		'direction'   => 'https://maps.google.com/?q=456+Urban+Avenue+Downtown+District+Metropolis',
	),
	array(
		'category'    => 'international',
		'label'       => __( 'International Office', 'real-estate-custom-theme' ),
		'address'     => __( '88 Global Tower, Business Bay, Dubai', 'real-estate-custom-theme' ),
		'description' => __( 'Our international office supports cross-border buyers, sellers, and investors with market-ready guidance and multilingual real estate support.', 'real-estate-custom-theme' ),
		'email'       => 'global@estatein.com',
		'phone'       => '+971 4 123 4567',
		'city'        => __( 'Dubai', 'real-estate-custom-theme' ),
		'direction'   => 'https://maps.google.com/?q=88+Global+Tower+Business+Bay+Dubai',
	),
	array(
		'category'    => 'international',
		'label'       => __( 'International Advisory Desk', 'real-estate-custom-theme' ),
		'address'     => __( '21 Riverfront Hub, Marina South, Singapore', 'real-estate-custom-theme' ),
		'description' => __( 'From investment onboarding to relocation guidance, our advisory desk helps clients navigate opportunities with a globally informed strategy.', 'real-estate-custom-theme' ),
		'email'       => 'advisory@estatein.com',
		'phone'       => '+65 6123 4567',
		'city'        => __( 'Singapore', 'real-estate-custom-theme' ),
		'direction'   => 'https://maps.google.com/?q=21+Riverfront+Hub+Marina+South+Singapore',
	),
);
?>

<main id="primary" class="site-main services-page contact-page">
	<?php
	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'id'            => 'contact-hero-title',
			'title'         => $contact_hero_title,
			'description'   => $contact_hero_description,
			'section_class' => 'contact-page__hero',
		)
	);
	?>

	<section class="quick-links" data-quick-links-loop aria-label="<?php esc_attr_e( 'Contact channels', 'real-estate-custom-theme' ); ?>">
		<div class="quick-links__container">
			<div class="quick-links__viewport">
				<div class="quick-links__track">
					<?php foreach ( $quick_links as $quick_link ) : ?>
						<?php
						$icon_url = '';
						if ( file_exists( $asset_path . '/' . $quick_link['icon'] ) ) {
							$icon_url = $asset_base . '/' . $quick_link['icon'];
						}
						?>
						<a class="quick-links__item" href="<?php echo esc_url( $quick_link['url'] ); ?>">
							<span class="quick-links__item-arrow" aria-hidden="true">
								<svg class="quick-links__item-arrow-icon" viewBox="0 0 24 24" focusable="false">
									<path d="M7 17L17 7"></path>
									<path d="M8 7H17V16"></path>
								</svg>
							</span>
							<?php if ( ! empty( $icon_url ) ) : ?>
								<img src="<?php echo esc_url( $icon_url ); ?>" alt="" loading="lazy" aria-hidden="true">
							<?php endif; ?>
							<span><?php echo esc_html( $quick_link['title'] ); ?></span>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>

	<section class="property-inquiry section-shell property-inquiry--contact" aria-labelledby="contact-connect-title">
		<div class="property-inquiry__head">
			<h2 id="contact-connect-title"><?php esc_html_e( 'Let\'s Connect', 'real-estate-custom-theme' ); ?></h2>
			<p>
				<?php
				esc_html_e(
					'We\'re excited to connect with you and learn more about your real estate goals. Use the form below to get in touch with Estatein. Whether you\'re a prospective client, partner, or simply curious about our services, we\'re here to answer your questions and provide the assistance you need.',
					'real-estate-custom-theme'
				);
				?>
			</p>
		</div>
		<div class="property-inquiry__panel">
			<div class="property-inquiry__form-wrap">
				<?php if ( ! empty( $contact_connect_form_shortcode ) ) : ?>
					<?php echo do_shortcode( $contact_connect_form_shortcode ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php else : ?>
					<p class="property-inquiry__fallback">
						<?php esc_html_e( 'Create a Contact Form 7 form titled "Contact Connect Form" to render this section.', 'real-estate-custom-theme' ); ?>
					</p>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="contact-offices section-shell" aria-labelledby="contact-offices-title" data-contact-offices-tabs>
		<div class="contact-offices__head">
			<h2 id="contact-offices-title"><?php esc_html_e( 'Discover Our Office Locations', 'real-estate-custom-theme' ); ?></h2>
			<p>
				<?php
				esc_html_e(
					'Estatein is here to serve you across multiple locations. Whether you\'re looking to meet our team, discuss real estate opportunities, or simply drop by for a chat, we have offices conveniently located to serve your needs. Explore the categories below to find the Estatein office nearest to you.',
					'real-estate-custom-theme'
				);
				?>
			</p>
		</div>

		<div class="contact-offices__tabs" role="tablist" aria-label="<?php esc_attr_e( 'Office categories', 'real-estate-custom-theme' ); ?>">
			<button class="contact-offices__tab is-active" type="button" role="tab" aria-selected="true" data-office-tab="all">
				<?php esc_html_e( 'All', 'real-estate-custom-theme' ); ?>
			</button>
			<button class="contact-offices__tab" type="button" role="tab" aria-selected="false" data-office-tab="regional">
				<?php esc_html_e( 'Regional', 'real-estate-custom-theme' ); ?>
			</button>
			<button class="contact-offices__tab" type="button" role="tab" aria-selected="false" data-office-tab="international">
				<?php esc_html_e( 'International', 'real-estate-custom-theme' ); ?>
			</button>
		</div>

		<div class="contact-offices__grid">
			<?php foreach ( $office_locations as $office ) : ?>
				<article class="contact-offices__card" data-office-card data-office-category="<?php echo esc_attr( $office['category'] ); ?>">
					<p class="contact-offices__label"><?php echo esc_html( $office['label'] ); ?></p>
					<h3 class="contact-offices__address"><?php echo esc_html( $office['address'] ); ?></h3>
					<p class="contact-offices__description"><?php echo esc_html( $office['description'] ); ?></p>

					<div class="contact-offices__chips">
						<span class="contact-offices__chip">
							<svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
								<path d="M4 6h16v12H4z"></path>
								<path d="M4 7l8 6 8-6"></path>
							</svg>
							<?php echo esc_html( $office['email'] ); ?>
						</span>
						<span class="contact-offices__chip">
							<svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
								<path d="M6.5 4.5h3l1.5 3-2 2a14 14 0 0 0 5 5l2-2 3 1.5v3A2 2 0 0 1 17 19.5C10 19.5 4.5 14 4.5 7a2 2 0 0 1 2-2.5z"></path>
							</svg>
							<?php echo esc_html( $office['phone'] ); ?>
						</span>
						<span class="contact-offices__chip">
							<svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
								<path d="M12 21s7-5.3 7-11a7 7 0 1 0-14 0c0 5.7 7 11 7 11z"></path>
								<circle cx="12" cy="10" r="2.5"></circle>
							</svg>
							<?php echo esc_html( $office['city'] ); ?>
						</span>
					</div>

					<a class="contact-offices__cta" href="<?php echo esc_url( $office['direction'] ); ?>" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'Get Direction', 'real-estate-custom-theme' ); ?>
					</a>
				</article>
			<?php endforeach; ?>
		</div>
	</section>
</main>

<?php
get_footer();
