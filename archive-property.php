<?php
/**
 * Property archive template.
 *
 * @package real-estate-custom-theme
 */

get_header();
?>

<main id="primary" class="site-main property-archive">
	<section class="property-archive__shell" aria-labelledby="property-archive-title">
		<header class="property-archive__head">
			<h1 id="property-archive-title"><?php post_type_archive_title(); ?></h1>
			<p><?php esc_html_e( 'Explore our latest listings and discover exceptional homes and investment opportunities.', 'real-estate-custom-theme' ); ?></p>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="property-archive__grid">
				<?php
				while ( have_posts() ) :
					the_post();

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
				the_posts_pagination(
					array(
						'prev_text' => esc_html__( 'Previous', 'real-estate-custom-theme' ),
						'next_text' => esc_html__( 'Next', 'real-estate-custom-theme' ),
					)
				);
				?>
			</div>
		<?php else : ?>
			<p><?php esc_html_e( 'No properties found yet.', 'real-estate-custom-theme' ); ?></p>
		<?php endif; ?>
	</section>
</main>

<?php
get_footer();
