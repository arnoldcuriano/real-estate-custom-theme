# Architecture

## Purpose
Document the current theme architecture, file ownership, and runtime flow.

## Scope
- Template structure used by the current theme
- Layout/alignment token model
- Header/footer ownership boundaries
- CSS/JS enqueue flow
- CPT/ACF module wiring ownership

## Source of truth files
- `functions.php`
- `header.php`
- `footer.php`
- `front-page.php`
- `archive-property.php`
- `archive-testimonial.php`
- `archive-faq.php`
- `inc/cpt-property.php`
- `inc/cpt-testimonial.php`
- `inc/cpt-faq.php`
- `inc/acf-fields-properties.php`
- `inc/acf-fields-testimonials.php`
- `inc/acf-fields-faq.php`
- `style.css`
- `css/header.css`
- `css/home.css`
- `js/home.js`

## Behavior and flow

### Template flow
- Front page: `front-page.php`
- Property archive: `archive-property.php`
- Testimonial archive: `archive-testimonial.php`
- FAQ archive: `archive-faq.php`
- Core defaults remain for:
  - `index.php`
  - `page.php`
  - `single.php`

Header and footer are loaded through `get_header()` and `get_footer()`.

### Layout token strategy
Global layout tokens are defined in `style.css` and reused across components:
- `--site-layout-max`
- `--site-layout-width`
- `--site-logo-line`
- `--site-content-inline-pad`

These tokens are used to align header, main content, and footer to a consistent logo/content line.

### CSS ownership boundaries
- `style.css`: base + global + footer + property archive + shared container rules
- `css/header.css`: front-page header/nav/top banner styles only
- `css/home.css`: front-page section styles and interactions only

### Script enqueue flow
From `functions.php`:
- Always enqueued:
  - Google Urbanist font stylesheet
  - `style.css`
  - `css/header.css`
  - `js/navigation.js`
- Front-page only:
  - `css/home.css`
  - `js/home.js`
  - `js/vendor/alpine.min.js` (deferred, dependent on home script)

### Content module architecture
- `property`, `testimonial`, and `faq` are registered as separate CPT modules in `inc/`.
- ACF local field groups are module-specific and code-registered in `inc/acf-fields-*.php`.
- Front-page dynamic sections reuse one shared carousel engine in `js/home.js`:
  - featured properties: `data-featured-carousel`
  - testimonials: `data-testimonials-carousel`
  - FAQs: `data-faq-carousel`

## Extension guidance
- Add sitewide component styles in `style.css` unless component-specific ownership is already defined.
- Keep header/nav changes in `css/header.css`.
- Keep front-page section styles in `css/home.css`.
- For new interactive front-page sections, use `js/home.js` and optional Alpine hooks; keep graceful fallback behavior.

## Troubleshooting
- Header/footer alignment drift:
  - verify shared tokens in `style.css`
  - verify `padding-inline` token usage in header/footer container selectors
- Missing front-page interactions:
  - confirm front page is configured as static page
  - confirm `js/home.js` and `js/vendor/alpine.min.js` are loaded

## Verification steps
- Confirm selectors are owned in the right file:
  - header selectors appear in `css/header.css`
  - footer selectors appear in `style.css`
  - front-page section selectors appear in `css/home.css`
- Validate syntax:
  - `C:\xampp\php\php.exe -l wp-content/themes/real-estate-custom-theme/functions.php`
  - `node --check wp-content/themes/real-estate-custom-theme/js/navigation.js`
