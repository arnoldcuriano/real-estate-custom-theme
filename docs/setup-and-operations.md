# Setup and Operations

## Purpose
Provide operational setup and maintenance steps for developers and maintainers.

## Scope
- Theme activation and required WordPress settings
- ACF/plugin setup
- Permalink and route operations
- Common operational caveats in current implementation

## Source of truth files
- `functions.php`
- `inc/cpt-property.php`
- `inc/acf-fields-properties.php`
- `front-page.php`
- `archive-property.php`

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

## Operational caveats
- Depending on local permalink mode, archive URL may resolve as:
  - `/properties/`
  - or `/index.php/properties/`
- Featured cards rely on property posts and image/content metadata:
  - no image on a post uses fallback image helper from `functions.php`

## Troubleshooting
- `404` on properties archive:
  - re-save permalinks
  - verify static page slug conflict is resolved
- Featured section empty:
  - verify published `property` posts exist
  - verify home page query fallback conditions
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
  - `http://localhost/realestate/properties/` or `http://localhost/realestate/index.php/properties/`

## Related
- `README.md`
- `docs/architecture.md`
- `docs/content-model.md`
- `docs/frontend-behavior.md`
- `ACF-SETUP.md`

