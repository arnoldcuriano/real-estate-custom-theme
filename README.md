# real-estate-custom-theme

Developer documentation for the current implementation of this WordPress custom theme.

## Project overview
This theme is a custom real estate site built on top of a WordPress theme structure. It contains:
- a custom front page experience (`front-page.php`)
- a custom footer and front-page-specific header/navigation
- a `property` custom post type and property archive template
- ACF-powered content controls with safe fallbacks when ACF is not active

This documentation reflects only what currently exists in the codebase.

## Latest Changes
- Added About page implementation with reusable front-style header/footer integration:
  - template: `page-about-us.php`
  - styles: `css/about.css`
  - ACF group for achievements/process/optional CTA: `inc/acf-fields-about.php`
- Added Services and Contact Us placeholder pages and route seeding logic:
  - templates: `page-services.php`, `page-contact-us.php`
  - activation/init seeding in `functions.php`
- Added sitewide footer CTA block (`Start Your Real Estate Journey Today`) in shared footer component.
- Added reusable stat-count animation for Home + About:
  - script: `js/stats-counter.js`
- Featured properties carousel threshold behavior in `js/home.js`:
  - `items <= 4`: static layout, autoplay off, navigation controls disabled/muted.
  - `5 <= items <= 9`: manual navigation enabled, autoplay off.
  - `items > 9`: manual navigation enabled and autoplay on.
- Featured slider loop stability and responsive behavior improved to avoid empty slide spaces across desktop, tablet, and mobile.
- Featured slider nav arrows updated for consistent icon centering in controls.
- Property meta icon selection implemented for `Bedrooms`, `Bathrooms`, and `Property Type`:
  - supports preset icons and custom-upload icons via post meta/ACF fields.
  - renders consistently on featured cards, archive cards, and single property view.
- Property card excerpts now use fixed-length truncation with `Read More` linking to the property detail page when content exceeds the limit.
- New property rendering helpers and template:
  - `inc/property-helpers.php`
  - `single-property.php`
- Added reusable FAQ content module:
  - new `faq` CPT (`/faqs/` archive, `/faq/{slug}` single) and `faq_category` taxonomy
  - ACF fields `is_featured` and `cta_label`
  - homepage FAQ section now uses dynamic featured-only slider with shared carousel engine hooks
  - new FAQ archive template with category query-pill filtering and pagination

## Tech stack
- WordPress PHP templates and hooks
- CSS split by ownership:
  - global: `style.css`
  - header/nav: `css/header.css`
  - front-page sections: `css/home.css`
- JavaScript:
  - `js/navigation.js` (header/nav interactions + global nav toggle)
  - `js/home.js` (front-page loops/carousels)
  - `js/stats-counter.js` (animated stat counters on Home/About)
  - local Alpine bundle: `js/vendor/alpine.min.js` (front page only)
- Optional plugin dependency:
  - Advanced Custom Fields (ACF) for editable field groups

## Theme file map
- Bootstrap and enqueue:
  - `functions.php`
- Templates:
  - `header.php`
  - `footer.php`
  - `front-page.php`
  - `page-about-us.php`
  - `page-services.php`
  - `page-contact-us.php`
  - `archive-property.php`
  - `archive-testimonial.php`
  - `archive-faq.php`
  - `single-property.php`
  - `index.php`
  - `page.php`
  - `single.php`
- Theme modules:
  - `inc/cpt-property.php`
  - `inc/cpt-testimonial.php`
  - `inc/cpt-faq.php`
  - `inc/acf-fields-properties.php`
  - `inc/acf-fields-testimonials.php`
  - `inc/acf-fields-faq.php`
  - `inc/acf-fields-about.php`
  - `inc/property-helpers.php`
  - `inc/testimonial-helpers.php`
  - `inc/faq-helpers.php`
  - `inc/template-functions.php`
  - `inc/template-tags.php`
- Styles:
  - `style.css`
  - `css/header.css`
  - `css/home.css`
  - `css/about.css`
- Scripts:
  - `js/navigation.js`
  - `js/home.js`
  - `js/stats-counter.js`

## Asset and style ownership model
- `style.css`
  - base WordPress styles
  - sitewide layout tokens
  - global content container alignment
  - footer styles (`.home-footer*`)
  - property archive styles (`.property-archive*`)
- `css/header.css`
  - front-page header shell, top announcement, nav row, mobile nav
- `css/home.css`
  - hero, quick-links, featured section cards/carousel, testimonials, FAQ
- `css/about.css`
  - about page hero/journey/values/achievements/process section styles

## Data model summary
- Custom post types:
  - slug: `property`
  - archive: `/properties/`
  - single: `/property/{slug}`
  - slug: `testimonial`
  - archive: `/testimonials/`
  - single: `/testimonial/{slug}`
  - slug: `faq`
  - archive: `/faqs/`
  - single: `/faq/{slug}`
- Front-page featured section uses:
  - `featured_section_description` (ACF field on front page)
- Property card fields:
  - `property_price`
  - `property_bedrooms`
  - `property_bathrooms`
  - `property_type`
  - `property_card_excerpt`
  - `featured_on_home`
  - `featured_order`
- Testimonial fields:
  - `testimonial_rating`
  - `testimonial_quote`
  - `client_name`
  - `client_location`
  - `client_photo`
  - `is_featured`
- FAQ fields:
  - `is_featured`
  - `cta_label`
- About page fields:
  - `achievements_title`
  - `achievements_description`
  - `achievements_items` (`achievement_title`, `achievement_description`)
  - `steps_section_title`
  - `steps_section_description`
  - `process_steps` (`step_number`, `step_title`, `step_description`)
  - optional process CTA (`cta_heading`, `cta_button_label`, `cta_button_link`)
- Fallback behavior exists in templates if fields are empty.

## Front-page flow
`front-page.php` sections:
1. Hero
2. Quick links loop (`data-quick-links-loop`, Alpine/fallback logic)
3. Featured properties carousel (`data-featured-carousel`, auto + manual)
4. Testimonials carousel (`data-testimonials-carousel`, shared carousel engine)
5. FAQ carousel (`data-faq-carousel`, shared carousel engine)
6. Sitewide footer CTA + footer group (from shared `footer.php`)

## Setup quickstart
1. Activate the theme.
2. Set a static front page in WordPress so `front-page.php` is used.
3. Install and activate ACF if you need editable featured/property field values.
4. Save permalinks (`Settings > Permalinks > Save Changes`) after CPT changes.
5. If static pages use slugs `properties`, `testimonials`, or `faqs`, rename those page slugs to avoid route conflicts.

## Validation checklist
- PHP syntax:
  - `C:\xampp\php\php.exe -l wp-content/themes/real-estate-custom-theme/functions.php`
  - `C:\xampp\php\php.exe -l wp-content/themes/real-estate-custom-theme/front-page.php`
  - `C:\xampp\php\php.exe -l wp-content/themes/real-estate-custom-theme/archive-property.php`
- JS syntax:
  - `node --check wp-content/themes/real-estate-custom-theme/js/home.js`
  - `node --check wp-content/themes/real-estate-custom-theme/js/navigation.js`
- Route checks:
  - front page: `http://localhost/realestate/`
  - property archive (depends on permalink mode): `/properties/` or `/index.php/properties/`

## Detailed documentation
- Architecture: `docs/architecture.md`
- Content model: `docs/content-model.md`
- Frontend behavior: `docs/frontend-behavior.md`
- Setup and operations: `docs/setup-and-operations.md`
- ACF setup details: `ACF-SETUP.md`
