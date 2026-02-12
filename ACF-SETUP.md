# ACF Setup

## Purpose
Configure ACF-backed editable fields used by the current theme implementation.

## Scope
- Front-page featured section description field
- Property card metadata fields
- Featured-on-home controls used by the home carousel query

## Source of truth files
- `inc/acf-fields-properties.php`
- `front-page.php`
- `archive-property.php`
- `inc/cpt-property.php`

## Plugin requirement
Install and activate:
- **Advanced Custom Fields** (free)

The theme supports both:
- code-registered local field groups (already implemented), and
- manual field setup in ACF UI using the same field names.

## Field names (must match exactly)

### Front page field group
- `featured_section_description` (textarea/text)

### Property field group
- `property_price` (text)
- `property_bedrooms` (text/number)
- `property_bathrooms` (text/number)
- `property_type` (text/select)
- `property_card_excerpt` (textarea, optional)
- `featured_on_home` (true/false)
- `featured_order` (number, optional)

## How featured query uses these fields
Home featured cards in `front-page.php`:
1. query `property` posts where `featured_on_home = 1`
2. order by `featured_order` ascending, then date descending
3. fallback to latest published `property` posts if no featured flag exists

## Routing and slug note
- CPT archive route is `/properties/`
- CPT single route is `/property/{slug}`
- If a static page slug is `properties`, rename it to avoid route conflict.

After setup:
- Save permalinks: `Settings > Permalinks > Save Changes`

## Troubleshooting
- Fields not visible in admin:
  - confirm ACF is active
- Featured cards missing data:
  - confirm field names are exact
  - confirm posts are published
- Archive not resolving:
  - re-save permalinks
  - resolve `properties` slug conflicts

## Verification steps
- Confirm field registration keys:
  - `rg -n "featured_section_description|property_price|featured_on_home|featured_order" wp-content/themes/real-estate-custom-theme/inc/acf-fields-properties.php`
- Confirm template usage:
  - `rg -n "featured_section_description|property_price|property_bedrooms|featured_on_home" wp-content/themes/real-estate-custom-theme/front-page.php`

## Related
- `README.md`
- `docs/content-model.md`
- `docs/setup-and-operations.md`

