# Content Model

## Purpose
Document current data structures used by the theme for dynamic home/archive content.

## Scope
- `property`, `testimonial`, `client`, `team_member`, and `faq` CPT contracts
- `property_location`, `property_type`, and `faq_category` taxonomy contracts
- ACF field contracts and fallback behavior
- Home/About section query rules
- Slug conflict rules for CPT archives

## Source of truth files
- `inc/cpt-property.php`
- `inc/cpt-testimonial.php`
- `inc/cpt-client.php`
- `inc/cpt-team-member.php`
- `inc/cpt-faq.php`
- `inc/acf-fields-properties.php`
- `inc/property-gallery-metabox.php`
- `inc/property-details-metabox.php`
- `inc/acf-fields-testimonials.php`
- `inc/acf-fields-clients.php`
- `inc/acf-fields-team-members.php`
- `inc/acf-fields-faq.php`
- `inc/acf-fields-about.php`
- `inc/acf-fields-services.php`
- `inc/property-helpers.php`
- `inc/testimonial-helpers.php`
- `inc/client-helpers.php`
- `inc/team-member-helpers.php`
- `inc/faq-helpers.php`
- `js/property-single-inquiry.js`
- `front-page.php`
- `page-about-us.php`
- `page-services.php`
- `archive-property.php`
- `archive-testimonial.php`
- `archive-faq.php`
- `template-parts/page-hero.php`

## Behavior and flow

### Custom post type: `property`
Registered in `inc/cpt-property.php`.

Contract:
- post type key: `property`
- archive path: `/properties/`
- single path: `/property/{slug}`
- supports: title, editor, excerpt, thumbnail

### Taxonomy: `property_location`
Registered in `inc/cpt-property.php`.

Contract:
- taxonomy key: `property_location`
- assigned to CPT: `property`
- query var: `property_location`
- archive path: `/property-location/{slug}`
- used by properties archive filter (`location` query arg)

### Taxonomy: `property_type`
Registered in `inc/cpt-property.php`.

Contract:
- taxonomy key: `property_type`
- assigned to CPT: `property`
- query var: `property_type`
- archive path: `/property-type/{slug}`
- used by properties archive filter (`type` query arg)
- one-time backfill migrates legacy `property_type` meta values to taxonomy terms

### Custom post type: `testimonial`
Registered in `inc/cpt-testimonial.php`.

Contract:
- post type key: `testimonial`
- archive path: `/testimonials/`
- single path: `/testimonial/{slug}`
- supports: title, editor, excerpt, thumbnail

### Custom post type: `client`
Registered in `inc/cpt-client.php`.

Contract:
- post type key: `client`
- archive path: `/clients/`
- single path: `/client/{slug}`
- supports: title, editor, excerpt, thumbnail

### Custom post type: `team_member`
Registered in `inc/cpt-team-member.php`.

Contract:
- post type key: `team_member`
- archive path: `/team-members/`
- single path: `/team-member/{slug}`
- supports: title, editor, excerpt, thumbnail, page-attributes

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
- `price`
- `size_sqm`
- `build_year`
- `property_bedrooms`
- `property_bathrooms`
- `property_type`
- icon source/preset/custom fields for bedrooms, bathrooms, property type
- `property_card_excerpt`
- `featured_on_home`
- `featured_order`

#### Property photo gallery (native metabox contract)
- storage key: `_rect_property_gallery_ids`
- value: ordered CSV attachment IDs (example: `23,54,99`)
- editor UI: Property edit metabox (`Property Photos`) with multi-select, drag-sort, remove
- single-property gallery source chain:
  1. `_rect_property_gallery_ids` (primary)
  2. legacy ACF `property_gallery` (fallback, if present)
  3. featured image
  4. theme fallback image

#### Property details modules (native metabox contract)
- storage keys:
  - `_rect_property_key_features`
  - `_rect_property_amenities`
  - `_rect_property_map_embed_url` (optional URL override)
- value format:
  - ordered rows saved as post meta arrays (WordPress serialized)
  - each row:
    - `label` (required text)
    - `value` (optional text)
    - `icon_source` (`predefined`/`custom`)
    - `icon_preset` (default icon key)
    - `icon_custom` (attachment ID, optional)
- editor UI:
  - metabox title: `Property Details`
  - add/remove rows
  - drag-sort ordering
  - per-row default/custom icon selection
- single-property details source:
  1. `_rect_property_key_features`
  2. `_rect_property_amenities`
  - sections are hidden when corresponding row arrays are empty
- single-property map source:
  1. `_rect_property_map_embed_url` when provided
  2. fallback to first `property_location` term -> Google Maps iframe query URL
  3. hidden when no map URL and no location term

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

#### Client fields
- `client_since`
- `client_domain`
- `client_category`
- `client_industry`
- `client_service_type`
- `client_testimonial`
- `client_website`
- `is_featured`

#### Team Member fields
- `position_title`
- `photo`
- `profile_icon_source`
- `profile_icon_platform`
- `profile_icon_custom`
- `social_links`:
  - `platform`
  - `url`
- `cta_label`
- `cta_url`

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

#### Services page fields
- `services_hero_title`
- `services_hero_description`

### Template field usage and fallbacks
- Properties:
  - `front-page.php`, `archive-property.php`, `single-property.php`
  - helper: `real_estate_custom_theme_get_property_card_excerpt_data()`
  - helper: `real_estate_custom_theme_get_property_type_label()`
  - legacy fallbacks for `price`, `bedrooms`, `bathrooms`
  - single-property gallery data source:
    - primary: native metabox `_rect_property_gallery_ids`
    - fallback: legacy ACF `property_gallery` when metabox is empty
    - then featured image -> theme fallback image
  - single-property details container:
    - `Key Features` from `_rect_property_key_features`
    - `Amenities` from `_rect_property_amenities`
    - rows render icon + label (+ optional value)
  - single-property title row:
    - location renders inline after property name using first `property_location` term
  - single-property location map:
    - rendered below details cards
    - iframe-based Google Maps embed (no JS SDK/API key requirement)
    - custom URL override from `_rect_property_map_embed_url` if present
    - fallback to first `property_location` term when override is empty
  - single-property inquiry section:
    - rendered below the map section in `single-property.php`
    - CF7 form source resolved by helper:
      - `real_estate_custom_theme_get_single_property_inquiry_form_shortcode()`
      - fixed form title: `Single Property Inquiry Form`
    - selected property prefill contract:
      - template data attrs: `data-selected-property-title`, `data-selected-property-location`
      - JS enhancer `js/property-single-inquiry.js` sets readonly `selected_property` field value to:
        - `{title}, {location}` when location exists
        - `{title}` otherwise
  - property type display fallback:
    - taxonomy term (`property_type`)
    - fallback to legacy `property_type` meta text
  - archive heading/description rendered through shared hero component `template-parts/page-hero.php`
  - archive filter contract (submit/reload):
    - `post_type=property`, `s`, `location`, `type`, `price_range`, `size_range`, `build_year_range`
    - tax filters: `property_location`, `property_type`
    - numeric meta filters: `price`, `size_sqm`, `build_year`
    - pagination preserves active query args
  - property search rendering contract:
    - `is_search() && post_type=property` routes to `archive-property.php` presentation
    - avoids fallback/default WordPress search layout with sidebar widgets
  - property filter engine scope:
    - query filters apply for `is_post_type_archive('property')`
    - query filters also apply for `is_search()` when `post_type=property`
- Testimonials:
  - `front-page.php`, `archive-testimonial.php`
  - helper fallbacks for quote/name/location/photo in `inc/testimonial-helpers.php`
- FAQs:
  - `front-page.php`, `archive-faq.php`
  - excerpt fallback: excerpt -> content trim (`real_estate_custom_theme_get_faq_excerpt()`)
  - CTA label fallback: `Read More` (`real_estate_custom_theme_get_faq_cta_label()`)
- Clients:
  - `page-about-us.php`
  - featured-first query (`is_featured=1`) with fallback to latest clients
  - helper fallbacks in `inc/client-helpers.php` for since/domain/category/testimonial/url
  - field fallback chain:
    - `client_domain` -> `client_industry`
    - `client_category` -> `client_service_type`
- About page:
  - `page-about-us.php`
  - ACF values override hardcoded defaults for achievements/process sections
  - Team section uses `team_member` CPT query (`menu_order ASC`, `date DESC`)
  - Team helper fallbacks:
    - photo -> featured image -> theme symbol asset
    - profile icon:
      - custom upload when `profile_icon_source=custom`
      - platform icon when default source
      - fallback to LinkedIn when custom source has no upload
    - CTA URL -> first social link -> contact page
  - featured process card is deterministic in template:
    - prefers a step label containing `03`
    - otherwise falls back to the third card index
- Services page:
  - `page-services.php`
  - shared hero component: `template-parts/page-hero.php`
  - hero title/description source:
    - `services_hero_title`
    - `services_hero_description`
  - fallback to default template copy when fields are empty or ACF is inactive
  - quick-links loop reuses shared Home module styling/logic

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

### Slider behavior contract
- Counters are page-based (`01 of totalPages`) and not raw item-based.
- Navigation is enabled only when `totalPages > 1`.
- Autoplay policy follows existing threshold logic and is additionally gated by page availability.

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
  - rename static page slugs `properties`, `property-location`, `property-type`, `testimonials`, `faqs`
  - re-save permalinks
- FAQ archive filter not changing results:
  - verify term slug exists in `faq_category`
  - verify URL uses `?faq_category=term-slug`

## Verification steps
- Confirm CPT registrations:
  - `rg -n "register_post_type\\( 'property'|register_post_type\\( 'testimonial'|register_post_type\\( 'client'|register_post_type\\( 'team_member'|register_post_type\\( 'faq'" wp-content/themes/real-estate-custom-theme/inc`
- Confirm taxonomy registrations:
  - `rg -n "register_taxonomy\\( 'property_location'|register_taxonomy\\( 'property_type'|register_taxonomy\\( 'faq_category'" wp-content/themes/real-estate-custom-theme/inc/cpt-property.php wp-content/themes/real-estate-custom-theme/inc/cpt-faq.php`
- Confirm ACF keys:
  - `rg -n "featured_on_home|price|size_sqm|build_year|testimonial_rating|client_since|position_title|profile_icon_source|profile_icon_platform|profile_icon_custom|social_links|field_rect_faq_is_featured|field_rect_faq_cta_label" wp-content/themes/real-estate-custom-theme/inc`
- Confirm home hook usage:
  - `rg -n "data-featured-carousel|data-testimonials-carousel|data-faq-carousel" wp-content/themes/real-estate-custom-theme/front-page.php`

## Related
- `ACF-SETUP.md`
- `docs/setup-and-operations.md`
- `docs/frontend-behavior.md`
