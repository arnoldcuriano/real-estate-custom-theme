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

- Single property page content module was refactored:
  - removed legacy meta chips + long content block below gallery
  - added native `Key Features` and `Amenities` details container
  - property title now shows location inline with pin icon (`Name + Location`)
  - added Google map embed section below details with native fallback logic (no extra plugin/API SDK install)
  - added dedicated single-property inquiry section below map using CF7 form title `Single Property Inquiry Form`
  - added `Comprehensive Pricing Details` section below inquiry with fixed 4-card accordion and icon-only chevron toggles
- Added native Property Details metabox (ACF-free) on `property` edit screen:
  - meta keys: `_rect_property_key_features`, `_rect_property_amenities`
  - supports add/remove/sort rows, predefined icons, and custom icon uploads
- Added native Property Pricing Details metabox (ACF-free) on `property` edit screen:
  - meta keys: `_rect_property_pricing_additional_fees`, `_rect_property_pricing_monthly_cost`, `_rect_property_pricing_total_initial_cost`, `_rect_property_pricing_monthly_expenses`
  - supports add/remove/sort rows in fixed panel order
- Property archive search now stays in branded property UI:
  - archive filter form submits hidden `post_type=property`
  - property searches render through archive-style layout (hero + filters + cards), not default WP sidebar search template
- Property inquiry form spacing/alignment normalized:
  - removed inherited paragraph margin offsets in contact-method row
  - standardized label/control spacing tokens for consistent field alignment
- Added property archive enhancement scripts:
  - `js/property-filters.js` (branded property filter dropdown behavior)
  - `js/property-inquiry-form.js` (branded inquiry form dropdown behavior)
- Refactored Services and Properties to reuse one shared page hero component:
  - template part: `template-parts/page-hero.php`
  - consumed by: `page-services.php`, `archive-property.php`
  - shared style system: `.page-hero*` in `style.css`
- Removed hover-lift card motion from Home page slider cards:
  - featured properties
  - testimonials
  - FAQs
  - card visuals now remain static on hover/focus.
- Updated footer `About Us` submenu links to route directly to About page section anchors:
  - `#about-journey-title`
  - `#about-achievements-title`
  - `#about-process-title`
  - `#about-team-title`
  - `#about-clients-title`
- Services hero heading and description are now ACF-editable on the Services page:
  - fields: `services_hero_title`, `services_hero_description`
  - safe fallback to default copy when fields are empty or ACF is inactive
- Quick-links card titles are normalized to explicit white text in shared module usage (Home + Services).
- Added About section anchor offset handling so sticky front header does not cover targeted section headings on jump links.
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
- Template parts:
  - `template-parts/page-hero.php`
- Theme modules:
  - `inc/cpt-property.php`
  - `inc/cpt-testimonial.php`
  - `inc/cpt-faq.php`
  - `inc/acf-fields-properties.php`
  - `inc/property-gallery-metabox.php`
  - `inc/property-details-metabox.php`
  - `inc/property-pricing-metabox.php`
  - `inc/acf-fields-testimonials.php`
  - `inc/acf-fields-faq.php`
  - `inc/acf-fields-about.php`
  - `inc/acf-fields-services.php`
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
  - `css/admin-property-gallery-metabox.css`
  - `css/admin-property-details-metabox.css`
  - `css/admin-property-pricing-metabox.css`
- Scripts:
  - `js/navigation.js`
  - `js/home.js`
  - `js/property-filters.js`
  - `js/property-inquiry-form.js`
  - `js/property-single-gallery.js`
  - `js/property-single-inquiry.js`
  - `js/property-single-pricing-accordion.js`
  - `js/admin-property-gallery-metabox.js`
  - `js/admin-property-details-metabox.js`
  - `js/admin-property-pricing-metabox.js`
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
- Native property gallery metabox:
  - `_rect_property_gallery_ids` (ordered CSV attachment IDs)
- Native property details metabox:
  - `_rect_property_key_features` (ordered rows)
  - `_rect_property_amenities` (ordered rows)
  - row contract:
    - `label`
    - `value` (optional)
    - `icon_source` (`predefined`/`custom`)
    - `icon_preset`
    - `icon_custom` (attachment ID, optional)
- Native property map embed override (optional):
  - `_rect_property_map_embed_url` (URL)
  - when empty, single property map iframe falls back to first `property_location` term
- Native property pricing metabox:
  - `_rect_property_pricing_additional_fees` (ordered rows)
  - `_rect_property_pricing_monthly_cost` (ordered rows)
  - `_rect_property_pricing_total_initial_cost` (ordered rows)
  - `_rect_property_pricing_monthly_expenses` (ordered rows)
  - row contract:
    - `label` (required)
    - `amount` (optional)
    - `note` (optional)
  - single-property pricing accordion always renders four cards in fixed order
  - empty panel fallback row: `Details will be updated soon.`
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
- Services page fields:
  - `services_hero_title`
  - `services_hero_description`
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

## Property Inquiry Form (Contact Form 7)

The `/properties/` page now renders a Contact Form 7 form section titled `Let's Make it Happen` after the listings/pagination.

### Required admin setup

1. Install and activate **Contact Form 7**.
2. Create a form with title: `Property Inquiry Form`.
3. In `Contact > Integration`, connect **reCAPTCHA v3** using Google site key + secret.
4. In the CF7 form **Additional Settings**, add:
   - `demo_mode: on`
   - `autop: off`

### Local reCAPTCHA testing (hosts alias)

Google reCAPTCHA may reject `localhost` in some setups. Use a local alias domain instead:

1. Add hosts entry (Windows file: `C:\Windows\System32\drivers\etc\hosts`):
   - `127.0.0.1 realestate.localdev`
2. Open the site using the alias domain, for example:
   - `http://realestate.localdev/realestate/`
3. In Google reCAPTCHA admin, add `realestate.localdev` to allowed domains.
4. Keep CF7 Integration keys connected in `Contact > Integration`.
5. Submit the form once and confirm no domain mismatch/integration error appears.

### CF7 form template (recommended)

Use this in the CF7 form editor to match theme styling and field mapping:

```text
<div class="property-inquiry__field">
  <label class="property-inquiry__label">First Name
    [text* first_name placeholder "Enter First Name"]
  </label>
</div>

<div class="property-inquiry__field">
  <label class="property-inquiry__label">Last Name
    [text* last_name placeholder "Enter Last Name"]
  </label>
</div>

<div class="property-inquiry__field">
  <label class="property-inquiry__label">Email
    [email* email placeholder "Enter your Email"]
  </label>
</div>

<div class="property-inquiry__field">
  <label class="property-inquiry__label">Phone
    [tel* phone placeholder "Enter Phone Number"]
  </label>
</div>

<div class="property-inquiry__field">
  <label class="property-inquiry__label">Preferred Location
    [select* preferred_location class:js-property-inquiry-select include_blank "Select Location" "Downtown" "Suburban" "Waterfront" "Countryside"]
  </label>
</div>

<div class="property-inquiry__field">
  <label class="property-inquiry__label">Property Type
    [select* property_type class:js-property-inquiry-select include_blank "Select Property Type" "Apartment" "Villa" "Townhouse" "Commercial"]
  </label>
</div>

<div class="property-inquiry__field">
  <label class="property-inquiry__label">No. of Bathrooms
    [select* bathrooms class:js-property-inquiry-select include_blank "Select no. of Bathrooms" "1" "2" "3" "4+"]
  </label>
</div>

<div class="property-inquiry__field">
  <label class="property-inquiry__label">No. of Bedrooms
    [select* bedrooms class:js-property-inquiry-select include_blank "Select no. of Bedrooms" "1" "2" "3" "4+"]
  </label>
</div>

<div class="property-inquiry__field property-inquiry__field--span-2">
  <label class="property-inquiry__label">Budget
    [select* budget class:js-property-inquiry-select include_blank "Select Budget" "Under $250k" "$250k - $500k" "$500k - $750k" "$750k - $1m" "Over $1m"]
  </label>
</div>

<div class="property-inquiry__field property-inquiry__field--span-2">
  <span class="property-inquiry__label">Preferred Contact Method</span>
  <div class="property-inquiry__contact-method">
    [radio preferred_contact_method use_label_element default:1 "Phone" "Email"]
  </div>
</div>

<div class="property-inquiry__field property-inquiry__field--span-2">
  <label class="property-inquiry__label">Preferred Phone
    [tel preferred_phone placeholder "Enter Your Number"]
  </label>
</div>

<div class="property-inquiry__field property-inquiry__field--span-2">
  <label class="property-inquiry__label">Preferred Email
    [email preferred_email placeholder "Enter Your Email"]
  </label>
</div>

<div class="property-inquiry__field property-inquiry__field--full">
  <label class="property-inquiry__label">Message
    [textarea message placeholder "Enter your Message here."]
  </label>
</div>

<div class="property-inquiry__field property-inquiry__field--terms property-inquiry__terms">
  [acceptance consent_terms]I agree with Terms of Use and Privacy Policy[/acceptance]
</div>

<div class="property-inquiry__field property-inquiry__field--submit">
  [submit class:property-inquiry__submit "Send Your Message"]
</div>
```

Important: if a select is not rendering as branded dropdown, keep `class:js-property-inquiry-select` on that `[select]` tag.  
Also, if you see raw text like `[acceptance ...]` on the frontend, use `[acceptance consent_terms]...[/acceptance]` exactly (do not use `acceptance*`).
Theme fallback: for this specific form title (`Property Inquiry Form`), invalid `[acceptance* ...]` is auto-normalized by theme hook to reduce local-authoring breakage.

## Single Property Inquiry Form (Contact Form 7)

The single property page (`single-property.php`) renders a dedicated inquiry section below the map.

### Required admin setup

1. Ensure **Contact Form 7** is active.
2. Create a form with title: `Single Property Inquiry Form`.
3. In the CF7 form **Additional Settings**, add:
   - `autop: off`
   - `demo_mode: on` (optional for local)

### CF7 form template (recommended)

Use this in the CF7 editor to match the single-property inquiry layout and prefill behavior:

```text
<div class="property-inquiry__field">
  <label class="property-inquiry__label">First Name
    [text* first_name placeholder "Enter First Name"]
  </label>
</div>

<div class="property-inquiry__field">
  <label class="property-inquiry__label">Last Name
    [text* last_name placeholder "Enter Last Name"]
  </label>
</div>

<div class="property-inquiry__field">
  <label class="property-inquiry__label">Email
    [email* email placeholder "Enter your Email"]
  </label>
</div>

<div class="property-inquiry__field">
  <label class="property-inquiry__label">Phone
    [tel* phone placeholder "Enter Phone Number"]
  </label>
</div>

<div class="property-inquiry__field property-inquiry__field--full">
  <label class="property-inquiry__label">Selected Property
    [text* selected_property placeholder "Selected Property"]
  </label>
</div>

<div class="property-inquiry__field property-inquiry__field--full">
  <label class="property-inquiry__label">Message
    [textarea message placeholder "Enter your Message here."]
  </label>
</div>

<div class="property-inquiry__field property-inquiry__field--terms property-inquiry__terms">
  [acceptance consent_terms]I agree with Terms of Use and Privacy Policy[/acceptance]
</div>

<div class="property-inquiry__field property-inquiry__field--submit">
  [submit class:property-inquiry__submit "Send Your Message"]
</div>
```

Notes:
- Theme JS auto-fills `selected_property` and enforces readonly on single-property pages.
- Keep field name exactly `selected_property` for prefill compatibility.
- Acceptance tag normalization also supports this form title if `acceptance*` is mistakenly used.

## Single Property Pricing Details (Native Metabox)

The single property page (`single-property.php`) now renders a dedicated pricing section below the inquiry form:

- heading + description
- note strip
- listing price summary
- four fixed accordion cards:
  1. Additional Fees
  2. Monthly Cost
  3. Total Initial Cost
  4. Monthly Expenses

### Pricing data source

Pricing content is managed in the native metabox `Property Pricing Details` on the property edit screen.

Storage keys:
- `_rect_property_pricing_additional_fees`
- `_rect_property_pricing_monthly_cost`
- `_rect_property_pricing_total_initial_cost`
- `_rect_property_pricing_monthly_expenses`

Each row supports:
- `label` (required)
- `amount` (optional)
- `note` (optional)

Behavior contract:
- accordion is single-open
- first card is open by default
- card header action uses icon-only chevron button
- if a panel has no rows, fallback text is shown:
  - `Details will be updated soon.`

## Validation checklist

- PHP syntax:
  - `C:\xampp\php\php.exe -l wp-content/themes/real-estate-custom-theme/functions.php`
  - `C:\xampp\php\php.exe -l wp-content/themes/real-estate-custom-theme/front-page.php`
  - `C:\xampp\php\php.exe -l wp-content/themes/real-estate-custom-theme/archive-property.php`
  - `C:\xampp\php\php.exe -l wp-content/themes/real-estate-custom-theme/single-property.php`
- JS syntax:
  - `node --check wp-content/themes/real-estate-custom-theme/js/home.js`
  - `node --check wp-content/themes/real-estate-custom-theme/js/navigation.js`
  - `node --check wp-content/themes/real-estate-custom-theme/js/property-filters.js`
  - `node --check wp-content/themes/real-estate-custom-theme/js/property-inquiry-form.js`
  - `node --check wp-content/themes/real-estate-custom-theme/js/property-single-inquiry.js`
  - `node --check wp-content/themes/real-estate-custom-theme/js/property-single-pricing-accordion.js`
- Route checks:
  - front page: `http://localhost/realestate/`
  - property archive (depends on permalink mode): `/properties/` or `/index.php/properties/`
  - branded property search: `/properties/?post_type=property&s=rustic`

## Detailed documentation

- Architecture: `docs/architecture.md`
- Content model: `docs/content-model.md`
- Frontend behavior: `docs/frontend-behavior.md`
- Setup and operations: `docs/setup-and-operations.md`
- ACF setup details: `ACF-SETUP.md`
