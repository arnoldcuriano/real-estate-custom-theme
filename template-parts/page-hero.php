<?php
/**
 * Shared page hero component.
 *
 * Args:
 * - id (string, optional)
 * - title (string, required for meaningful output)
 * - description (string, optional)
 * - section_class (string, optional)
 * - inner_class (string, optional)
 *
 * @package real-estate-custom-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hero_args = isset( $args ) && is_array( $args ) ? $args : array();

$hero_id          = isset( $hero_args['id'] ) ? sanitize_html_class( (string) $hero_args['id'] ) : '';
$hero_title       = isset( $hero_args['title'] ) ? trim( (string) $hero_args['title'] ) : '';
$hero_description = isset( $hero_args['description'] ) ? trim( (string) $hero_args['description'] ) : '';
$section_class    = isset( $hero_args['section_class'] ) ? trim( (string) $hero_args['section_class'] ) : '';
$inner_class      = isset( $hero_args['inner_class'] ) ? trim( (string) $hero_args['inner_class'] ) : '';

if ( '' === $hero_id ) {
	$hero_id = 'page-hero-title';
}

if ( '' === $hero_title ) {
	return;
}

$hero_section_classes = trim( 'page-hero ' . $section_class );
$hero_inner_classes   = trim( 'page-hero__inner ' . $inner_class );
?>

<section class="<?php echo esc_attr( $hero_section_classes ); ?>" aria-labelledby="<?php echo esc_attr( $hero_id ); ?>">
	<div class="<?php echo esc_attr( $hero_inner_classes ); ?>">
		<h1 id="<?php echo esc_attr( $hero_id ); ?>" class="page-hero__title"><?php echo esc_html( $hero_title ); ?></h1>
		<?php if ( '' !== $hero_description ) : ?>
			<p class="page-hero__description"><?php echo wp_kses_post( $hero_description ); ?></p>
		<?php endif; ?>
	</div>
</section>
