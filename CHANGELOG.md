# Changelog

All notable changes to the MyAds Bootstrap Sample theme will be documented in this file.

## [1.3.0] - 2026-07-31

### Added
- **Redesigned Store Page (`views/store/index.blade.php`):**
  - Upgraded categories grid to support all 9 store catalog categories (`script`, `themes`, `plugins`, `graphics`, `audio`, `video`, `ebooks`, `software`, `courses`) using `StoreCategoryCatalog::selectable()`.
  - Added custom color gradients, FontAwesome icons, product count badges, and active category state indicator (`ring-active`).
  - Redesigned Hero Banner with glassmorphism styling, user PTS balance counter badge (`auth()->user()->pts`), store title/description, and quick action buttons (`Add Product`, `Discount Codes`).
  - Enhanced Product Cards with 16:9 thumbnail container with scale on hover, multi-badge overlay (Price PTS, Sale Discount with strikethrough, Free badge, Suspended status badge), category pill link, publisher avatar & profile link (`profile.show`), and latest version tag badge (`$latestFile->name`).
  - Added responsive empty state card and Bootstrap 5 pagination styling.
- **Localization:**
  - Added `messages.try_adjusting_filters` translation key across all 14 supported language files (`ar`, `en`, `de`, `es`, `fa`, `fr`, `it`, `ja`, `pt`, `ru`, `sr`, `tr`, `zh_CN`, `zh_TW`).

## [1.2.0] - 2026-07-26

### Added
- **YouTube-Style Video Watch Page (`video.blade.php`):**
  - Created a dedicated YouTube-style watch page view for regular video posts (`s_type == 10`).
  - Integrated custom HTML5 video player with timeline scrubber, playback speed controls (0.5x-2x), volume range slider, fullscreen mode, and keyboard shortcuts.
  - Implemented publisher header card with hexagonal avatar styling fallback, verified badge, role label, and interactive follow/unfollow toggle.
  - Designed uniform compact action buttons with standalone popover flyouts (Reactions, Save, Share with link copy toast, Options/Report).
  - Integrated a 4-column suggested videos sidebar strictly filtered to video posts only.
- **Community Feed Publisher Enhancements (`add_post.blade.php`):**
  - Added **Video Title (`video_title`)** input field to the video section of the composer box.
  - Added **Video Thumbnail (`video_thumbnail`)** cover upload field with live client-side image preview.
- **Topic & Post Edit View (`edit.blade.php`):**
  - Added **Video Title** and **Video Thumbnail** cover image upload fields with current thumbnail preview when editing video posts.
- **Metadata & Config:**
  - Updated `theme.json` configuration and bumped minimum supported MyAds version to `4.5.0`.

## [1.1.0] - 2026-07-11

### Added
- **Visit Exchange Anti-Fraud Client-Side:**
  - Added support for 7-layer anti-fraud visit verification system.
  - Implemented window focus detection (`blur`/`focus` listeners) to automatically pause and resume the surf countdown.
  - Added client-side JS challenge solver matching backend verification standards.
  - Introduced AJAX-based verify workflow with distinct status bars (Viewing, Paused, Verifying, Success, Error) and animations.
  - Added a visual progress bar indicating time elapsed during active surfs.
- **Store & Knowledgebase Improvements:**
  - Added knowledgebase listing category filter selector support.
  - Styled Store index, category layouts, and product single detail pages.
- **Documentation & Metadata:**
  - Added a comprehensive `README.md` file.
  - Added standard `LICENSE` file.
  - Updated `theme.json` configuration structure and bumped the version.

### Fixed
- **Private Messaging Overhaul:**
  - Redesigned the chat composer input wrapper with optimized spacing and styling.
  - Replaced textareas with auto-sizing responsive input blocks.
  - Styled the attachment paperclip icon trigger and clean file preview indicators.
- **UI & Layout Fixes:**
  - Fixed Clips media player layout issues and rendering bugs.
  - Corrected quest achievement icons and missing SVG loader rendering.
  - Fixed badge showcase icon rendering within user profile grids.
  - Copied and linked FontAwesome `webfonts` directory directly to assets for stable offline performance.
  - Replaced external FontAwesome and Bootstrap CDNs with locally served files to permit offline PWA execution.
  - Restored legacy `styles.min.css` and `rtl.css` for pages not yet fully ported to the new theme.
  - Corrected group cover images and avatar path helpers in `views/groups/index.blade.php`.

## [1.0.0] - 2026-06-15

### Added
- Initial release of the Bootstrap Sample theme.
- Support for basic views: Homepage, Portal Feed, Auth layouts, and User Profiles.
- Bootstrap 5 integration with custom color variables.
