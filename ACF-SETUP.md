# ACF Setup

## Purpose

Configure ACF-backed editable fields used by the current theme implementation.

## Scope

- Front-page featured section description field
- Property card metadata fields
- Testimonial metadata fields
- Team member metadata fields
- FAQ metadata fields
- About page Achievements/Process fields
- Services page hero metadata fields
- Featured controls used by home carousel queries

## Source of truth files

- `inc/acf-fields-properties.php`
- `inc/acf-fields-testimonials.php`
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
- `property_bedrooms` (text/number)
- `property_bathrooms` (text/number)
- `property_type` (text/select)
- `property_card_excerpt` (textarea, optional)
- `featured_on_home` (true/false)
- `featured_order` (number, optional)

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

## Global slider logic contract

- Pagination is based on slide pages/groups, not raw item count.
- Navigation buttons are disabled when `totalPages <= 1`.
- Autoplay policy stays on current threshold behavior where implemented.

## Routing and slug note

- CPT archive route is `/properties/`
- CPT single route is `/property/{slug}`
- CPT archive route is `/testimonials/`
- CPT single route is `/testimonial/{slug}`
- CPT archive route is `/team-members/`
- CPT single route is `/team-member/{slug}`
- CPT archive route is `/faqs/`
- CPT single route is `/faq/{slug}`
- If static page slugs are `properties`, `testimonials`, `team-members`, or `faqs`, rename them to avoid route conflicts.

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
  - `rg -n "testimonial_rating|testimonial_quote|is_featured" wp-content/themes/real-estate-custom-theme/inc/acf-fields-testimonials.php`
  - `rg -n "position_title|profile_icon_source|profile_icon_platform|profile_icon_custom|social_links|cta_label|cta_url" wp-content/themes/real-estate-custom-theme/inc/acf-fields-team-members.php`
  - `rg -n "field_rect_faq_is_featured|field_rect_faq_cta_label" wp-content/themes/real-estate-custom-theme/inc/acf-fields-faq.php`
  - `rg -n "group_rect_about_sections|field_rect_achievements_title|field_rect_process_steps|field_rect_about_cta_heading" wp-content/themes/real-estate-custom-theme/inc/acf-fields-about.php`
  - `rg -n "group_rect_services_page_hero|services_hero_title|services_hero_description" wp-content/themes/real-estate-custom-theme/inc/acf-fields-services.php`
- Confirm template usage:
  - `rg -n "data-featured-carousel|data-testimonials-carousel|data-faq-carousel|featured_on_home|is_featured|cta_label" wp-content/themes/real-estate-custom-theme/front-page.php`
  - `rg -n "about-team|team_member|position_title|social_links|cta_url" wp-content/themes/real-estate-custom-theme/page-about-us.php`
  - `rg -n "services_hero_title|services_hero_description|data-quick-links-loop" wp-content/themes/real-estate-custom-theme/page-services.php`

## Related

- `README.md`
- `docs/content-model.md`
- `docs/setup-and-operations.md`
