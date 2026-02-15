<?php
/**
 * Property archive template.
 *
 * @package real-estate-custom-theme
 */

get_header();

$properties_hero_title       = post_type_archive_title( '', false );
$properties_hero_description = __( 'Explore our latest listings and discover exceptional homes and investment opportunities.', 'real-estate-custom-theme' );

if ( '' === trim( (string) $properties_hero_title ) ) {
	$properties_hero_title = __( 'Properties', 'real-estate-custom-theme' );
}

$property_archive_url = function_exists( 'real_estate_custom_theme_get_properties_archive_url' )
	? real_estate_custom_theme_get_properties_archive_url()
	: get_post_type_archive_link( 'property' );

$filter_state = function_exists( 'real_estate_custom_theme_get_property_archive_filter_state' )
	? real_estate_custom_theme_get_property_archive_filter_state()
	: array(
		's'                => isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '',
		'location'         => isset( $_GET['location'] ) ? sanitize_title( wp_unslash( $_GET['location'] ) ) : '',
		'type'             => isset( $_GET['type'] ) ? sanitize_title( wp_unslash( $_GET['type'] ) ) : '',
		'price_range'      => isset( $_GET['price_range'] ) ? sanitize_title( wp_unslash( $_GET['price_range'] ) ) : '',
		'size_range'       => isset( $_GET['size_range'] ) ? sanitize_title( wp_unslash( $_GET['size_range'] ) ) : '',
		'build_year_range' => isset( $_GET['build_year_range'] ) ? sanitize_title( wp_unslash( $_GET['build_year_range'] ) ) : '',
	);

$location_terms = get_terms(
	array(
		'taxonomy'   => 'property_location',
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);

$type_terms = get_terms(
	array(
		'taxonomy'   => 'property_type',
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);

$price_ranges = function_exists( 'real_estate_custom_theme_get_property_price_ranges' )
	? real_estate_custom_theme_get_property_price_ranges()
	: array();
$size_ranges  = function_exists( 'real_estate_custom_theme_get_property_size_ranges' )
	? real_estate_custom_theme_get_property_size_ranges()
	: array();
$year_ranges  = function_exists( 'real_estate_custom_theme_get_property_build_year_ranges' )
	? real_estate_custom_theme_get_property_build_year_ranges()
	: array();

$active_filter_args = array_filter(
	array(
		's'                => $filter_state['s'],
		'location'         => $filter_state['location'],
		'type'             => $filter_state['type'],
		'price_range'      => $filter_state['price_range'],
		'size_range'       => $filter_state['size_range'],
		'build_year_range' => $filter_state['build_year_range'],
	),
	static function( $value ) {
		return '' !== (string) $value;
	}
);

$current_post_type = get_query_var( 'post_type' );
if ( is_array( $current_post_type ) ) {
	$current_post_type = reset( $current_post_type );
}

if ( 'property' === (string) $current_post_type ) {
	$active_filter_args['post_type'] = 'property';
}

if ( '' !== $filter_state['s'] ) {
	$properties_hero_title       = sprintf(
		/* translators: %s: search query. */
		__( 'Search Results for: %s', 'real-estate-custom-theme' ),
		$filter_state['s']
	);
	$properties_hero_description = __( 'Showing properties that match your search and selected filters.', 'real-estate-custom-theme' );
}

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

$featured_properties_query = new WP_Query( $featured_query_args );

if ( ! $featured_properties_query->have_posts() ) {
	$featured_properties_query = new WP_Query(
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

$property_inquiry_form_shortcode = function_exists( 'real_estate_custom_theme_get_property_inquiry_form_shortcode' )
	? real_estate_custom_theme_get_property_inquiry_form_shortcode()
	: '';

?>

<main id="primary" class="site-main property-archive">
	<?php
	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'id'            => 'property-archive-title',
			'title'         => $properties_hero_title,
			'description'   => $properties_hero_description,
			'section_class' => 'property-archive__hero',
		)
	);
	?>

	<section class="property-archive__shell" aria-label="<?php esc_attr_e( 'Property listings', 'real-estate-custom-theme' ); ?>">
		<form class="property-archive__filters" method="get" action="<?php echo esc_url( $property_archive_url ); ?>">
			<input type="hidden" name="post_type" value="property">
			<div class="property-archive__search-wrap">
				<div class="property-archive__search-inner">
					<div class="property-archive__search-group">
						<div class="property-archive__search-row">
							<label class="property-archive__search-field" for="property-search">
								<span class="property-archive__search-icon" aria-hidden="true">
									<svg viewBox="0 0 24 24" focusable="false">
										<circle cx="11" cy="11" r="6"></circle>
										<path d="M20 20L16.6 16.6"></path>
									</svg>
								</span>
								<input id="property-search" class="property-archive__search-input" type="search" name="s" value="<?php echo esc_attr( $filter_state['s'] ); ?>" placeholder="<?php esc_attr_e( 'Search for a property', 'real-estate-custom-theme' ); ?>">
							</label>
							<button class="property-archive__search-submit" type="submit"><?php esc_html_e( 'Find Property', 'real-estate-custom-theme' ); ?></button>
						</div>
					</div>
				</div>
			</div>

			<div class="property-archive__filters-row-wrap">
				<div class="property-archive__filters-group">
					<div class="property-archive__filter-grid">
						<label class="property-archive__filter-control" for="property-filter-location">
							<span class="property-archive__filter-icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" focusable="false">
									<path d="M12 22C12 22 6 16.6 6 11A6 6 0 1 1 18 11C18 16.6 12 22 12 22z"></path>
									<circle cx="12" cy="11" r="2.5"></circle>
								</svg>
							</span>
							<select id="property-filter-location" class="property-archive__filter-select js-property-filter-select" name="location" aria-label="<?php esc_attr_e( 'Location', 'real-estate-custom-theme' ); ?>">
								<option value=""><?php esc_html_e( 'Location', 'real-estate-custom-theme' ); ?></option>
								<?php if ( ! is_wp_error( $location_terms ) && ! empty( $location_terms ) ) : ?>
									<?php foreach ( $location_terms as $location_term ) : ?>
										<option value="<?php echo esc_attr( $location_term->slug ); ?>"<?php selected( $filter_state['location'], (string) $location_term->slug ); ?>>
											<?php echo esc_html( $location_term->name ); ?>
										</option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</label>

						<label class="property-archive__filter-control" for="property-filter-type">
							<span class="property-archive__filter-icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" focusable="false">
									<path d="M4 20H20"></path>
									<path d="M5 20V8L12 4L19 8V20"></path>
									<path d="M9 12H11"></path>
									<path d="M13 12H15"></path>
								</svg>
							</span>
							<select id="property-filter-type" class="property-archive__filter-select js-property-filter-select" name="type" aria-label="<?php esc_attr_e( 'Property type', 'real-estate-custom-theme' ); ?>">
								<option value=""><?php esc_html_e( 'Property Type', 'real-estate-custom-theme' ); ?></option>
								<?php if ( ! is_wp_error( $type_terms ) && ! empty( $type_terms ) ) : ?>
									<?php foreach ( $type_terms as $type_term ) : ?>
										<option value="<?php echo esc_attr( $type_term->slug ); ?>"<?php selected( $filter_state['type'], (string) $type_term->slug ); ?>>
											<?php echo esc_html( $type_term->name ); ?>
										</option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</label>

						<label class="property-archive__filter-control" for="property-filter-price">
							<span class="property-archive__filter-icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" focusable="false">
									<rect x="3" y="6" width="18" height="12" rx="2"></rect>
									<path d="M8 12H16"></path>
								</svg>
							</span>
							<select id="property-filter-price" class="property-archive__filter-select js-property-filter-select" name="price_range" aria-label="<?php esc_attr_e( 'Pricing range', 'real-estate-custom-theme' ); ?>">
								<option value=""><?php esc_html_e( 'Pricing Range', 'real-estate-custom-theme' ); ?></option>
								<?php foreach ( $price_ranges as $range_key => $range_data ) : ?>
									<option value="<?php echo esc_attr( $range_key ); ?>"<?php selected( $filter_state['price_range'], (string) $range_key ); ?>>
										<?php echo esc_html( $range_data['label'] ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</label>

						<label class="property-archive__filter-control" for="property-filter-size">
							<span class="property-archive__filter-icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" focusable="false">
									<path d="M4 8H20"></path>
									<path d="M4 16H20"></path>
									<path d="M8 4V20"></path>
									<path d="M16 4V20"></path>
								</svg>
							</span>
							<select id="property-filter-size" class="property-archive__filter-select js-property-filter-select" name="size_range" aria-label="<?php esc_attr_e( 'Property size', 'real-estate-custom-theme' ); ?>">
								<option value=""><?php esc_html_e( 'Property Size', 'real-estate-custom-theme' ); ?></option>
								<?php foreach ( $size_ranges as $range_key => $range_data ) : ?>
									<option value="<?php echo esc_attr( $range_key ); ?>"<?php selected( $filter_state['size_range'], (string) $range_key ); ?>>
										<?php echo esc_html( $range_data['label'] ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</label>

						<label class="property-archive__filter-control" for="property-filter-year">
							<span class="property-archive__filter-icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" focusable="false">
									<rect x="3" y="5" width="18" height="16" rx="2"></rect>
									<path d="M7 3V7"></path>
									<path d="M17 3V7"></path>
									<path d="M3 10H21"></path>
								</svg>
							</span>
							<select id="property-filter-year" class="property-archive__filter-select js-property-filter-select" name="build_year_range" aria-label="<?php esc_attr_e( 'Build year', 'real-estate-custom-theme' ); ?>">
								<option value=""><?php esc_html_e( 'Build Year', 'real-estate-custom-theme' ); ?></option>
								<?php foreach ( $year_ranges as $range_key => $range_data ) : ?>
									<option value="<?php echo esc_attr( $range_key ); ?>"<?php selected( $filter_state['build_year_range'], (string) $range_key ); ?>>
										<?php echo esc_html( $range_data['label'] ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</label>
					</div>

					<?php if ( ! empty( $active_filter_args ) ) : ?>
						<div class="property-archive__filter-actions">
							<a class="property-archive__filter-reset" href="<?php echo esc_url( $property_archive_url ); ?>"><?php esc_html_e( 'Clear Filters', 'real-estate-custom-theme' ); ?></a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</form>

		<?php if ( $featured_properties_query->have_posts() ) : ?>
			<section class="property-archive__featured section-shell" aria-labelledby="property-featured-title">
				<div class="section-head">
					<div>
						<p class="eyebrow"><?php esc_html_e( 'Featured Selection', 'real-estate-custom-theme' ); ?></p>
						<h2 id="property-featured-title"><?php esc_html_e( 'Discover a World of Possibilities', 'real-estate-custom-theme' ); ?></h2>
						<p class="section-head__description">
							<?php esc_html_e( 'Our portfolio of properties is as diverse as your dreams. Explore the following categories to find the perfect property that resonates with your vision of home', 'real-estate-custom-theme' ); ?>
						</p>
					</div>
				</div>

				<div class="featured-properties property-archive__featured-slider" data-featured-carousel>
					<div class="featured-properties__viewport">
						<div class="featured-properties__track">
							<?php
							while ( $featured_properties_query->have_posts() ) :
								$featured_properties_query->the_post();

								$property_id       = get_the_ID();
								$property_price    = trim( (string) get_post_meta( $property_id, 'property_price', true ) );
								$property_bedrooms = trim( (string) get_post_meta( $property_id, 'property_bedrooms', true ) );
								$property_bathroom = trim( (string) get_post_meta( $property_id, 'property_bathrooms', true ) );
								$property_type     = function_exists( 'real_estate_custom_theme_get_property_type_label' )
									? real_estate_custom_theme_get_property_type_label( $property_id )
									: trim( (string) get_post_meta( $property_id, 'property_type', true ) );

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

					<div class="featured-properties__controls property-archive__featured-controls">
						<p class="featured-properties__count">
							<span data-featured-current>01</span>
							<?php esc_html_e( 'of', 'real-estate-custom-theme' ); ?>
							<span data-featured-total>01</span>
						</p>
						<div class="featured-properties__actions">
							<button type="button" class="featured-properties__arrow" data-featured-prev aria-label="<?php esc_attr_e( 'Previous featured properties', 'real-estate-custom-theme' ); ?>">
								<svg class="featured-properties__arrow-icon" viewBox="0 0 24 24" focusable="false" aria-hidden="true">
									<path d="M15 6L9 12L15 18"></path>
								</svg>
							</button>
							<button type="button" class="featured-properties__arrow" data-featured-next aria-label="<?php esc_attr_e( 'Next featured properties', 'real-estate-custom-theme' ); ?>">
								<svg class="featured-properties__arrow-icon" viewBox="0 0 24 24" focusable="false" aria-hidden="true">
									<path d="M9 6L15 12L9 18"></path>
								</svg>
							</button>
						</div>
					</div>
				</div>
			</section>
			<?php
			wp_reset_postdata();
			?>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="property-archive__grid">
				<?php
				while ( have_posts() ) :
					the_post();

					$property_id       = get_the_ID();
					$property_price    = trim( (string) get_post_meta( $property_id, 'property_price', true ) );
					$property_bedrooms = trim( (string) get_post_meta( $property_id, 'property_bedrooms', true ) );
					$property_bathroom = trim( (string) get_post_meta( $property_id, 'property_bathrooms', true ) );
					$property_type     = function_exists( 'real_estate_custom_theme_get_property_type_label' )
						? real_estate_custom_theme_get_property_type_label( $property_id )
						: trim( (string) get_post_meta( $property_id, 'property_type', true ) );

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
							'text'     => wp_trim_words( get_the_excerpt(), 20 ),
							'has_more' => false,
						);
					?>
					<article <?php post_class( 'property-archive__card' ); ?>>
					<a class="property-archive__image" href="<?php the_permalink(); ?>">
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
					<div class="property-archive__body">
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="property-archive__excerpt">
							<?php echo esc_html( $card_excerpt_data['text'] ); ?>
							<?php if ( ! empty( $card_excerpt_data['has_more'] ) ) : ?>
								<a class="property-archive__read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'real-estate-custom-theme' ); ?></a>
							<?php endif; ?>
						</p>
						<ul class="property-archive__meta">
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
						<div class="property-archive__footer">
							<div>
								<span><?php esc_html_e( 'Price', 'real-estate-custom-theme' ); ?></span>
								<strong><?php echo '' !== $property_price ? esc_html( $property_price ) : esc_html__( 'Price on request', 'real-estate-custom-theme' ); ?></strong>
							</div>
							<a class="property-archive__cta" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View Property Details', 'real-estate-custom-theme' ); ?></a>
						</div>
					</div>
					</article>
				<?php endwhile; ?>
			</div>

			<div class="property-archive__pagination">
				<?php
				$pagination_args = array(
					'prev_text' => esc_html__( 'Previous', 'real-estate-custom-theme' ),
					'next_text' => esc_html__( 'Next', 'real-estate-custom-theme' ),
				);
				if ( ! empty( $active_filter_args ) ) {
					$pagination_args['add_args'] = $active_filter_args;
				}

				the_posts_pagination( $pagination_args );
				?>
			</div>
		<?php else : ?>
			<p><?php esc_html_e( 'No properties found yet.', 'real-estate-custom-theme' ); ?></p>
		<?php endif; ?>

		<section class="property-inquiry section-shell" aria-labelledby="property-inquiry-title">
			<div class="property-inquiry__head">
				<h2 id="property-inquiry-title"><?php esc_html_e( "Let's Make it Happen", 'real-estate-custom-theme' ); ?></h2>
				<p>
					<?php
					esc_html_e(
						"Ready to take the first step toward your dream property? Fill out the form below, and our real estate wizards will work their magic to find your perfect match. Don't wait; let's embark on this exciting journey together.",
						'real-estate-custom-theme'
					);
					?>
				</p>
			</div>

			<div class="property-inquiry__panel">
				<?php if ( '' !== $property_inquiry_form_shortcode ) : ?>
					<div class="property-inquiry__form-wrap">
						<?php echo do_shortcode( $property_inquiry_form_shortcode ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php else : ?>
					<p class="property-inquiry__fallback">
						<?php if ( current_user_can( 'manage_options' ) ) : ?>
							<?php esc_html_e( 'Property Inquiry Form is not available yet. Install and activate Contact Form 7, create a form titled "Property Inquiry Form", then connect reCAPTCHA v3 in Contact > Integration.', 'real-estate-custom-theme' ); ?>
						<?php else : ?>
							<?php esc_html_e( 'The inquiry form will be available soon.', 'real-estate-custom-theme' ); ?>
						<?php endif; ?>
					</p>
				<?php endif; ?>
			</div>
		</section>
	</section>
</main>

<?php
get_footer();
