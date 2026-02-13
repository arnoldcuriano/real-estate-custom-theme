<?php
/**
 * Testimonial archive template.
 *
 * @package real-estate-custom-theme
 */

get_header();
?>

<main id="primary" class="site-main testimonial-archive">
	<section class="testimonial-archive__shell" aria-labelledby="testimonial-archive-title">
		<header class="testimonial-archive__head">
			<h1 id="testimonial-archive-title"><?php post_type_archive_title(); ?></h1>
			<p><?php esc_html_e( 'Read success stories and heartfelt feedback from our valued clients.', 'real-estate-custom-theme' ); ?></p>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="testimonial-archive__grid">
				<?php
				while ( have_posts() ) :
					the_post();

					$testimonial_id      = get_the_ID();
					$testimonial_rating  = function_exists( 'real_estate_custom_theme_get_testimonial_rating' ) ? real_estate_custom_theme_get_testimonial_rating( $testimonial_id ) : 5;
					$testimonial_quote   = function_exists( 'real_estate_custom_theme_get_testimonial_quote' ) ? real_estate_custom_theme_get_testimonial_quote( $testimonial_id ) : wp_trim_words( get_the_excerpt(), 28 );
					$client_name         = function_exists( 'real_estate_custom_theme_get_testimonial_client_name' ) ? real_estate_custom_theme_get_testimonial_client_name( $testimonial_id ) : get_the_title();
					$client_location     = function_exists( 'real_estate_custom_theme_get_testimonial_client_location' ) ? real_estate_custom_theme_get_testimonial_client_location( $testimonial_id ) : '';
					$client_photo_url    = function_exists( 'real_estate_custom_theme_get_testimonial_photo_url' ) ? real_estate_custom_theme_get_testimonial_photo_url( $testimonial_id ) : '';
					?>
					<article <?php post_class( 'testimonial-card' ); ?>>
						<ul class="testimonial-card__stars" aria-label="<?php echo esc_attr( sprintf( __( 'Rated %d out of 5', 'real-estate-custom-theme' ), (int) $testimonial_rating ) ); ?>">
							<?php for ( $star_index = 1; $star_index <= 5; $star_index++ ) : ?>
								<li class="testimonial-card__star<?php echo $star_index <= $testimonial_rating ? ' is-filled' : ''; ?>" aria-hidden="true">&#9733;</li>
							<?php endfor; ?>
						</ul>
						<h2><?php the_title(); ?></h2>
						<p class="testimonial-card__quote"><?php echo esc_html( $testimonial_quote ); ?></p>
						<div class="testimonial-card__client">
							<?php if ( '' !== $client_photo_url ) : ?>
								<img src="<?php echo esc_url( $client_photo_url ); ?>" alt="<?php echo esc_attr( $client_name ); ?>" loading="lazy">
							<?php endif; ?>
							<div>
								<strong><?php echo esc_html( $client_name ); ?></strong>
								<?php if ( '' !== $client_location ) : ?>
									<span><?php echo esc_html( $client_location ); ?></span>
								<?php endif; ?>
							</div>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<div class="testimonial-archive__pagination">
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
			<p><?php esc_html_e( 'No testimonials found yet.', 'real-estate-custom-theme' ); ?></p>
		<?php endif; ?>
	</section>
</main>

<?php
get_footer();
