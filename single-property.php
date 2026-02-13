<?php
/**
 * Template for single property posts.
 *
 * @package real-estate-custom-theme
 */

get_header();
?>

<main id="primary" class="site-main property-single">
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
		?>
		<article <?php post_class( 'property-single__article' ); ?>>
			<header class="property-single__head">
				<h1><?php the_title(); ?></h1>
				<?php if ( '' !== $property_price ) : ?>
					<p class="property-single__price"><?php echo esc_html( $property_price ); ?></p>
				<?php endif; ?>
			</header>

			<div class="property-single__media">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'large', array( 'loading' => 'eager' ) );
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
			</div>

			<ul class="property-single__meta">
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

			<div class="property-single__content">
				<?php the_content(); ?>
			</div>
		</article>
	<?php endwhile; ?>
</main>

<?php
get_footer();

