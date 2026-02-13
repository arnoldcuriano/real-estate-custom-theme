# Setup and Operations

## Purpose
Provide operational setup and maintenance steps for developers and maintainers.

## Scope
- Theme activation and required WordPress settings
- ACF/plugin setup
- Permalink and route operations
- Common operational caveats in current implementation
- FAQ archive filtering operation

## Source of truth files
- `functions.php`
- `inc/cpt-property.php`
- `inc/cpt-testimonial.php`
- `inc/cpt-faq.php`
- `inc/acf-fields-properties.php`
- `inc/acf-fields-testimonials.php`
- `inc/acf-fields-faq.php`
- `inc/acf-fields-about.php`
- `front-page.php`
- `page-about-us.php`
- `archive-property.php`
- `archive-testimonial.php`
- `archive-faq.php`

## Setup flow

### 1) Activate theme
- Activate `real-estate-custom-theme` in WordPress.

### 2) Configure front page
- Set WordPress to use a static front page so `front-page.php` is used.
- Settings path: `Settings > Reading`.

### 3) Install ACF (optional but recommended)
- Install and activate `Advanced Custom Fields` plugin (free).
- Without ACF:
  - theme still renders with defaults and post meta fallbacks
  - editable ACF field UI will not be available

Detailed field setup:
- `ACF-SETUP.md`

### 4) Permalink refresh
- Save permalinks after CPT changes:
  - `Settings > Permalinks > Save Changes`

### 5) Properties route conflict check
- The `property` CPT archive uses `/properties/`.
- If a static page uses slug `properties`, rename that page slug.
- An admin warning notice is already implemented in `inc/cpt-property.php`.

### 6) Services + Contact placeholder pages
- Theme bootstrap seeds missing navigation placeholder pages:
  - `/services/`
  - `/contact-us/`
- Seed logic is implemented in `functions.php` and runs:
  - on `after_switch_theme`
  - on `init` (idempotent safety for already-active installs)

### 7) Testimonials and FAQs route conflict checks
- The `testimonial` CPT archive uses `/testimonials/`.
- The `faq` CPT archive uses `/faqs/`.
- If static pages use `testimonials` or `faqs` slugs, rename those pages.
- Admin warning notices are implemented in:
  - `inc/cpt-testimonial.php`
  - `inc/cpt-faq.php`

### 8) FAQ taxonomy filtering operation
- FAQ archive filter uses `faq_category` query parameter:
  - `/faqs/?faq_category=buying`
- Main archive query is filtered via `pre_get_posts` in `inc/cpt-faq.php`.
- Pagination preserves the active `faq_category` parameter.

## Operational caveats
- Depending on local permalink mode, archive URL may resolve as:
  - `/properties/`
  - `/testimonials/`
  - `/faqs/`
  - or `/index.php/properties/`
  - or `/index.php/testimonials/`
  - or `/index.php/faqs/`
- Featured cards rely on property posts and image/content metadata:
  - no image on a post uses fallback image helper from `functions.php`
- About page sections are ACF-optional:
  - if About ACF fields are empty, template falls back to built-in default content

## Troubleshooting
- `404` on properties archive:
  - re-save permalinks
  - verify static page slug conflict is resolved
- `404` on testimonials or FAQs archive:
  - re-save permalinks
  - verify `testimonials`/`faqs` static page slug conflicts are resolved
- Featured section empty:
  - verify published `property` posts exist
  - verify home page query fallback conditions
- FAQ section empty on home:
  - verify published `faq` posts exist
  - verify `is_featured` is enabled on FAQ posts (home FAQ query is featured-only)
- FAQ category filter returns no items:
  - verify FAQs are assigned to selected `faq_category`
  - verify URL param matches taxonomy term slug
- Missing editable fields:
  - activate ACF plugin

## Verification steps
- PHP lint:
  - `C:\xampp\php\php.exe -l wp-content/themes/real-estate-custom-theme/functions.php`
  - `C:\xampp\php\php.exe -l wp-content/themes/real-estate-custom-theme/front-page.php`
  - `C:\xampp\php\php.exe -l wp-content/themes/real-estate-custom-theme/archive-property.php`
- JS lint/syntax:
  - `node --check wp-content/themes/real-estate-custom-theme/js/home.js`
  - `node --check wp-content/themes/real-estate-custom-theme/js/navigation.js`
- Route checks:
  - `http://localhost/realestate/`
  - `http://localhost/realestate/about-us/`
  - `http://localhost/realestate/services/`
  - `http://localhost/realestate/contact-us/`
  - `http://localhost/realestate/properties/` or `http://localhost/realestate/index.php/properties/`
  - `http://localhost/realestate/testimonials/` or `http://localhost/realestate/index.php/testimonials/`
  - `http://localhost/realestate/faqs/` or `http://localhost/realestate/index.php/faqs/`
  - `http://localhost/realestate/faqs/?faq_category=your-term-slug`

## Related
- `README.md`
- `docs/architecture.md`
- `docs/content-model.md`
- `docs/frontend-behavior.md`
- `ACF-SETUP.md`
