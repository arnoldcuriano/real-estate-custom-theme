# Content Model

## Purpose
Document data structures used by the theme for property and featured content.

## Scope
- `property` CPT contract
- ACF field contracts and fallback behavior
- Front-page featured query rules
- Slug conflict rule for `/properties/`

## Source of truth files
- `inc/cpt-property.php`
- `inc/acf-fields-properties.php`
- `front-page.php`
- `archive-property.php`
- `functions.php`

## Behavior and flow

### Custom post type: `property`
Registered in `inc/cpt-property.php`.

Current contract:
- post type key: `property`
- archive path: `/properties/`
- single path: `/property/{slug}`
- supports: title, editor, excerpt, thumbnail

### ACF field groups (code-registered)
Registered in `inc/acf-fields-properties.php` when ACF is active.

#### Front page group
- `featured_section_description`

#### Property card group
- `property_price`
- `property_bedrooms`
- `property_bathrooms`
- `property_type`
- `property_card_excerpt`
- `featured_on_home`
- `featured_order`

### Template field usage
`front-page.php` and `archive-property.php` map card content from property meta keys above.

Fallbacks in place:
- price fallback to legacy `price`
- bedrooms fallback to legacy `bedrooms`
- bathrooms fallback to legacy `bathrooms`
- card excerpt fallback to trimmed post excerpt
- image fallback via `real_estate_custom_theme_get_property_fallback_image_url()` in `functions.php`

### Featured section query logic
`front-page.php` Featured carousel query:
1. `property` posts where `featured_on_home = 1`
2. sort by `featured_order` ascending, then `date` descending
3. fallback to latest `property` posts if none are flagged

## Extension guidance
- Keep existing field names stable to avoid template breakage.
- If adding new display fields, add:
  1. ACF field registration
  2. template fallback behavior
  3. documentation update in this file
- If changing CPT slugs, update nav links and route docs.

## Troubleshooting
- No featured cards on home:
  - ensure `property` posts exist and are published
  - ensure `featured_on_home` is enabled for at least one post, or rely on fallback query
- Missing field controls in admin:
  - install/activate ACF plugin
- `/properties/` route conflict:
  - rename static page with slug `properties`
  - re-save permalinks

## Verification steps
- Confirm CPT registration exists:
  - `rg -n "register_post_type\\( 'property'" wp-content/themes/real-estate-custom-theme/inc/cpt-property.php`
- Confirm ACF fields are registered:
  - `rg -n "featured_section_description|property_price|featured_on_home" wp-content/themes/real-estate-custom-theme/inc/acf-fields-properties.php`
- Confirm template usage:
  - `rg -n "property_price|property_bedrooms|featured_on_home|featured_section_description" wp-content/themes/real-estate-custom-theme/front-page.php`

## Related
- `ACF-SETUP.md`
- `docs/setup-and-operations.md`

