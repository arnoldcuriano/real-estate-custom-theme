# ACF Setup

## Purpose

Configure ACF-backed editable fields used by the current theme implementation.

## Scope

- Front-page featured section description field
- Property card metadata fields
- Property archive filters (taxonomy + numeric ranges)
- Testimonial metadata fields
- Client metadata fields
- Team member metadata fields
- FAQ metadata fields
- About page Achievements/Process fields
- Services page hero metadata fields
- Featured controls used by home carousel queries

## Source of truth files

- `inc/acf-fields-properties.php`
- `inc/property-gallery-metabox.php`
- `inc/property-details-metabox.php`
- `inc/property-pricing-metabox.php`
- `inc/acf-fields-testimonials.php`
- `inc/acf-fields-clients.php`
- `inc/acf-fields-team-members.php`
- `inc/acf-fields-faq.php`
- `inc/acf-fields-about.php`
- `inc/acf-fields-services.php`
- `front-page.php`
- `archive-property.php`
- `archive-testimonial.php`
- `archive-faq.php`
- `inc/cpt-property.php`
- `inc/cpt-testimonial.php`
- `inc/cpt-client.php`
- `inc/cpt-team-member.php`
- `inc/cpt-faq.php`
- `page-about-us.php`
- `page-services.php`

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
- `price` (number, archive filter source)
- `size_sqm` (number, archive filter source)
- `build_year` (number, archive filter source)
- `property_bedrooms` (text/number)
- `property_bathrooms` (text/number)
- `property_type` (text/select, legacy fallback display source)
- `property_card_excerpt` (textarea, optional)
- `featured_on_home` (true/false)
- `featured_order` (number, optional)

### Property photo gallery (native metabox, not ACF)

- Meta key: `_rect_property_gallery_ids`
- Value format: CSV attachment IDs in saved order (example: `23,54,99`)
- Managed from Property edit screen metabox:
  - title: `Property Photos`
  - supports multi-select, drag reorder, remove
- Single-property gallery source chain:
  1. native metabox `_rect_property_gallery_ids`
  2. legacy ACF `property_gallery` (fallback only, if present)
  3. featured image fallback
  4. theme fallback asset

### Property details modules (native metabox, not ACF)

- Meta key: `_rect_property_key_features`
- Meta key: `_rect_property_amenities`
- Meta key: `_rect_property_map_embed_url` (optional URL override)
- Value format (both): ordered array rows saved as post meta (serialized by WordPress)
  - row fields:
    - `label` (required text)
    - `value` (optional text)
    - `icon_source` (`predefined`/`custom`)
    - `icon_preset` (default icon key)
    - `icon_custom` (attachment ID, optional)
- Managed from Property edit screen metabox:
  - title: `Property Details`
  - supports add/remove rows, drag reorder, icon preset per row
  - supports optional `Map Embed URL` field
- Single-property details source:
  - `single-property.php` reads `_rect_property_key_features` and `_rect_property_amenities`
  - sections render only when rows exist (`Key Features`, `Amenities`)
- Single-property map source:
  1. `_rect_property_map_embed_url` when provided
  2. fallback to first `property_location` term -> Google Maps iframe query embed
  3. map section hidden when no source is available

### Property pricing module (native metabox, not ACF)

- Meta key: `_rect_property_pricing_additional_fees`
- Meta key: `_rect_property_pricing_monthly_cost`
- Meta key: `_rect_property_pricing_total_initial_cost`
- Meta key: `_rect_property_pricing_monthly_expenses`
- Value format: ordered array rows saved as post meta (serialized by WordPress)
  - row fields:
    - `label` (required text)
    - `amount` (optional text)
    - `note` (optional text)
- Managed from Property edit screen metabox:
  - title: `Property Pricing Details`
  - supports add/remove rows and drag reorder per panel
- Single-property pricing source:
  - `single-property.php` reads all four pricing panel keys
  - pricing panels render in fixed order:
    1. Additional Fees
    2. Monthly Cost
    3. Total Initial Cost
    4. Monthly Expenses
  - empty panels render fallback row text: `Details will be updated soon.`

### Property taxonomy contract

- `property_location` (taxonomy, archive filter source)
- `property_type` (taxonomy, archive filter source)
- one-time backfill:
  - existing legacy `property_type` meta values are migrated into `property_type` taxonomy terms
  - completion flag option: `real_estate_custom_theme_property_type_backfilled`

### Testimonial field group

- `testimonial_rating` (select 1..5)
- `testimonial_quote` (textarea)
- `client_name` (text)
- `client_location` (text, optional)
- `client_photo` (image)
- `is_featured` (true/false)

### FAQ field group

- `is_featured` (true/false)
- `cta_label` (text, optional)

### Client field group

- `client_since` (text)
- `client_domain` (text)
- `client_category` (text)
- `client_industry` (text, legacy fallback)
- `client_service_type` (text, legacy fallback)
- `client_testimonial` (textarea)
- `client_website` (url)
- `is_featured` (true/false)

### Team Member field group

- `position_title` (text)
- `photo` (image, optional)
- `profile_icon_source` (select: default/custom)
- `profile_icon_platform` (select: linkedin/x/email, default linkedin)
- `profile_icon_custom` (image, optional)
- `social_links` (repeater)
  - `platform` (select: linkedin/x/email)
  - `url` (url)
- `cta_label` (text, optional)
- `cta_url` (text/url, optional; supports mailto)

### About page field group

- `achievements_title` (text)
- `achievements_description` (textarea)
- `achievements_items` (repeater)
  - `achievement_title` (text)
  - `achievement_description` (textarea)
- `steps_section_title` (text)
- `steps_section_description` (textarea)
- `process_steps` (repeater)
  - `step_number` (text, optional)
  - `step_title` (text)
  - `step_description` (textarea)
- optional process CTA:
  - `cta_heading` (text)
  - `cta_button_label` (text)
  - `cta_button_link` (link)

### Services page field group

- `services_hero_title` (text)
- `services_hero_description` (textarea)

## How featured query uses these fields

Home featured cards in `front-page.php`:

1. query `property` posts where `featured_on_home = 1`
2. order by `featured_order` ascending, then date descending
3. fallback to latest published `property` posts if no featured flag exists

Home testimonials in `front-page.php`:

1. query `testimonial` posts where `is_featured = 1`
2. fallback to latest published testimonials if none are featured

Home FAQs in `front-page.php`:

1. query `faq` posts where `is_featured = 1`
2. no fallback to non-featured FAQs (shows empty-state if none featured)

About clients in `page-about-us.php`:

1. query `client` posts where `is_featured = 1`
2. fallback to latest published `client` posts if none are featured
3. field fallback chain:
   - `client_domain` -> `client_industry`
   - `client_category` -> `client_service_type`

About Team cards in `page-about-us.php`:

1. query `team_member` posts (`post_status=publish`)
2. order by `menu_order ASC`, then `date DESC`
3. `photo` field fallback: featured image -> theme symbol asset
4. profile badge icon fallback:
   - custom icon (when `profile_icon_source=custom` and upload exists)
   - otherwise platform icon (`profile_icon_platform`)
   - if custom source has no icon upload, fallback to LinkedIn icon
5. `cta_url` fallback: first social link -> contact page URL

Services hero in `page-services.php`:

1. reads `services_hero_title` and `services_hero_description` from the Services page
2. fallback to default hardcoded copy when ACF is inactive or fields are empty

Property archive filters in `archive-property.php` + `inc/cpt-property.php`:

1. search/filter module submits via query string (`GET`)
2. accepted params:
   - `post_type=property` (keeps search in branded property archive experience)
   - `s`
   - `location` (`property_location` term slug)
   - `type` (`property_type` term slug)
   - `price_range`
   - `size_range`
   - `build_year_range`
3. archive query applies:
   - `tax_query` for `location` + `type`
   - `meta_query` for numeric range keys on `price`, `size_sqm`, `build_year`
4. pagination preserves active filter query args
5. branded property search routing:
   - when `is_search()` and `post_type=property`, results render through `archive-property.php`
   - prevents fallback to default WordPress search/sidebar layout

## Global slider logic contract

- Pagination is based on slide pages/groups, not raw item count.
- Navigation buttons are disabled when `totalPages <= 1`.
- Autoplay policy stays on current threshold behavior where implemented.

## Routing and slug note

- CPT archive route is `/properties/`
- CPT single route is `/property/{slug}`
- Property Location taxonomy archive uses `/property-location/{slug}`
- Property Type taxonomy archive uses `/property-type/{slug}`
- CPT archive route is `/testimonials/`
- CPT single route is `/testimonial/{slug}`
- CPT archive route is `/team-members/`
- CPT single route is `/team-member/{slug}`
- CPT archive route is `/faqs/`
- CPT single route is `/faq/{slug}`
- If static page slugs are `properties`, `property-location`, `property-type`, `testimonials`, `team-members`, or `faqs`, rename them to avoid route conflicts.

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
- Property search shows default WP search page/sidebar:
  - confirm properties search form includes hidden `post_type=property`
  - confirm `search.php` routes `post_type=property` searches to `archive-property.php`

## Verification steps

- Confirm field registration keys:
  - `rg -n "featured_section_description|property_price|price|size_sqm|build_year|featured_on_home|featured_order" wp-content/themes/real-estate-custom-theme/inc/acf-fields-properties.php`
  - `rg -n "testimonial_rating|testimonial_quote|is_featured" wp-content/themes/real-estate-custom-theme/inc/acf-fields-testimonials.php`
  - `rg -n "client_since|client_domain|client_category|client_industry|client_service_type|client_testimonial|client_website|is_featured" wp-content/themes/real-estate-custom-theme/inc/acf-fields-clients.php`
  - `rg -n "position_title|profile_icon_source|profile_icon_platform|profile_icon_custom|social_links|cta_label|cta_url" wp-content/themes/real-estate-custom-theme/inc/acf-fields-team-members.php`
  - `rg -n "field_rect_faq_is_featured|field_rect_faq_cta_label" wp-content/themes/real-estate-custom-theme/inc/acf-fields-faq.php`
  - `rg -n "group_rect_about_sections|field_rect_achievements_title|field_rect_process_steps|field_rect_about_cta_heading" wp-content/themes/real-estate-custom-theme/inc/acf-fields-about.php`
  - `rg -n "group_rect_services_page_hero|services_hero_title|services_hero_description" wp-content/themes/real-estate-custom-theme/inc/acf-fields-services.php`
  - `rg -n "_rect_property_gallery_ids|save_post_property|add_meta_box\\(|wp_enqueue_media" wp-content/themes/real-estate-custom-theme/inc/property-gallery-metabox.php`
  - `rg -n "_rect_property_key_features|_rect_property_amenities|_rect_property_map_embed_url|Property Details|save_post_property|wp_enqueue_media" wp-content/themes/real-estate-custom-theme/inc/property-details-metabox.php`
  - `rg -n "_rect_property_pricing_|Property Pricing Details|save_post_property|data-pricing-group" wp-content/themes/real-estate-custom-theme/inc/property-pricing-metabox.php`
- Confirm template usage:
  - `rg -n "data-featured-carousel|data-testimonials-carousel|data-faq-carousel|featured_on_home|is_featured|cta_label" wp-content/themes/real-estate-custom-theme/front-page.php`
  - `rg -n "post_type\\\" value=\\\"property\\\"|property_location|property_type|location|type|price_range|size_range|build_year_range" wp-content/themes/real-estate-custom-theme/archive-property.php wp-content/themes/real-estate-custom-theme/inc/cpt-property.php`
  - `rg -n "is_search\\(\\) && 'property'|locate_template\\( 'archive-property.php'" wp-content/themes/real-estate-custom-theme/search.php`
  - `rg -n "about-clients|client_domain|client_category|client_industry|client_service_type" wp-content/themes/real-estate-custom-theme/page-about-us.php`
  - `rg -n "about-team|team_member|position_title|social_links|cta_url" wp-content/themes/real-estate-custom-theme/page-about-us.php`
  - `rg -n "services_hero_title|services_hero_description|data-quick-links-loop" wp-content/themes/real-estate-custom-theme/page-services.php`

## Related

- `README.md`
- `docs/content-model.md`
- `docs/setup-and-operations.md`
