<?php
/**
 * The template for the Services page.
 *
 * @package real-estate-custom-theme
 */

get_header();

$asset_base = trailingslashit( get_template_directory_uri() ) . 'assets/images/home';
$asset_path = trailingslashit( get_template_directory() ) . 'assets/images/home';
$services_asset_base = trailingslashit( get_template_directory_uri() ) . 'assets/images/services';
$services_asset_path = trailingslashit( get_template_directory() ) . 'assets/images/services';

$properties_page_url = function_exists( 'real_estate_custom_theme_get_properties_archive_url' )
	? real_estate_custom_theme_get_properties_archive_url()
	: home_url( '/properties/' );
$services_page_url   = function_exists( 'real_estate_custom_theme_get_services_page_url' )
	? real_estate_custom_theme_get_services_page_url()
	: home_url( '/services/' );
$contact_page_url    = function_exists( 'real_estate_custom_theme_get_contact_page_url' )
	? real_estate_custom_theme_get_contact_page_url()
	: home_url( '/contact-us/' );
$services_page_id    = 0;

if ( have_posts() ) {
	the_post();
	$services_page_id = get_the_ID();
	rewind_posts();
}

if ( $services_page_id <= 0 ) {
	$services_page_id = (int) get_queried_object_id();
}

$default_services_hero_title       = __( 'Elevate Your Real Estate Experience', 'real-estate-custom-theme' );
$default_services_hero_description = __(
	'Welcome to Estatein, where your real estate aspirations meet expert guidance. Explore our comprehensive range of services, each designed to cater to your unique needs and dreams.',
	'real-estate-custom-theme'
);

$services_hero_title       = $default_services_hero_title;
$services_hero_description = $default_services_hero_description;

if ( function_exists( 'get_field' ) && $services_page_id > 0 ) {
	$acf_services_hero_title       = trim( (string) get_field( 'services_hero_title', $services_page_id ) );
	$acf_services_hero_description = trim( (string) get_field( 'services_hero_description', $services_page_id ) );

	if ( '' !== $acf_services_hero_title ) {
		$services_hero_title = $acf_services_hero_title;
	}

	if ( '' !== $acf_services_hero_description ) {
		$services_hero_description = $acf_services_hero_description;
	}
}

$quick_links = array(
	array(
		'title' => __( 'Find Your Dream Home', 'real-estate-custom-theme' ),
		'url'   => $properties_page_url,
		'icon'  => 'icon-home.png',
	),
	array(
		'title' => __( 'Unlock Property Value', 'real-estate-custom-theme' ),
		'url'   => $services_page_url,
		'icon'  => 'icon-ticket.png',
	),
	array(
		'title' => __( 'Effortless Property Management', 'real-estate-custom-theme' ),
		'url'   => $services_page_url,
		'icon'  => 'icon-building.png',
	),
	array(
		'title' => __( 'Smart Investments, Informed Decisions', 'real-estate-custom-theme' ),
		'url'   => $services_page_url,
		'icon'  => 'icon-sun.png',
	),
);

$selling_service_cards = array(
	array(
		'title'       => __( 'Valuation Mastery', 'real-estate-custom-theme' ),
		'description' => __( 'Discover the true worth of your property with our expert valuation services.', 'real-estate-custom-theme' ),
		'icon'        => 'valuation-mastery.png',
	),
	array(
		'title'       => __( 'Strategic Marketing', 'real-estate-custom-theme' ),
		'description' => __( 'Selling a property requires more than just listing: it demands a strategic marketing approach.', 'real-estate-custom-theme' ),
		'icon'        => 'strategic_marketing.png',
	),
	array(
		'title'       => __( 'Negotiation Wizardry', 'real-estate-custom-theme' ),
		'description' => __( 'Negotiating the best deal is an art, and our negotiation experts are masters of it.', 'real-estate-custom-theme' ),
		'icon'        => 'negotiation_wizardry.png',
	),
	array(
		'title'       => __( 'Closing Success', 'real-estate-custom-theme' ),
		'description' => __( 'A successful sale is not complete until the closing. We guide you through the intricate closing process.', 'real-estate-custom-theme' ),
		'icon'        => 'closing_success.png',
	),
);

$management_service_cards = array(
	array(
		'title'       => __( 'Tenant Harmony', 'real-estate-custom-theme' ),
		'description' => __( 'Our tenant management services ensure smooth occupancy while minimizing vacancies.', 'real-estate-custom-theme' ),
		'icon'        => 'tenant_harmony.png',
	),
	array(
		'title'       => __( 'Maintenance Ease', 'real-estate-custom-theme' ),
		'description' => __( 'Say goodbye to maintenance headaches. We handle every aspect of property upkeep.', 'real-estate-custom-theme' ),
		'icon'        => 'maintenance_ease.png',
	),
	array(
		'title'       => __( 'Financial Peace of Mind', 'real-estate-custom-theme' ),
		'description' => __( 'Managing property finances can be complex. Our experts take care of rent collection and reporting.', 'real-estate-custom-theme' ),
		'icon'        => 'finance_peace_of_mind.png',
	),
	array(
		'title'       => __( 'Legal Guardian', 'real-estate-custom-theme' ),
		'description' => __( 'Stay compliant with property laws and regulations effortlessly with our legal support.', 'real-estate-custom-theme' ),
		'icon'        => 'legal_guardian.png',
	),
);

$service_icon_directories = array(
	'services' => array(
		'base_url'  => $services_asset_base,
		'base_path' => $services_asset_path,
	),
	'home'     => array(
		'base_url'  => $asset_base,
		'base_path' => $asset_path,
	),
);

$services_value_bg_filename = 'cta_background.png';
$services_value_bg_url      = '';

if ( file_exists( $services_asset_path . '/' . $services_value_bg_filename ) ) {
	$services_value_bg_url = $services_asset_base . '/' . $services_value_bg_filename;
}

$services_value_cta_style_attr = '';
if ( '' !== $services_value_bg_url ) {
	$services_value_cta_style_attr = '--services-value-cta-bg:url(' . esc_url( $services_value_bg_url ) . ');';
}
?>

<main id="primary" class="site-main services-page">
	<?php
	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'id'          => 'services-hero-title',
			'title'       => $services_hero_title,
			'description' => $services_hero_description,
			'section_class' => 'services-page__hero',
		)
	);
	?>

	<section class="quick-links" data-quick-links-loop aria-label="<?php esc_attr_e( 'Key services', 'real-estate-custom-theme' ); ?>">
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

	<section class="services-value section-shell" aria-labelledby="services-value-title">
		<header class="services-value__head">
			<h2 id="services-value-title"><?php esc_html_e( 'Unlock Property Value', 'real-estate-custom-theme' ); ?></h2>
			<p>
				<?php esc_html_e( 'Selling your property should be a rewarding experience, and at Estatein, we make sure it is. Our Property Selling Service is designed to maximize the value of your property, ensuring you get the best deal possible. Explore the categories below to see how we can help you at every step of your selling journey.', 'real-estate-custom-theme' ); ?>
			</p>
		</header>

		<div class="services-value__grid">
			<?php foreach ( $selling_service_cards as $service_card ) : ?>
				<?php
				$service_icon_url    = '';
				$service_icon_source = isset( $service_card['icon_source'] ) ? sanitize_key( (string) $service_card['icon_source'] ) : 'services';
				if ( ! isset( $service_icon_directories[ $service_icon_source ] ) ) {
					$service_icon_source = 'services';
				}

				$service_icon_path = $service_icon_directories[ $service_icon_source ]['base_path'] . '/' . $service_card['icon'];
				$service_icon_base = $service_icon_directories[ $service_icon_source ]['base_url'] . '/' . $service_card['icon'];

				if ( file_exists( $service_icon_path ) ) {
					$service_icon_url = $service_icon_base;
				} elseif ( 'services' !== $service_icon_source && file_exists( $services_asset_path . '/' . $service_card['icon'] ) ) {
					$service_icon_url = $services_asset_base . '/' . $service_card['icon'];
				}
				?>
				<article class="services-value__card">
					<div class="services-value__card-head">
						<?php if ( '' !== $service_icon_url ) : ?>
							<img class="services-value__icon" src="<?php echo esc_url( $service_icon_url ); ?>" alt="" loading="lazy" decoding="async">
						<?php endif; ?>
						<h3><?php echo esc_html( $service_card['title'] ); ?></h3>
					</div>
					<p><?php echo esc_html( $service_card['description'] ); ?></p>
				</article>
			<?php endforeach; ?>

			<article class="services-value__card services-value__card--cta"<?php echo '' !== $services_value_cta_style_attr ? ' style="' . esc_attr( $services_value_cta_style_attr ) . '"' : ''; ?>>
				<div class="services-value__cta-copy">
					<h3><?php esc_html_e( 'Unlock the Value of Your Property Today', 'real-estate-custom-theme' ); ?></h3>
					<p><?php esc_html_e( 'Ready to unlock the true value of your property? Explore our Property Selling Service categories and let us help you achieve the best deal possible for your valuable asset.', 'real-estate-custom-theme' ); ?></p>
				</div>
				<a class="services-value__cta-btn" href="<?php echo esc_url( $contact_page_url ); ?>"><?php esc_html_e( 'Learn More', 'real-estate-custom-theme' ); ?></a>
			</article>
		</div>
	</section>

	<section class="services-value section-shell" aria-labelledby="services-management-title">
		<header class="services-value__head">
			<h2 id="services-management-title"><?php esc_html_e( 'Effortless Property Management', 'real-estate-custom-theme' ); ?></h2>
			<p>
				<?php esc_html_e( 'Owning a property should be a pleasure, not a hassle. Estatein\'s Property Management Service takes the stress out of property ownership, offering comprehensive solutions tailored to your needs. Explore the categories below to see how we can make property management effortless for you.', 'real-estate-custom-theme' ); ?>
			</p>
		</header>

		<div class="services-value__grid">
			<?php foreach ( $management_service_cards as $service_card ) : ?>
				<?php
				$service_icon_url    = '';
				$service_icon_source = isset( $service_card['icon_source'] ) ? sanitize_key( (string) $service_card['icon_source'] ) : 'services';
				if ( ! isset( $service_icon_directories[ $service_icon_source ] ) ) {
					$service_icon_source = 'services';
				}

				$service_icon_path = $service_icon_directories[ $service_icon_source ]['base_path'] . '/' . $service_card['icon'];
				$service_icon_base = $service_icon_directories[ $service_icon_source ]['base_url'] . '/' . $service_card['icon'];

				if ( file_exists( $service_icon_path ) ) {
					$service_icon_url = $service_icon_base;
				} elseif ( 'services' !== $service_icon_source && file_exists( $services_asset_path . '/' . $service_card['icon'] ) ) {
					$service_icon_url = $services_asset_base . '/' . $service_card['icon'];
				}
				?>
				<article class="services-value__card">
					<div class="services-value__card-head">
						<?php if ( '' !== $service_icon_url ) : ?>
							<img class="services-value__icon" src="<?php echo esc_url( $service_icon_url ); ?>" alt="" loading="lazy" decoding="async">
						<?php endif; ?>
						<h3><?php echo esc_html( $service_card['title'] ); ?></h3>
					</div>
					<p><?php echo esc_html( $service_card['description'] ); ?></p>
				</article>
			<?php endforeach; ?>

			<article class="services-value__card services-value__card--cta"<?php echo '' !== $services_value_cta_style_attr ? ' style="' . esc_attr( $services_value_cta_style_attr ) . '"' : ''; ?>>
				<div class="services-value__cta-copy">
					<h3><?php esc_html_e( 'Experience Effortless Property Management', 'real-estate-custom-theme' ); ?></h3>
					<p><?php esc_html_e( 'Ready to experience hassle-free property management? Explore our Property Management Service categories and let us handle the complexities while you enjoy the benefits of property ownership.', 'real-estate-custom-theme' ); ?></p>
				</div>
				<a class="services-value__cta-btn" href="<?php echo esc_url( $contact_page_url ); ?>"><?php esc_html_e( 'Learn More', 'real-estate-custom-theme' ); ?></a>
			</article>
		</div>
	</section>
</main>

<?php
get_footer();
