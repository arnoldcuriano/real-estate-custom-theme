<?php
/**
 * Front page template.
 *
 * @package real-estate-custom-theme
 */

get_header();

$front_page_id = (int) get_option( 'page_on_front' );
$asset_base    = get_template_directory_uri() . '/assets/images/home';
$asset_path    = get_template_directory() . '/assets/images/home';

$hero_building_url = '';
$pattern_wave_url  = '';

if ( file_exists( $asset_path . '/hero-building.png' ) ) {
	$hero_building_url = $asset_base . '/hero-building.png';
}

if ( file_exists( $asset_path . '/wave-lines.png' ) ) {
	$pattern_wave_url = $asset_base . '/wave-lines.png';
}

$hero_title       = '';
$hero_description = '';
$hero_image_url   = '';

if ( $front_page_id > 0 && 'page' === get_post_type( $front_page_id ) ) {
	$hero_title       = get_the_title( $front_page_id );
	$hero_description = get_the_excerpt( $front_page_id );
	$hero_image_url   = get_the_post_thumbnail_url( $front_page_id, 'full' );
}

if ( function_exists( 'get_field' ) ) {
	$acf_hero_title = get_field( 'hero_title', $front_page_id );
	$acf_hero_text  = get_field( 'hero_description', $front_page_id );
	$acf_hero_image = get_field( 'hero_image', $front_page_id );

	if ( ! empty( $acf_hero_title ) ) {
		$hero_title = $acf_hero_title;
	}

	if ( ! empty( $acf_hero_text ) ) {
		$hero_description = $acf_hero_text;
	}

	if ( empty( $hero_building_url ) && is_array( $acf_hero_image ) && ! empty( $acf_hero_image['url'] ) ) {
		$hero_image_url = $acf_hero_image['url'];
	}
}

if ( empty( $hero_title ) ) {
	$hero_title = __( 'Discover Your Dream Property with Estatein', 'real-estate-custom-theme' );
}

if ( empty( $hero_description ) ) {
	$hero_description = __( 'Your journey to finding the perfect property begins here. Explore our curated listings and discover the ideal home that matches your lifestyle.', 'real-estate-custom-theme' );
}

$featured_section_description = __( 'Explore our handpicked selection of featured properties. Each listing offers a glimpse into exceptional homes and investments available through Estatein. Click "View Details" for more information.', 'real-estate-custom-theme' );
if ( function_exists( 'get_field' ) && $front_page_id > 0 ) {
	$acf_featured_section_description = (string) get_field( 'featured_section_description', $front_page_id );
	if ( '' !== trim( $acf_featured_section_description ) ) {
		$featured_section_description = $acf_featured_section_description;
	}
}

$testimonial_section_description = __( 'Read the success stories and heartfelt testimonials from our valued clients. Discover why they chose Estatein for their real estate needs.', 'real-estate-custom-theme' );
$faq_section_description         = __( 'Find answers to common questions about Estatein\'s services, property listings, and the real estate process. We\'re here to provide clarity and assist you every step of the way.', 'real-estate-custom-theme' );
?>

<main id="primary" class="site-main homepage">
	<section class="hero <?php echo ! empty( $pattern_wave_url ) ? 'hero--pattern' : ''; ?>" aria-labelledby="hero-title">
		<div class="hero__container">
			<div class="hero__split">
				<div class="hero__content">
					<p class="eyebrow"><?php esc_html_e( 'Discover Your Dream Property', 'real-estate-custom-theme' ); ?></p>
					<h1 id="hero-title"><?php echo esc_html( $hero_title ); ?></h1>
					<p class="hero__lead"><?php echo esc_html( $hero_description ); ?></p>
					<div class="hero__actions">
						<a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>"><?php esc_html_e( 'Learn More', 'real-estate-custom-theme' ); ?></a>
						<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/properties/' ) ); ?>"><?php esc_html_e( 'Browse Properties', 'real-estate-custom-theme' ); ?></a>
					</div>
					<ul class="hero__stats" aria-label="<?php esc_attr_e( 'Company statistics', 'real-estate-custom-theme' ); ?>">
						<li><strong>200+</strong><span><?php esc_html_e( 'Happy Customers', 'real-estate-custom-theme' ); ?></span></li>
						<li><strong>10k+</strong><span><?php esc_html_e( 'Properties For Clients', 'real-estate-custom-theme' ); ?></span></li>
						<li><strong>16+</strong><span><?php esc_html_e( 'Years of Experience', 'real-estate-custom-theme' ); ?></span></li>
					</ul>
				</div>
				<div class="hero__media" aria-hidden="true">
					<?php if ( ! empty( $hero_building_url ) || ! empty( $hero_image_url ) ) : ?>
						<img src="<?php echo esc_url( ! empty( $hero_building_url ) ? $hero_building_url : $hero_image_url ); ?>" alt="" loading="eager" fetchpriority="high">
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<section
		class="quick-links"
		data-quick-links-loop
		x-data="quickLinksLoop"
		x-init="init()"
		aria-label="<?php esc_attr_e( 'Key services', 'real-estate-custom-theme' ); ?>"
	>
		<div class="quick-links__container">
			<div class="quick-links__viewport">
				<div class="quick-links__track" x-ref="track">
				<?php
				$quick_links = array(
					array(
						'title' => __( 'Find Your Dream Home', 'real-estate-custom-theme' ),
						'url'   => home_url( '/properties/' ),
						'icon'  => 'icon-home.png',
					),
					array(
						'title' => __( 'Unlock Property Value', 'real-estate-custom-theme' ),
						'url'   => home_url( '/services/' ),
						'icon'  => 'icon-ticket.png',
					),
					array(
						'title' => __( 'Effortless Property Management', 'real-estate-custom-theme' ),
						'url'   => home_url( '/services/' ),
						'icon'  => 'icon-building.png',
					),
					array(
						'title' => __( 'Smart Investments, Informed Decisions', 'real-estate-custom-theme' ),
						'url'   => home_url( '/services/' ),
						'icon'  => 'icon-sun.png',
					),
				);

					foreach ( $quick_links as $index => $quick_link ) :
						$icon_url = '';
						if ( file_exists( $asset_path . '/' . $quick_link['icon'] ) ) {
							$icon_url = $asset_base . '/' . $quick_link['icon'];
						}
						?>
						<a
							class="quick-links__item"
							href="<?php echo esc_url( $quick_link['url'] ); ?>"
							@mouseenter="setActive(<?php echo (int) $index; ?>)"
							@focus="setActive(<?php echo (int) $index; ?>)"
							:class="activeIndex === <?php echo (int) $index; ?> ? 'is-active' : ''"
						>
							<span class="quick-links__item-arrow" aria-hidden="true">
								<!-- Heroicons arrow-up-right (MIT) -->
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

	<section class="section-shell" aria-labelledby="featured-title">
		<div class="section-head">
			<div>
				<p class="eyebrow"><?php esc_html_e( 'Featured Selection', 'real-estate-custom-theme' ); ?></p>
				<h2 id="featured-title"><?php esc_html_e( 'Featured Properties', 'real-estate-custom-theme' ); ?></h2>
				<p class="section-head__description"><?php echo wp_kses_post( $featured_section_description ); ?></p>
			</div>
			<a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/properties/' ) ); ?>"><?php esc_html_e( 'View All Properties', 'real-estate-custom-theme' ); ?></a>
		</div>

		<?php
		$featured_query_args = array(
			'post_type'           => 'property',
			'post_status'         => 'publish',
			'posts_per_page'      => 12,
			'ignore_sticky_posts' => true,
			'meta_query'          => array(
				array(
					'key'     => 'featured_on_home',
					'value'   => '1',
					'compare' => '=',
				),
			),
			'meta_key'            => 'featured_order',
			'orderby'             => array(
				'meta_value_num' => 'ASC',
				'date'           => 'DESC',
			),
		);

		$property_query = new WP_Query( $featured_query_args );

		if ( ! $property_query->have_posts() ) {
			$property_query = new WP_Query(
				array(
					'post_type'           => 'property',
					'post_status'         => 'publish',
					'posts_per_page'      => 12,
					'ignore_sticky_posts' => true,
					'orderby'             => array(
						'date' => 'DESC',
					),
				)
			);
		}

		if ( $property_query->have_posts() ) :
			$featured_total = (int) $property_query->post_count;
			?>
			<div
				class="featured-properties"
				data-featured-carousel
				x-data="featuredPropertiesCarousel"
				x-init="init()"
				@mouseenter="pause()"
				@mouseleave="resume()"
				@focusin="pause()"
				@focusout="resume()"
			>
				<div class="featured-properties__viewport" x-ref="viewport">
					<div class="featured-properties__track" x-ref="track">
						<?php
						while ( $property_query->have_posts() ) :
							$property_query->the_post();

							$property_id       = get_the_ID();
							$property_price    = trim( (string) get_post_meta( $property_id, 'property_price', true ) );
							$property_bedrooms = trim( (string) get_post_meta( $property_id, 'property_bedrooms', true ) );
							$property_bathroom = trim( (string) get_post_meta( $property_id, 'property_bathrooms', true ) );
							$property_type     = trim( (string) get_post_meta( $property_id, 'property_type', true ) );

							if ( '' === $property_price ) {
								$property_price = trim( (string) get_post_meta( $property_id, 'price', true ) );
							}
							if ( '' === $property_bedrooms ) {
								$property_bedrooms = trim( (string) get_post_meta( $property_id, 'bedrooms', true ) );
							}
							if ( '' === $property_bathroom ) {
								$property_bathroom = trim( (string) get_post_meta( $property_id, 'bathrooms', true ) );
							}

							$card_excerpt_data = function_exists( 'real_estate_custom_theme_get_property_card_excerpt_data' )
								? real_estate_custom_theme_get_property_card_excerpt_data( $property_id, get_the_excerpt(), 150 )
								: array(
									'text'     => wp_trim_words( get_the_excerpt(), 18 ),
									'has_more' => false,
								);
							?>
							<article <?php post_class( 'card featured-properties__slide' ); ?>>
								<a class="card__image" href="<?php the_permalink(); ?>">
									<?php
									if ( has_post_thumbnail() ) {
										the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) );
									} else {
										$fallback_image_url = function_exists( 'real_estate_custom_theme_get_property_fallback_image_url' )
											? real_estate_custom_theme_get_property_fallback_image_url()
											: '';
										if ( '' !== $fallback_image_url ) {
											?>
											<img src="<?php echo esc_url( $fallback_image_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
											<?php
										}
									}
									?>
								</a>
								<div class="card__body">
									<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									<p class="card__excerpt">
										<?php echo esc_html( $card_excerpt_data['text'] ); ?>
										<?php if ( ! empty( $card_excerpt_data['has_more'] ) ) : ?>
											<a class="card__read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'real-estate-custom-theme' ); ?></a>
										<?php endif; ?>
									</p>
									<ul class="meta-pills">
										<?php if ( '' !== $property_bedrooms ) : ?>
											<li>
												<?php
												if ( function_exists( 'real_estate_custom_theme_get_property_meta_icon_markup' ) ) {
													echo wp_kses(
														real_estate_custom_theme_get_property_meta_icon_markup( $property_id, 'property_bedrooms', 'bed' ),
														array(
															'svg'  => array( 'class' => array(), 'viewBox' => array(), 'focusable' => array(), 'aria-hidden' => array() ),
															'path' => array( 'd' => array() ),
															'img'  => array( 'class' => array(), 'src' => array(), 'alt' => array(), 'loading' => array(), 'aria-hidden' => array() ),
														)
													);
												}
												?>
												<span><?php echo esc_html( $property_bedrooms ); ?></span>
											</li>
										<?php endif; ?>
										<?php if ( '' !== $property_bathroom ) : ?>
											<li>
												<?php
												if ( function_exists( 'real_estate_custom_theme_get_property_meta_icon_markup' ) ) {
													echo wp_kses(
														real_estate_custom_theme_get_property_meta_icon_markup( $property_id, 'property_bathrooms', 'bath' ),
														array(
															'svg'  => array( 'class' => array(), 'viewBox' => array(), 'focusable' => array(), 'aria-hidden' => array() ),
															'path' => array( 'd' => array() ),
															'img'  => array( 'class' => array(), 'src' => array(), 'alt' => array(), 'loading' => array(), 'aria-hidden' => array() ),
														)
													);
												}
												?>
												<span><?php echo esc_html( $property_bathroom ); ?></span>
											</li>
										<?php endif; ?>
										<?php if ( '' !== $property_type ) : ?>
											<li>
												<?php
												if ( function_exists( 'real_estate_custom_theme_get_property_meta_icon_markup' ) ) {
													echo wp_kses(
														real_estate_custom_theme_get_property_meta_icon_markup( $property_id, 'property_type', 'building' ),
														array(
															'svg'  => array( 'class' => array(), 'viewBox' => array(), 'focusable' => array(), 'aria-hidden' => array() ),
															'path' => array( 'd' => array() ),
															'img'  => array( 'class' => array(), 'src' => array(), 'alt' => array(), 'loading' => array(), 'aria-hidden' => array() ),
														)
													);
												}
												?>
												<span><?php echo esc_html( $property_type ); ?></span>
											</li>
										<?php endif; ?>
									</ul>
									<div class="card__footer">
										<div>
											<span class="label"><?php esc_html_e( 'Price', 'real-estate-custom-theme' ); ?></span>
											<strong><?php echo '' !== $property_price ? esc_html( $property_price ) : esc_html__( 'Price on request', 'real-estate-custom-theme' ); ?></strong>
										</div>
										<a class="btn btn--primary" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View Property Details', 'real-estate-custom-theme' ); ?></a>
									</div>
								</div>
							</article>
						<?php endwhile; ?>
					</div>
				</div>

				<div class="featured-properties__controls">
					<p class="featured-properties__count">
						<span data-featured-current x-text="formattedCurrent">01</span>
						<?php esc_html_e( 'of', 'real-estate-custom-theme' ); ?>
						<span data-featured-total x-text="formattedTotal"><?php echo esc_html( str_pad( (string) $featured_total, 2, '0', STR_PAD_LEFT ) ); ?></span>
					</p>
					<div class="featured-properties__actions">
						<button type="button" class="featured-properties__arrow" data-featured-prev @click="prev()" :disabled="!canManual" aria-label="<?php esc_attr_e( 'Previous featured properties', 'real-estate-custom-theme' ); ?>">
							<svg class="featured-properties__arrow-icon" viewBox="0 0 24 24" focusable="false" aria-hidden="true">
								<path d="M15 6L9 12L15 18"></path>
							</svg>
						</button>
						<button type="button" class="featured-properties__arrow" data-featured-next @click="next()" :disabled="!canManual" aria-label="<?php esc_attr_e( 'Next featured properties', 'real-estate-custom-theme' ); ?>">
							<svg class="featured-properties__arrow-icon" viewBox="0 0 24 24" focusable="false" aria-hidden="true">
								<path d="M9 6L15 12L9 18"></path>
							</svg>
						</button>
					</div>
				</div>
			</div>
			<?php
			wp_reset_postdata();
		else :
			?>
			<p><?php esc_html_e( 'No featured properties found yet.', 'real-estate-custom-theme' ); ?></p>
			<?php
		endif;
		?>
	</section>

	<section class="section-shell" aria-labelledby="testimonials-title">
		<div class="section-head">
			<div>
				<p class="eyebrow"><?php esc_html_e( 'Testimonials', 'real-estate-custom-theme' ); ?></p>
				<h2 id="testimonials-title"><?php esc_html_e( 'What Our Clients Say', 'real-estate-custom-theme' ); ?></h2>
				<p class="section-head__description"><?php echo esc_html( $testimonial_section_description ); ?></p>
			</div>
			<a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/testimonials/' ) ); ?>"><?php esc_html_e( 'View All Testimonials', 'real-estate-custom-theme' ); ?></a>
		</div>

		<?php
		$testimonials_query = new WP_Query(
			array(
				'post_type'           => 'testimonial',
				'post_status'         => 'publish',
				'posts_per_page'      => 12,
				'ignore_sticky_posts' => true,
				'meta_query'          => array(
					array(
						'key'     => 'is_featured',
						'value'   => '1',
						'compare' => '=',
					),
				),
				'orderby'             => array(
					'date' => 'DESC',
				),
			)
		);

		if ( ! $testimonials_query->have_posts() ) {
			$testimonials_query = new WP_Query(
				array(
					'post_type'           => 'testimonial',
					'post_status'         => 'publish',
					'posts_per_page'      => 12,
					'ignore_sticky_posts' => true,
					'orderby'             => array(
						'date' => 'DESC',
					),
				)
			);
		}

		if ( $testimonials_query->have_posts() ) :
			$testimonials_total = (int) $testimonials_query->post_count;
			?>
			<div
				class="testimonials"
				data-testimonials-carousel
				x-data="testimonialsCarousel"
				x-init="init()"
				@mouseenter="pause()"
				@mouseleave="resume()"
				@focusin="pause()"
				@focusout="resume()"
			>
				<div class="testimonials__viewport" x-ref="viewport">
					<div class="testimonials__track" x-ref="track">
						<?php
						while ( $testimonials_query->have_posts() ) :
							$testimonials_query->the_post();

							$testimonial_id       = get_the_ID();
							$testimonial_rating   = function_exists( 'real_estate_custom_theme_get_testimonial_rating' ) ? real_estate_custom_theme_get_testimonial_rating( $testimonial_id ) : 5;
							$testimonial_quote    = function_exists( 'real_estate_custom_theme_get_testimonial_quote' ) ? real_estate_custom_theme_get_testimonial_quote( $testimonial_id ) : wp_trim_words( get_the_excerpt(), 24 );
							$client_name          = function_exists( 'real_estate_custom_theme_get_testimonial_client_name' ) ? real_estate_custom_theme_get_testimonial_client_name( $testimonial_id ) : get_the_title();
							$client_location      = function_exists( 'real_estate_custom_theme_get_testimonial_client_location' ) ? real_estate_custom_theme_get_testimonial_client_location( $testimonial_id ) : '';
							$testimonial_photo_url = function_exists( 'real_estate_custom_theme_get_testimonial_photo_url' ) ? real_estate_custom_theme_get_testimonial_photo_url( $testimonial_id ) : '';
							?>
							<article <?php post_class( 'card testimonial-card testimonials__slide' ); ?>>
								<div class="card__body">
									<ul class="testimonial-card__stars" aria-label="<?php echo esc_attr( sprintf( __( 'Rated %d out of 5', 'real-estate-custom-theme' ), (int) $testimonial_rating ) ); ?>">
										<?php for ( $star_index = 1; $star_index <= 5; $star_index++ ) : ?>
											<li class="testimonial-card__star<?php echo $star_index <= $testimonial_rating ? ' is-filled' : ''; ?>" aria-hidden="true">&#9733;</li>
										<?php endfor; ?>
									</ul>
									<h3><?php the_title(); ?></h3>
									<p class="testimonial-card__quote"><?php echo esc_html( $testimonial_quote ); ?></p>
									<div class="testimonial-card__client">
										<?php if ( '' !== $testimonial_photo_url ) : ?>
											<img src="<?php echo esc_url( $testimonial_photo_url ); ?>" alt="<?php echo esc_attr( $client_name ); ?>" loading="lazy">
										<?php endif; ?>
										<div>
											<strong><?php echo esc_html( $client_name ); ?></strong>
											<?php if ( '' !== $client_location ) : ?>
												<span><?php echo esc_html( $client_location ); ?></span>
											<?php endif; ?>
										</div>
									</div>
								</div>
							</article>
						<?php endwhile; ?>
					</div>
				</div>

				<div class="featured-properties__controls testimonials__controls">
					<p class="featured-properties__count testimonials__count">
						<span data-testimonials-current x-text="formattedCurrent">01</span>
						<?php esc_html_e( 'of', 'real-estate-custom-theme' ); ?>
						<span data-testimonials-total x-text="formattedTotal"><?php echo esc_html( str_pad( (string) $testimonials_total, 2, '0', STR_PAD_LEFT ) ); ?></span>
					</p>
					<div class="featured-properties__actions testimonials__actions">
						<button type="button" class="featured-properties__arrow testimonials__arrow" data-testimonials-prev @click="prev()" :disabled="!canManual" aria-label="<?php esc_attr_e( 'Previous testimonials', 'real-estate-custom-theme' ); ?>">
							<svg class="featured-properties__arrow-icon" viewBox="0 0 24 24" focusable="false" aria-hidden="true">
								<path d="M15 6L9 12L15 18"></path>
							</svg>
						</button>
						<button type="button" class="featured-properties__arrow testimonials__arrow" data-testimonials-next @click="next()" :disabled="!canManual" aria-label="<?php esc_attr_e( 'Next testimonials', 'real-estate-custom-theme' ); ?>">
							<svg class="featured-properties__arrow-icon" viewBox="0 0 24 24" focusable="false" aria-hidden="true">
								<path d="M9 6L15 12L9 18"></path>
							</svg>
						</button>
					</div>
				</div>
			</div>
			<?php
			wp_reset_postdata();
		else :
			?>
			<p><?php esc_html_e( 'No testimonials found yet.', 'real-estate-custom-theme' ); ?></p>
			<?php
		endif;
		?>
	</section>

	<section class="section-shell" aria-labelledby="faq-title">
		<div class="section-head">
			<div>
				<p class="eyebrow"><?php esc_html_e( 'FAQ', 'real-estate-custom-theme' ); ?></p>
				<h2 id="faq-title"><?php esc_html_e( 'Frequently Asked Questions', 'real-estate-custom-theme' ); ?></h2>
				<p class="section-head__description"><?php echo esc_html( $faq_section_description ); ?></p>
			</div>
			<a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/faqs/' ) ); ?>"><?php esc_html_e( 'View All FAQ\'s', 'real-estate-custom-theme' ); ?></a>
		</div>

		<?php
		$faq_query = new WP_Query(
			array(
				'post_type'           => 'faq',
				'post_status'         => 'publish',
				'posts_per_page'      => 12,
				'ignore_sticky_posts' => true,
				'meta_query'          => array(
					array(
						'key'     => 'is_featured',
						'value'   => '1',
						'compare' => '=',
					),
				),
				'orderby'             => array(
					'date' => 'DESC',
				),
			)
		);

		if ( $faq_query->have_posts() ) :
			$faq_total = (int) $faq_query->post_count;
			?>
			<div
				class="faqs"
				data-faq-carousel
				x-data="faqCarousel"
				x-init="init()"
				@mouseenter="pause()"
				@mouseleave="resume()"
				@focusin="pause()"
				@focusout="resume()"
			>
				<div class="faqs__viewport" x-ref="viewport">
					<div class="faqs__track" x-ref="track">
						<?php
						while ( $faq_query->have_posts() ) :
							$faq_query->the_post();
							$faq_id          = get_the_ID();
							$faq_excerpt     = function_exists( 'real_estate_custom_theme_get_faq_excerpt' ) ? real_estate_custom_theme_get_faq_excerpt( $faq_id, get_the_excerpt(), 150 ) : wp_trim_words( get_the_excerpt(), 24 );
							$faq_button_text = function_exists( 'real_estate_custom_theme_get_faq_cta_label' ) ? real_estate_custom_theme_get_faq_cta_label( $faq_id ) : __( 'Read More', 'real-estate-custom-theme' );
							?>
							<article <?php post_class( 'card faq-card faqs__slide' ); ?>>
								<div class="faq-card__body">
									<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									<p class="faq-card__excerpt"><?php echo esc_html( $faq_excerpt ); ?></p>
									<a class="faq-card__cta" href="<?php the_permalink(); ?>"><?php echo esc_html( $faq_button_text ); ?></a>
								</div>
							</article>
						<?php endwhile; ?>
					</div>
				</div>

				<div class="featured-properties__controls faqs__controls">
					<p class="featured-properties__count faqs__count">
						<span data-faq-current x-text="formattedCurrent">01</span>
						<?php esc_html_e( 'of', 'real-estate-custom-theme' ); ?>
						<span data-faq-total x-text="formattedTotal"><?php echo esc_html( str_pad( (string) $faq_total, 2, '0', STR_PAD_LEFT ) ); ?></span>
					</p>
					<div class="featured-properties__actions faqs__actions">
						<button type="button" class="featured-properties__arrow faqs__arrow" data-faq-prev @click="prev()" :disabled="!canManual" aria-label="<?php esc_attr_e( 'Previous FAQs', 'real-estate-custom-theme' ); ?>">
							<svg class="featured-properties__arrow-icon" viewBox="0 0 24 24" focusable="false" aria-hidden="true">
								<path d="M15 6L9 12L15 18"></path>
							</svg>
						</button>
						<button type="button" class="featured-properties__arrow faqs__arrow" data-faq-next @click="next()" :disabled="!canManual" aria-label="<?php esc_attr_e( 'Next FAQs', 'real-estate-custom-theme' ); ?>">
							<svg class="featured-properties__arrow-icon" viewBox="0 0 24 24" focusable="false" aria-hidden="true">
								<path d="M9 6L15 12L9 18"></path>
							</svg>
						</button>
					</div>
				</div>
			</div>
			<?php
			wp_reset_postdata();
		else :
			?>
			<p><?php esc_html_e( 'No featured FAQs found yet.', 'real-estate-custom-theme' ); ?></p>
			<?php
		endif;
		?>
	</section>

	<section class="cta-band" aria-labelledby="cta-title">
		<div>
			<h2 id="cta-title"><?php esc_html_e( 'Start Your Real Estate Journey Today', 'real-estate-custom-theme' ); ?></h2>
			<p><?php esc_html_e( 'Whether you are buying, selling, or investing, Estatein is here to guide you with expert advice and personalized assistance.', 'real-estate-custom-theme' ); ?></p>
		</div>
		<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/properties/' ) ); ?>"><?php esc_html_e( 'Explore Properties', 'real-estate-custom-theme' ); ?></a>
	</section>
</main>

<?php
get_footer();
