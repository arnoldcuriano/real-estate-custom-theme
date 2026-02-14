<?php
/**
 * The template for the Services page.
 *
 * @package real-estate-custom-theme
 */

get_header();

$asset_base = trailingslashit( get_template_directory_uri() ) . 'assets/images/home';
$asset_path = trailingslashit( get_template_directory() ) . 'assets/images/home';

$properties_page_url = function_exists( 'real_estate_custom_theme_get_properties_archive_url' )
	? real_estate_custom_theme_get_properties_archive_url()
	: home_url( '/properties/' );
$services_page_url   = function_exists( 'real_estate_custom_theme_get_services_page_url' )
	? real_estate_custom_theme_get_services_page_url()
	: home_url( '/services/' );
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
</main>

<?php
get_footer();
