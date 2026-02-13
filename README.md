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

## Tech stack
- WordPress PHP templates and hooks
- CSS split by ownership:
  - global: `style.css`
  - header/nav: `css/header.css`
  - front-page sections: `css/home.css`
- JavaScript:
  - `js/navigation.js` (header/nav interactions + global nav toggle)
  - `js/home.js` (front-page loops/carousels)
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
  - `archive-property.php`
  - `single-property.php`
  - `index.php`
  - `page.php`
  - `single.php`
- Theme modules:
  - `inc/cpt-property.php`
  - `inc/acf-fields-properties.php`
  - `inc/property-helpers.php`
  - `inc/template-functions.php`
  - `inc/template-tags.php`
- Styles:
  - `style.css`
  - `css/header.css`
  - `css/home.css`
- Scripts:
  - `js/navigation.js`
  - `js/home.js`

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
  - hero, quick-links, featured section cards/carousel, testimonials, FAQ, CTA

## Data model summary
- Custom post type:
  - slug: `property`
  - archive: `/properties/`
  - single: `/property/{slug}`
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
- Fallback behavior exists in templates if fields are empty.

## Front-page flow
`front-page.php` sections:
1. Hero
2. Quick links loop (`data-quick-links-loop`, Alpine/fallback logic)
3. Featured properties carousel (`data-featured-carousel`, auto + manual)
4. Testimonials
5. FAQ
6. CTA band
7. Footer group

## Setup quickstart
1. Activate the theme.
2. Set a static front page in WordPress so `front-page.php` is used.
3. Install and activate ACF if you need editable featured/property field values.
4. Save permalinks (`Settings > Permalinks > Save Changes`) after CPT changes.
5. If a static page uses slug `properties`, rename that page slug to avoid route conflicts.

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
