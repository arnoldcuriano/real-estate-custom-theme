# Frontend Behavior

## Purpose
Document current interactive frontend behavior and event flow.

## Scope
- Front-page quick-links infinite loop
- Front-page featured properties carousel
- Header top banner close and nav scroll/menu behavior
- Existing accessibility hooks in interactive elements

## Source of truth files
- `js/home.js`
- `js/navigation.js`
- `front-page.php`
- `header.php`
- `css/home.css`
- `css/header.css`

## Behavior and flow

### Quick links loop
- Hook: `data-quick-links-loop`
- Alpine component: `quickLinksLoop`
- Fallback initializer also exists when Alpine is unavailable.

Current behavior:
- clones original quick-link cards for seamless looping
- continuous horizontal animation
- pauses on hover/focus in viewport
- active card style updates on hover/focus

### Featured properties carousel
- Hook: `data-featured-carousel`
- Alpine component: `featuredPropertiesCarousel`
- Fallback initializer exists in `js/home.js`.

Current behavior:
- auto advances every ~4 seconds
- manual prev/next buttons
- infinite wrap via slide clones
- pauses on hover/focus and when tab is hidden
- counter text updates (`01 of N`)
- disables controls when there is only one slide

### Header and navigation behavior
- Front header initialized in `js/navigation.js`.

Current behavior:
- top announcement close button applies `is-dismissed`
- no localStorage persistence for dismiss state
- front header scroll state toggles nav shell styles
- mobile navigation toggles menu visibility
- menu closes on outside click

## Accessibility hooks currently present
- Buttons have `aria-label` in featured controls and top banner close.
- Menu toggle uses `aria-expanded`.
- Fallback controllers avoid hard dependency on Alpine for core behavior.

## Extension guidance
- Keep hook attributes stable:
  - `data-quick-links-loop`
  - `data-featured-carousel`
- Add new interactive components with:
  - deterministic selectors
  - Alpine registration + non-Alpine fallback where possible
  - pause behavior for hover/focus and hidden tab where animation is continuous

## Troubleshooting
- Carousel not moving:
  - verify `js/home.js` is loaded on front page
  - verify featured cards render and total slide count > 1
- Top banner close not working:
  - verify `js/navigation.js` is loaded
  - verify button class `.front-top-banner__close` exists in `header.php`

## Verification steps
- Confirm interactive hooks in template:
  - `rg -n "data-quick-links-loop|data-featured-carousel|x-data" wp-content/themes/real-estate-custom-theme/front-page.php`
- Validate JS syntax:
  - `node --check wp-content/themes/real-estate-custom-theme/js/home.js`
  - `node --check wp-content/themes/real-estate-custom-theme/js/navigation.js`
- Confirm component registrations:
  - `rg -n "window.Alpine.data\\(\" wp-content/themes/real-estate-custom-theme/js/home.js`

