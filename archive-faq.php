<?php
/**
 * FAQ archive template.
 *
 * @package real-estate-custom-theme
 */

get_header();

$faq_archive_url      = get_post_type_archive_link( 'faq' );
$selected_faq_category = (string) get_query_var( 'faq_category' );

if ( '' === $selected_faq_category && isset( $_GET['faq_category'] ) ) {
	$selected_faq_category = sanitize_text_field( wp_unslash( $_GET['faq_category'] ) );
}

$selected_faq_category = sanitize_title( $selected_faq_category );
if ( 'all' === $selected_faq_category ) {
	$selected_faq_category = '';
}

$faq_categories = get_terms(
	array(
		'taxonomy'   => 'faq_category',
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);
?>

<main id="primary" class="site-main faq-archive">
	<section class="faq-archive__shell" aria-labelledby="faq-archive-title">
		<header class="faq-archive__head">
			<h1 id="faq-archive-title"><?php post_type_archive_title(); ?></h1>
			<p><?php esc_html_e( 'Find answers to common questions about Estatein services, listings, and processes.', 'real-estate-custom-theme' ); ?></p>
		</header>

		<?php if ( ! is_wp_error( $faq_categories ) && ! empty( $faq_categories ) ) : ?>
			<nav class="faq-archive__filters" aria-label="<?php esc_attr_e( 'FAQ category filters', 'real-estate-custom-theme' ); ?>">
				<a class="faq-archive__filter<?php echo '' === $selected_faq_category ? ' is-active' : ''; ?>" href="<?php echo esc_url( $faq_archive_url ); ?>">
					<?php esc_html_e( 'All', 'real-estate-custom-theme' ); ?>
				</a>
				<?php foreach ( $faq_categories as $faq_category_term ) : ?>
					<?php
					$term_slug = (string) $faq_category_term->slug;
					$term_url  = add_query_arg( 'faq_category', $term_slug, $faq_archive_url );
					?>
					<a class="faq-archive__filter<?php echo $selected_faq_category === $term_slug ? ' is-active' : ''; ?>" href="<?php echo esc_url( $term_url ); ?>">
						<?php echo esc_html( $faq_category_term->name ); ?>
					</a>
				<?php endforeach; ?>
			</nav>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="faq-archive__grid">
				<?php
				while ( have_posts() ) :
					the_post();
					$faq_id         = get_the_ID();
					$faq_excerpt    = function_exists( 'real_estate_custom_theme_get_faq_excerpt' ) ? real_estate_custom_theme_get_faq_excerpt( $faq_id, get_the_excerpt(), 160 ) : wp_trim_words( get_the_excerpt(), 24 );
					$faq_cta_label  = function_exists( 'real_estate_custom_theme_get_faq_cta_label' ) ? real_estate_custom_theme_get_faq_cta_label( $faq_id ) : __( 'Read More', 'real-estate-custom-theme' );
					?>
					<article <?php post_class( 'faq-archive__card' ); ?>>
						<div class="faq-archive__body">
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p><?php echo esc_html( $faq_excerpt ); ?></p>
							<a class="faq-archive__cta" href="<?php the_permalink(); ?>"><?php echo esc_html( $faq_cta_label ); ?></a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<div class="faq-archive__pagination">
				<?php
				$faq_pagination_args = array(
					'prev_text' => esc_html__( 'Previous', 'real-estate-custom-theme' ),
					'next_text' => esc_html__( 'Next', 'real-estate-custom-theme' ),
				);

				if ( '' !== $selected_faq_category ) {
					$faq_pagination_args['add_args'] = array(
						'faq_category' => $selected_faq_category,
					);
				}

				the_posts_pagination( $faq_pagination_args );
				?>
			</div>
		<?php else : ?>
			<p><?php esc_html_e( 'No FAQs found yet.', 'real-estate-custom-theme' ); ?></p>
		<?php endif; ?>
	</section>
</main>

<?php
get_footer();
