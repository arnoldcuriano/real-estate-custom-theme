# Content Model

## Purpose
Document current data structures used by the theme for dynamic home/archive content.

## Scope
- `property`, `testimonial`, and `faq` CPT contracts
- `faq_category` taxonomy contract
- ACF field contracts and fallback behavior
- Home section query rules
- Slug conflict rules for CPT archives

## Source of truth files
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
- `front-page.php`
- `archive-property.php`
- `archive-testimonial.php`
- `archive-faq.php`

## Behavior and flow

### Custom post type: `property`
Registered in `inc/cpt-property.php`.

Contract:
- post type key: `property`
- archive path: `/properties/`
- single path: `/property/{slug}`
- supports: title, editor, excerpt, thumbnail

### Custom post type: `testimonial`
Registered in `inc/cpt-testimonial.php`.

Contract:
- post type key: `testimonial`
- archive path: `/testimonials/`
- single path: `/testimonial/{slug}`
- supports: title, editor, excerpt, thumbnail

### Custom post type: `faq`
Registered in `inc/cpt-faq.php`.

Contract:
- post type key: `faq`
- archive path: `/faqs/`
- single path: `/faq/{slug}`
- supports: title, editor, excerpt

### Taxonomy: `faq_category`
Registered in `inc/cpt-faq.php`.

Contract:
- taxonomy key: `faq_category`
- assigned to CPT: `faq`
- query var: `faq_category`
- archive filtering used on FAQ archive via query param

### ACF field groups (code-registered)

#### Front page group
- `featured_section_description`

#### Property fields
- `property_price`
- `property_bedrooms`
- `property_bathrooms`
- `property_type`
- icon source/preset/custom fields for bedrooms, bathrooms, property type
- `property_card_excerpt`
- `featured_on_home`
- `featured_order`

#### Testimonial fields
- `testimonial_rating`
- `testimonial_quote`
- `client_name`
- `client_location`
- `client_photo`
- `is_featured`

#### FAQ fields
- `is_featured`
- `cta_label`

#### About page fields
- `achievements_title`
- `achievements_description`
- `achievements_items`:
  - `achievement_title`
  - `achievement_description`
- `steps_section_title`
- `steps_section_description`
- `process_steps`:
  - `step_number`
  - `step_title`
  - `step_description`
- optional CTA:
  - `cta_heading`
  - `cta_button_label`
  - `cta_button_link`

### Template field usage and fallbacks
- Properties:
  - `front-page.php`, `archive-property.php`, `single-property.php`
  - helper: `real_estate_custom_theme_get_property_card_excerpt_data()`
  - legacy fallbacks for `price`, `bedrooms`, `bathrooms`
- Testimonials:
  - `front-page.php`, `archive-testimonial.php`
  - helper fallbacks for quote/name/location/photo in `inc/testimonial-helpers.php`
- FAQs:
  - `front-page.php`, `archive-faq.php`
  - excerpt fallback: excerpt -> content trim (`real_estate_custom_theme_get_faq_excerpt()`)
  - CTA label fallback: `Read More` (`real_estate_custom_theme_get_faq_cta_label()`)
- About page:
  - `page-about-us.php`
  - ACF values override hardcoded defaults for achievements/process sections
  - featured process card is deterministic in template:
    - prefers a step label containing `03`
    - otherwise falls back to the third card index

### Home query logic
- Featured Properties:
  1. `property` with `featured_on_home = 1`
  2. sort by `featured_order` asc, then date desc
  3. fallback to latest `property` posts
- Testimonials:
  1. `testimonial` with `is_featured = 1`
  2. fallback to latest `testimonial` posts
- FAQs:
  1. `faq` with `is_featured = 1`
  2. no fallback to non-featured posts (empty state shown if none)

## Extension guidance
- Keep field names stable to avoid template breakage.
- If adding fields:
  1. register in ACF local group
  2. add fallback-safe template rendering
  3. update this document and `ACF-SETUP.md`
- If changing archive slugs, update:
  - nav/CTA URLs in templates
  - slug conflict notices in CPT modules
  - operations docs

## Troubleshooting
- ACF fields missing in admin:
  - ensure ACF plugin is active
- Archive route conflict:
  - rename static page slugs `properties`, `testimonials`, `faqs`
  - re-save permalinks
- FAQ archive filter not changing results:
  - verify term slug exists in `faq_category`
  - verify URL uses `?faq_category=term-slug`

## Verification steps
- Confirm CPT registrations:
  - `rg -n "register_post_type\\( 'property'|register_post_type\\( 'testimonial'|register_post_type\\( 'faq'" wp-content/themes/real-estate-custom-theme/inc`
- Confirm FAQ taxonomy registration:
  - `rg -n "register_taxonomy\\( 'faq_category'" wp-content/themes/real-estate-custom-theme/inc/cpt-faq.php`
- Confirm ACF keys:
  - `rg -n "featured_on_home|testimonial_rating|field_rect_faq_is_featured|field_rect_faq_cta_label" wp-content/themes/real-estate-custom-theme/inc`
- Confirm home hook usage:
  - `rg -n "data-featured-carousel|data-testimonials-carousel|data-faq-carousel" wp-content/themes/real-estate-custom-theme/front-page.php`

## Related
- `ACF-SETUP.md`
- `docs/setup-and-operations.md`
- `docs/frontend-behavior.md`
