<?php
/**
 * The template for the Services placeholder page.
 *
 * @package real-estate-custom-theme
 */

get_header();
?>

<main id="primary" class="site-main nav-placeholder-page">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<section class="nav-placeholder-page__shell" aria-labelledby="nav-placeholder-title">
			<h1 id="nav-placeholder-title"><?php the_title(); ?></h1>
			<p><?php esc_html_e( 'This page is ready. Full content will be added soon.', 'real-estate-custom-theme' ); ?></p>
		</section>
	<?php endwhile; ?>
</main>

<?php
get_footer();
