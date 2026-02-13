<?php
/**
 * About Us page template (slug-based).
 *
 * @package real-estate-custom-theme
 */

get_header();

$about_page_id       = (int) get_queried_object_id();
$about_image_url     = get_the_post_thumbnail_url( $about_page_id, 'full' );
$asset_base          = get_template_directory_uri() . '/assets/images/home';
$asset_path          = get_template_directory() . '/assets/images/home';
$fallback_hero_image = '';

if ( file_exists( $asset_path . '/hero-building.png' ) ) {
	$fallback_hero_image = $asset_base . '/hero-building.png';
}

$about_hero_media_url = ! empty( $about_image_url ) ? $about_image_url : $fallback_hero_image;

$achievements_title       = __( 'Our Achievements', 'real-estate-custom-theme' );
$achievements_description = __( 'Our milestones reflect years of dedication, client trust, and consistent performance in delivering exceptional real estate experiences.', 'real-estate-custom-theme' );
$achievements_items       = array(
	array(
		'title'       => __( '3+ Years of Excellence', 'real-estate-custom-theme' ),
		'description' => __( 'A strong track record of delivering seamless real estate experiences with professionalism and care.', 'real-estate-custom-theme' ),
	),
	array(
		'title'       => __( '10k+ Properties For Clients', 'real-estate-custom-theme' ),
		'description' => __( 'A broad portfolio curated to match diverse goals, lifestyles, and investment priorities.', 'real-estate-custom-theme' ),
	),
	array(
		'title'       => __( '200+ Happy Customers', 'real-estate-custom-theme' ),
		'description' => __( 'Hundreds of clients trusted us to find, buy, and manage properties with confidence.', 'real-estate-custom-theme' ),
	),
);

$steps_section_title       = __( 'Navigating the Estatein Experience', 'real-estate-custom-theme' );
$steps_section_description = __( 'A clear, guided process designed to keep every stage transparent, efficient, and aligned with your real estate goals.', 'real-estate-custom-theme' );
$process_steps             = array(
	array(
		'number'      => __( 'Step 01', 'real-estate-custom-theme' ),
		'title'       => __( 'Discover Your Needs', 'real-estate-custom-theme' ),
		'description' => __( 'We begin by understanding your budget, preferences, and timeline to define the right strategy.', 'real-estate-custom-theme' ),
	),
	array(
		'number'      => __( 'Step 02', 'real-estate-custom-theme' ),
		'title'       => __( 'Curate the Best Matches', 'real-estate-custom-theme' ),
		'description' => __( 'Our team shortlists properties that fit your requirements and long-term priorities.', 'real-estate-custom-theme' ),
	),
	array(
		'number'      => __( 'Step 03', 'real-estate-custom-theme' ),
		'title'       => __( 'Tour and Evaluate', 'real-estate-custom-theme' ),
		'description' => __( 'Visit top options, compare features, and receive expert guidance to evaluate each property.', 'real-estate-custom-theme' ),
	),
	array(
		'number'      => __( 'Step 04', 'real-estate-custom-theme' ),
		'title'       => __( 'Offer and Negotiate', 'real-estate-custom-theme' ),
		'description' => __( 'We handle offer strategy and negotiations to secure favorable terms with clarity.', 'real-estate-custom-theme' ),
	),
	array(
		'number'      => __( 'Step 05', 'real-estate-custom-theme' ),
		'title'       => __( 'Due Diligence', 'real-estate-custom-theme' ),
		'description' => __( 'Complete inspections, legal checks, and documentation with confidence before closing.', 'real-estate-custom-theme' ),
	),
	array(
		'number'      => __( 'Step 06', 'real-estate-custom-theme' ),
		'title'       => __( 'Close and Move Forward', 'real-estate-custom-theme' ),
		'description' => __( 'Finalize the transaction smoothly and transition into ownership with full support.', 'real-estate-custom-theme' ),
	),
);

$team_section_title       = __( 'Meet the Estatein Team', 'real-estate-custom-theme' );
$team_section_description = __( 'At Estatein, our success is driven by the dedication and expertise of our team. Get to know the people behind our mission to make your real estate dreams a reality.', 'real-estate-custom-theme' );

$clients_section_title       = __( 'Our Valued Clients', 'real-estate-custom-theme' );
$clients_section_description = __( 'At Estatein, we have had the privilege of working with a diverse range of clients across various industries. Here are some of the clients we have had the pleasure of serving.', 'real-estate-custom-theme' );

$about_cta_heading      = '';
$about_cta_button_label = '';
$about_cta_button_url   = '';
$about_cta_button_target = '';

if ( function_exists( 'get_field' ) ) {
	$acf_achievements_title = trim( (string) get_field( 'achievements_title', $about_page_id ) );
	if ( '' !== $acf_achievements_title ) {
		$achievements_title = $acf_achievements_title;
	}

	$acf_achievements_description = trim( (string) get_field( 'achievements_description', $about_page_id ) );
	if ( '' !== $acf_achievements_description ) {
		$achievements_description = $acf_achievements_description;
	}

	$acf_achievements_items = get_field( 'achievements_items', $about_page_id );
	if ( is_array( $acf_achievements_items ) && ! empty( $acf_achievements_items ) ) {
		$normalized_achievements_items = array();
		foreach ( $acf_achievements_items as $achievement_row ) {
			$achievement_title       = trim( (string) ( $achievement_row['achievement_title'] ?? '' ) );
			$achievement_description = trim( (string) ( $achievement_row['achievement_description'] ?? '' ) );

			if ( '' === $achievement_title && '' === $achievement_description ) {
				continue;
			}

			$normalized_achievements_items[] = array(
				'title'       => '' !== $achievement_title ? $achievement_title : __( 'Achievement', 'real-estate-custom-theme' ),
				'description' => $achievement_description,
			);
		}

		if ( ! empty( $normalized_achievements_items ) ) {
			$achievements_items = $normalized_achievements_items;
		}
	}

	$acf_steps_title = trim( (string) get_field( 'steps_section_title', $about_page_id ) );
	if ( '' !== $acf_steps_title ) {
		$steps_section_title = $acf_steps_title;
	}

	$acf_steps_description = trim( (string) get_field( 'steps_section_description', $about_page_id ) );
	if ( '' !== $acf_steps_description ) {
		$steps_section_description = $acf_steps_description;
	}

	$acf_process_steps = get_field( 'process_steps', $about_page_id );
	if ( is_array( $acf_process_steps ) && ! empty( $acf_process_steps ) ) {
		$normalized_process_steps = array();
		foreach ( $acf_process_steps as $index => $step_row ) {
			$step_number      = trim( (string) ( $step_row['step_number'] ?? '' ) );
			$step_title       = trim( (string) ( $step_row['step_title'] ?? '' ) );
			$step_description = trim( (string) ( $step_row['step_description'] ?? '' ) );

			if ( '' === $step_title && '' === $step_description ) {
				continue;
			}

			$normalized_process_steps[] = array(
				'number'      => '' !== $step_number ? $step_number : sprintf( __( 'Step %02d', 'real-estate-custom-theme' ), (int) $index + 1 ),
				'title'       => '' !== $step_title ? $step_title : __( 'Step', 'real-estate-custom-theme' ),
				'description' => $step_description,
			);
		}

		if ( ! empty( $normalized_process_steps ) ) {
			$process_steps = $normalized_process_steps;
		}
	}

	$about_cta_heading      = trim( (string) get_field( 'cta_heading', $about_page_id ) );
	$about_cta_button_label = trim( (string) get_field( 'cta_button_label', $about_page_id ) );
	$about_cta_link_field   = get_field( 'cta_button_link', $about_page_id );
	if ( is_array( $about_cta_link_field ) && ! empty( $about_cta_link_field['url'] ) ) {
		$about_cta_button_url    = (string) $about_cta_link_field['url'];
		$about_cta_button_target = ! empty( $about_cta_link_field['target'] ) ? (string) $about_cta_link_field['target'] : '_self';
		if ( '' === $about_cta_button_label && ! empty( $about_cta_link_field['title'] ) ) {
			$about_cta_button_label = (string) $about_cta_link_field['title'];
		}
	}
}

$featured_step_index = -1;
foreach ( $process_steps as $step_index => $step_data ) {
	$step_label = isset( $step_data['number'] ) ? (string) $step_data['number'] : '';
	if ( '' !== $step_label && 1 === preg_match( '/03/i', $step_label ) ) {
		$featured_step_index = (int) $step_index;
		break;
	}
}

if ( -1 === $featured_step_index ) {
	$featured_step_index = array_key_exists( 2, $process_steps ) ? 2 : 0;
}
?>

<main id="primary" class="site-main about-page">
	<section class="about-hero" aria-labelledby="about-journey-title">
		<div class="about-hero__container">
			<div class="about-hero__split">
				<div class="about-hero__content">
					<p class="about-eyebrow"><?php esc_html_e( 'Our Journey', 'real-estate-custom-theme' ); ?></p>
					<h1 id="about-journey-title"><?php esc_html_e( 'Our Journey', 'real-estate-custom-theme' ); ?></h1>
					<p class="about-hero__lead">
						<?php esc_html_e( 'Our story is one of continuous growth and evolution. We started as a small team with big dreams, determined to create a real estate platform that transcended the ordinary. Over the years, we have expanded our reach, forged valuable partnerships, and gained the trust of countless clients.', 'real-estate-custom-theme' ); ?>
					</p>

					<ul class="about-hero__stats" aria-label="<?php esc_attr_e( 'Company statistics', 'real-estate-custom-theme' ); ?>">
						<li><strong data-stat-count="200" data-stat-suffix="+">200+</strong><span><?php esc_html_e( 'Happy Customers', 'real-estate-custom-theme' ); ?></span></li>
						<li><strong data-stat-count="10" data-stat-suffix="k+">10k+</strong><span><?php esc_html_e( 'Properties For Clients', 'real-estate-custom-theme' ); ?></span></li>
						<li><strong data-stat-count="16" data-stat-suffix="+">16+</strong><span><?php esc_html_e( 'Years of Experience', 'real-estate-custom-theme' ); ?></span></li>
					</ul>
				</div>

				<div class="about-hero__media" aria-hidden="true">
					<?php if ( ! empty( $about_hero_media_url ) ) : ?>
						<img src="<?php echo esc_url( $about_hero_media_url ); ?>" alt="" loading="eager">
					<?php else : ?>
						<div class="about-hero__media-placeholder">
							<span><?php esc_html_e( 'Set a featured image for this About page to display the hero visual.', 'real-estate-custom-theme' ); ?></span>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<section class="about-values" aria-labelledby="about-values-title">
		<div class="about-values__shell">
			<div class="about-values__grid">
				<div class="about-values__intro">
					<p class="about-eyebrow"><?php esc_html_e( 'Our Values', 'real-estate-custom-theme' ); ?></p>
					<h2 id="about-values-title"><?php esc_html_e( 'Our Values', 'real-estate-custom-theme' ); ?></h2>
					<p>
					<?php esc_html_e( 'Our story is one of continuous growth and evolution. We started as a small team with big dreams, determined to create a real estate platform that transcended the ordinary. Over the years, we have expanded our reach, forged valuable partnerships, and gained the trust of countless clients.', 'real-estate-custom-theme' ); ?>
					</p>
				</div>

				<div class="about-values__cards">
					<article class="about-value-card">
						<span class="about-value-card__icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" focusable="false">
								<path d="M12 3.6L14.7 9.1L20.8 10L16.4 14.3L17.4 20.4L12 17.5L6.6 20.4L7.6 14.3L3.2 10L9.3 9.1L12 3.6Z"></path>
							</svg>
						</span>
						<h3><?php esc_html_e( 'Trust', 'real-estate-custom-theme' ); ?></h3>
						<p><?php esc_html_e( 'Trust is the cornerstone of every successful real estate transaction.', 'real-estate-custom-theme' ); ?></p>
					</article>
					<article class="about-value-card">
						<span class="about-value-card__icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" focusable="false">
								<path d="M3 9.5L12 5.5L21 9.5L12 13.5L3 9.5Z"></path>
								<path d="M7.5 11.5V14.7C7.5 15.9 9.5 17.3 12 17.3C14.5 17.3 16.5 15.9 16.5 14.7V11.5"></path>
								<path d="M21 10.5V15"></path>
							</svg>
						</span>
						<h3><?php esc_html_e( 'Excellence', 'real-estate-custom-theme' ); ?></h3>
						<p><?php esc_html_e( 'We set the bar high for ourselves, from the properties we list to the services we provide.', 'real-estate-custom-theme' ); ?></p>
					</article>
					<article class="about-value-card">
						<span class="about-value-card__icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" focusable="false">
								<circle cx="8.2" cy="8.4" r="3"></circle>
								<circle cx="16.4" cy="9.2" r="2.5"></circle>
								<path d="M2.8 19C3.7 16.4 5.7 15 8.2 15C10.7 15 12.8 16.4 13.6 19"></path>
								<path d="M13 19C13.7 17.2 15 16.2 16.8 16.2C18.6 16.2 20.1 17.2 20.8 19"></path>
							</svg>
						</span>
						<h3><?php esc_html_e( 'Client-Centric', 'real-estate-custom-theme' ); ?></h3>
						<p><?php esc_html_e( 'Your dreams and needs are at the center of our universe. We listen, understand, and deliver.', 'real-estate-custom-theme' ); ?></p>
					</article>
					<article class="about-value-card">
						<span class="about-value-card__icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" focusable="false">
								<path d="M12 3.6L14.7 9.1L20.8 10L16.4 14.3L17.4 20.4L12 17.5L6.6 20.4L7.6 14.3L3.2 10L9.3 9.1L12 3.6Z"></path>
							</svg>
						</span>
						<h3><?php esc_html_e( 'Our Commitment', 'real-estate-custom-theme' ); ?></h3>
						<p><?php esc_html_e( 'We are dedicated to providing you with the highest level of service, professionalism, and support.', 'real-estate-custom-theme' ); ?></p>
					</article>
				</div>
			</div>
		</div>
	</section>

	<section class="about-achievements" aria-labelledby="about-achievements-title">
		<div class="about-achievements__shell">
			<header class="about-section-head">
				<h2 id="about-achievements-title"><?php echo esc_html( $achievements_title ); ?></h2>
				<p><?php echo esc_html( $achievements_description ); ?></p>
			</header>

			<div class="about-achievements__grid">
				<?php foreach ( $achievements_items as $achievement_item ) : ?>
					<article class="about-achievement-card">
						<h3><?php echo esc_html( $achievement_item['title'] ); ?></h3>
						<p><?php echo esc_html( $achievement_item['description'] ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="about-process" aria-labelledby="about-process-title">
		<div class="about-process__shell">
			<header class="about-section-head">
				<h2 id="about-process-title"><?php echo esc_html( $steps_section_title ); ?></h2>
				<p><?php echo esc_html( $steps_section_description ); ?></p>
			</header>

			<div class="about-process__grid">
				<?php foreach ( $process_steps as $index => $process_step ) : ?>
					<article class="<?php echo esc_attr( $index === $featured_step_index ? 'about-step-card about-step-card--featured' : 'about-step-card' ); ?>">
						<p class="about-step-card__label">
							<?php
							echo esc_html(
								'' !== trim( (string) $process_step['number'] )
									? (string) $process_step['number']
									: sprintf( __( 'Step %02d', 'real-estate-custom-theme' ), (int) $index + 1 )
							);
							?>
						</p>
						<h3><?php echo esc_html( $process_step['title'] ); ?></h3>
						<p><?php echo esc_html( $process_step['description'] ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>

			<?php if ( '' !== $about_cta_heading || ( '' !== $about_cta_button_label && '' !== $about_cta_button_url ) ) : ?>
				<div class="about-process__cta">
					<div class="about-process__cta-content">
						<?php if ( '' !== $about_cta_heading ) : ?>
							<h3><?php echo esc_html( $about_cta_heading ); ?></h3>
						<?php endif; ?>
					</div>
					<?php if ( '' !== $about_cta_button_label && '' !== $about_cta_button_url ) : ?>
						<a class="about-process__cta-action" href="<?php echo esc_url( $about_cta_button_url ); ?>" target="<?php echo esc_attr( $about_cta_button_target ); ?>">
							<?php echo esc_html( $about_cta_button_label ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<section class="about-team" aria-labelledby="about-team-title">
		<div class="about-team__shell">
			<header class="about-section-head">
				<h2 id="about-team-title"><?php echo esc_html( $team_section_title ); ?></h2>
				<p><?php echo esc_html( $team_section_description ); ?></p>
			</header>

			<?php
			$team_query = new WP_Query(
				array(
					'post_type'           => 'team_member',
					'post_status'         => 'publish',
					'posts_per_page'      => -1,
					'ignore_sticky_posts' => true,
					'orderby'             => array(
						'menu_order' => 'ASC',
						'date'       => 'DESC',
					),
				)
			);

			if ( $team_query->have_posts() ) :
				?>
				<div class="about-team__grid">
					<?php
					while ( $team_query->have_posts() ) :
						$team_query->the_post();

						$team_member_id        = get_the_ID();
						$team_member_name      = get_the_title();
						$team_member_role      = function_exists( 'real_estate_custom_theme_get_team_member_position_title' ) ? real_estate_custom_theme_get_team_member_position_title( $team_member_id ) : '';
						$team_member_role      = '' !== $team_member_role ? $team_member_role : __( 'Team Member', 'real-estate-custom-theme' );
						$team_member_photo_url = function_exists( 'real_estate_custom_theme_get_team_member_photo_url' ) ? real_estate_custom_theme_get_team_member_photo_url( $team_member_id ) : '';
						$team_member_cta_label = function_exists( 'real_estate_custom_theme_get_team_member_cta_label' ) ? real_estate_custom_theme_get_team_member_cta_label( $team_member_id ) : __( 'Say Hello', 'real-estate-custom-theme' );
						$team_member_cta_url   = function_exists( 'real_estate_custom_theme_get_team_member_cta_url' ) ? real_estate_custom_theme_get_team_member_cta_url( $team_member_id ) : home_url( '/contact-us/' );
						$team_member_icon_data  = function_exists( 'real_estate_custom_theme_get_team_member_profile_icon_data' )
							? real_estate_custom_theme_get_team_member_profile_icon_data( $team_member_id )
							: array(
								'type'       => 'svg',
								'platform'   => 'linkedin',
								'icon_url'   => '',
								'link_url'   => '',
								'aria_label' => __( 'Open LinkedIn profile', 'real-estate-custom-theme' ),
							);

						$team_member_icon_type      = isset( $team_member_icon_data['type'] ) ? (string) $team_member_icon_data['type'] : 'svg';
						$team_member_icon_platform  = isset( $team_member_icon_data['platform'] ) ? sanitize_key( (string) $team_member_icon_data['platform'] ) : 'linkedin';
						$team_member_icon_custom    = isset( $team_member_icon_data['icon_url'] ) ? (string) $team_member_icon_data['icon_url'] : '';
						$team_member_icon_link      = isset( $team_member_icon_data['link_url'] ) ? (string) $team_member_icon_data['link_url'] : '';
						$team_member_icon_aria      = isset( $team_member_icon_data['aria_label'] ) ? (string) $team_member_icon_data['aria_label'] : __( 'Open LinkedIn profile', 'real-estate-custom-theme' );
						$team_member_icon_target    = 0 === strpos( $team_member_icon_link, 'http' ) ? '_blank' : '_self';
						$team_member_icon_rel       = '_blank' === $team_member_icon_target ? 'noopener noreferrer' : '';
						$team_member_cta_target    = 0 === strpos( $team_member_cta_url, 'http' ) ? '_blank' : '_self';
						$team_member_cta_rel       = '_blank' === $team_member_cta_target ? 'noopener noreferrer' : '';

						$team_member_initials = '';
						$team_name_parts      = preg_split( '/\s+/', trim( wp_strip_all_tags( $team_member_name ) ) );
						if ( is_array( $team_name_parts ) ) {
							foreach ( array_slice( $team_name_parts, 0, 2 ) as $name_part ) {
								$team_member_initials .= strtoupper( substr( (string) $name_part, 0, 1 ) );
							}
						}

						if ( '' === $team_member_initials ) {
							$team_member_initials = 'TM';
						}
						?>
						<article <?php post_class( 'about-team-card' ); ?>>
							<div class="about-team-card__media">
								<?php if ( '' !== $team_member_photo_url ) : ?>
									<img src="<?php echo esc_url( $team_member_photo_url ); ?>" alt="<?php echo esc_attr( $team_member_name ); ?>" loading="lazy">
								<?php else : ?>
									<div class="about-team-card__media-placeholder">
										<span><?php echo esc_html( $team_member_initials ); ?></span>
									</div>
								<?php endif; ?>

								<div class="about-team-card__social">
									<?php if ( '' !== $team_member_icon_link ) : ?>
										<a
											class="about-team-card__social-badge about-team-card__social-badge--<?php echo esc_attr( $team_member_icon_platform ); ?><?php echo 'image' === $team_member_icon_type ? ' about-team-card__social-badge--custom' : ''; ?>"
											href="<?php echo esc_url( $team_member_icon_link ); ?>"
											target="<?php echo esc_attr( $team_member_icon_target ); ?>"
											<?php if ( '' !== $team_member_icon_rel ) : ?>
												rel="<?php echo esc_attr( $team_member_icon_rel ); ?>"
											<?php endif; ?>
											aria-label="<?php echo esc_attr( $team_member_icon_aria ); ?>"
										>
											<span class="about-team-card__social-icon" aria-hidden="true">
												<?php if ( 'image' === $team_member_icon_type && '' !== $team_member_icon_custom ) : ?>
													<img src="<?php echo esc_url( $team_member_icon_custom ); ?>" alt="">
												<?php elseif ( 'x' === $team_member_icon_platform ) : ?>
													<svg viewBox="0 0 24 24" focusable="false">
														<path fill="currentColor" stroke="none" d="M18.2 3H21L14.9 9.9L22 21H16.5L12.2 14.5L6.5 21H3.7L10.2 13.7L3.4 3H9L12.9 8.9L18.2 3ZM17.3 19H18.9L8.1 4.9H6.4L17.3 19Z"></path>
													</svg>
												<?php elseif ( 'email' === $team_member_icon_platform ) : ?>
													<svg viewBox="0 0 24 24" focusable="false">
														<rect x="3.5" y="6.5" width="17" height="11" rx="1.2" ry="1.2" fill="none"></rect>
														<path fill="none" d="M4.6 7.5L12 13.2L19.4 7.5"></path>
													</svg>
												<?php else : ?>
													<svg viewBox="0 0 24 24" focusable="false">
														<path fill="currentColor" stroke="none" d="M6.9 8.2A1.9 1.9 0 1 1 6.9 4.4A1.9 1.9 0 0 1 6.9 8.2ZM5.2 9.6H8.6V19.2H5.2V9.6ZM10.4 9.6H13.6V10.9H13.7C14.1 10.1 15 9.3 16.7 9.3C20 9.3 20.6 11.4 20.6 14.1V19.2H17.2V14.8C17.2 13.7 17.2 12.3 15.7 12.3C14.2 12.3 14 13.4 14 14.7V19.2H10.4V9.6Z"></path>
													</svg>
												<?php endif; ?>
											</span>
										</a>
									<?php else : ?>
										<span class="about-team-card__social-badge about-team-card__social-badge--static<?php echo 'image' === $team_member_icon_type ? ' about-team-card__social-badge--custom' : ''; ?>" aria-hidden="true">
											<span class="about-team-card__social-icon" aria-hidden="true">
												<?php if ( 'image' === $team_member_icon_type && '' !== $team_member_icon_custom ) : ?>
													<img src="<?php echo esc_url( $team_member_icon_custom ); ?>" alt="">
												<?php elseif ( 'x' === $team_member_icon_platform ) : ?>
													<svg viewBox="0 0 24 24" focusable="false">
														<path fill="currentColor" stroke="none" d="M18.2 3H21L14.9 9.9L22 21H16.5L12.2 14.5L6.5 21H3.7L10.2 13.7L3.4 3H9L12.9 8.9L18.2 3ZM17.3 19H18.9L8.1 4.9H6.4L17.3 19Z"></path>
													</svg>
												<?php elseif ( 'email' === $team_member_icon_platform ) : ?>
													<svg viewBox="0 0 24 24" focusable="false">
														<rect x="3.5" y="6.5" width="17" height="11" rx="1.2" ry="1.2" fill="none"></rect>
														<path fill="none" d="M4.6 7.5L12 13.2L19.4 7.5"></path>
													</svg>
												<?php else : ?>
													<svg viewBox="0 0 24 24" focusable="false">
														<path fill="currentColor" stroke="none" d="M6.9 8.2A1.9 1.9 0 1 1 6.9 4.4A1.9 1.9 0 0 1 6.9 8.2ZM5.2 9.6H8.6V19.2H5.2V9.6ZM10.4 9.6H13.6V10.9H13.7C14.1 10.1 15 9.3 16.7 9.3C20 9.3 20.6 11.4 20.6 14.1V19.2H17.2V14.8C17.2 13.7 17.2 12.3 15.7 12.3C14.2 12.3 14 13.4 14 14.7V19.2H10.4V9.6Z"></path>
													</svg>
												<?php endif; ?>
											</span>
										</span>
									<?php endif; ?>
								</div>
							</div>

							<h3 class="about-team-card__name"><?php echo esc_html( $team_member_name ); ?></h3>
							<p class="about-team-card__role"><?php echo esc_html( $team_member_role ); ?></p>

							<div class="about-team-card__actions">
								<a
									class="about-team-card__cta"
									href="<?php echo esc_url( $team_member_cta_url ); ?>"
									target="<?php echo esc_attr( $team_member_cta_target ); ?>"
									<?php if ( '' !== $team_member_cta_rel ) : ?>
										rel="<?php echo esc_attr( $team_member_cta_rel ); ?>"
									<?php endif; ?>
								>
									<?php echo esc_html( $team_member_cta_label ); ?>
								</a>
								<a
									class="about-team-card__cta-icon"
									href="<?php echo esc_url( $team_member_cta_url ); ?>"
									target="<?php echo esc_attr( $team_member_cta_target ); ?>"
									<?php if ( '' !== $team_member_cta_rel ) : ?>
										rel="<?php echo esc_attr( $team_member_cta_rel ); ?>"
									<?php endif; ?>
									aria-label="<?php echo esc_attr( $team_member_cta_label ); ?>"
								>
									<svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
										<path d="M20.1 4.5L10.2 14.4"></path>
										<path d="M20.1 4.5L14.1 20L10.2 14.4L4.5 10.5L20.1 4.5Z"></path>
									</svg>
								</a>
							</div>
						</article>
					<?php endwhile; ?>
				</div>
				<?php
				wp_reset_postdata();
			else :
				?>
				<p><?php esc_html_e( 'No team members found yet.', 'real-estate-custom-theme' ); ?></p>
				<?php
			endif;
			?>
		</div>
	</section>

	<section class="about-clients-section" aria-labelledby="about-clients-title">
		<div class="about-clients__shell">
			<header class="about-section-head">
				<h2 id="about-clients-title"><?php echo esc_html( $clients_section_title ); ?></h2>
				<p><?php echo esc_html( $clients_section_description ); ?></p>
			</header>

			<?php
			$clients_query = new WP_Query(
				array(
					'post_type'           => 'client',
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

			if ( ! $clients_query->have_posts() ) {
				$clients_query = new WP_Query(
					array(
						'post_type'           => 'client',
						'post_status'         => 'publish',
						'posts_per_page'      => 12,
						'ignore_sticky_posts' => true,
						'orderby'             => array(
							'date' => 'DESC',
						),
					)
				);
			}

			if ( $clients_query->have_posts() ) :
				?>
				<div class="about-clients" data-about-carousel>
					<div class="about-clients__viewport" data-about-carousel-viewport>
						<div class="about-clients__track" data-about-carousel-track>
							<?php
							while ( $clients_query->have_posts() ) :
								$clients_query->the_post();

								$client_id            = get_the_ID();
								$client_since         = function_exists( 'real_estate_custom_theme_get_client_since' ) ? real_estate_custom_theme_get_client_since( $client_id ) : '';
								$client_domain        = function_exists( 'real_estate_custom_theme_get_client_domain' ) ? real_estate_custom_theme_get_client_domain( $client_id ) : '';
								$client_category      = function_exists( 'real_estate_custom_theme_get_client_category' ) ? real_estate_custom_theme_get_client_category( $client_id ) : '';
								$client_testimonial   = function_exists( 'real_estate_custom_theme_get_client_testimonial' ) ? real_estate_custom_theme_get_client_testimonial( $client_id ) : wp_trim_words( get_the_excerpt(), 24 );
								$client_website_url   = function_exists( 'real_estate_custom_theme_get_client_website_url' ) ? real_estate_custom_theme_get_client_website_url( $client_id ) : '';
								$client_domain        = '' !== $client_domain ? $client_domain : esc_html__( 'Not specified', 'real-estate-custom-theme' );
								$client_category      = '' !== $client_category ? $client_category : esc_html__( 'Not specified', 'real-estate-custom-theme' );
								$client_testimonial   = '' !== trim( $client_testimonial ) ? $client_testimonial : esc_html__( 'Client testimonial will appear here once provided.', 'real-estate-custom-theme' );
								?>
								<article <?php post_class( 'about-client-card about-clients__slide' ); ?> data-about-carousel-slide>
									<div class="about-client-card__head">
										<p class="about-client-card__since">
											<?php echo '' !== $client_since ? esc_html( $client_since ) : esc_html__( 'Since -', 'real-estate-custom-theme' ); ?>
										</p>
										<?php if ( '' !== $client_website_url ) : ?>
											<a class="about-client-card__visit" href="<?php echo esc_url( $client_website_url ); ?>" target="_blank" rel="noopener noreferrer">
												<?php esc_html_e( 'Visit Website', 'real-estate-custom-theme' ); ?>
											</a>
										<?php endif; ?>
									</div>

									<h3><?php the_title(); ?></h3>

									<ul class="about-client-card__meta">
										<li>
											<span class="about-client-card__meta-label">
												<span class="about-client-card__meta-icon" aria-hidden="true">
													<svg viewBox="0 0 24 24" focusable="false">
														<rect x="3.5" y="3.5" width="7" height="7" rx="1"></rect>
														<rect x="13.5" y="3.5" width="7" height="7" rx="1"></rect>
														<rect x="3.5" y="13.5" width="7" height="7" rx="1"></rect>
														<rect x="13.5" y="13.5" width="7" height="7" rx="1"></rect>
													</svg>
												</span>
												<?php esc_html_e( 'Domain', 'real-estate-custom-theme' ); ?>
											</span>
											<strong><?php echo esc_html( $client_domain ); ?></strong>
										</li>
										<li>
											<span class="about-client-card__meta-label">
												<span class="about-client-card__meta-icon" aria-hidden="true">
													<svg viewBox="0 0 24 24" focusable="false">
														<path d="M13.4 2L5.5 13H11L9.7 22L18.5 9H12.8L13.4 2Z"></path>
													</svg>
												</span>
												<?php esc_html_e( 'Category', 'real-estate-custom-theme' ); ?>
											</span>
											<strong><?php echo esc_html( $client_category ); ?></strong>
										</li>
									</ul>

									<div class="about-client-card__quote-wrap">
										<p class="about-client-card__quote-label"><?php esc_html_e( 'What They Said', 'real-estate-custom-theme' ); ?></p>
										<p class="about-client-card__quote"><?php echo esc_html( $client_testimonial ); ?></p>
									</div>
								</article>
							<?php endwhile; ?>
						</div>
					</div>

					<div class="about-clients__controls">
						<p class="about-clients__count">
							<span data-about-carousel-current>01</span>
							<?php esc_html_e( 'of', 'real-estate-custom-theme' ); ?>
							<span data-about-carousel-total>01</span>
						</p>
						<div class="about-clients__actions">
							<button type="button" class="about-clients__arrow" data-about-carousel-prev aria-label="<?php esc_attr_e( 'Previous clients', 'real-estate-custom-theme' ); ?>">
								<svg class="about-clients__arrow-icon" viewBox="0 0 24 24" focusable="false" aria-hidden="true">
									<path d="M15 6L9 12L15 18"></path>
								</svg>
							</button>
							<button type="button" class="about-clients__arrow" data-about-carousel-next aria-label="<?php esc_attr_e( 'Next clients', 'real-estate-custom-theme' ); ?>">
								<svg class="about-clients__arrow-icon" viewBox="0 0 24 24" focusable="false" aria-hidden="true">
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
				<p><?php esc_html_e( 'No clients found yet.', 'real-estate-custom-theme' ); ?></p>
				<?php
			endif;
			?>
		</div>
	</section>
</main>

<?php
get_footer();
