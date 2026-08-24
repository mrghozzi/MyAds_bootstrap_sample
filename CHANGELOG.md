# Changelog

All notable changes to the MyAds Bootstrap Sample theme will be documented in this file.

## [1.9.1] - 2026-08-24

### Added
- **Real-Time Events Engine (SSE Live Stream — RT-04) Integration (`views/layouts/master.blade.php`, `assets/js/live-events.js`, `assets/js/messages-app.js`):**
  - Integrated `LiveEventManager` client script (`assets/js/live-events.js`) with Server-Sent Events (SSE) `/live/stream` connection, live Bootstrap 5 toast alerts, dynamic notification & message badge counters, and progressive fallback polling.
  - Bound `myads:live-message` custom event listener inside `messages-app.js` for instant conversation refreshes on incoming messages.
  - Added `\App\Helpers\Hooks::do_action('theme_master_before_body_close')` hook invocation in `views/layouts/master.blade.php`.

### Metadata & Config
- Updated `theme.json` version to `1.9.1` and bumped `min_myads` compatibility to `4.5.3`.

## [1.9.0] - 2026-08-15

### Added
- **Developer Platform & Guides Modernization (`views/developer/index.blade.php`, `views/developer/guides.blade.php`, `views/developer/partials/styles.blade.php`):**
  - Modernized the Developer Platform landing page (`/developer`) with interactive feature cards (OAuth 2.0, REST API v1, JavaScript Widgets, Web Share API), dynamic rate limit badges (30 req/min), live multi-language Code Playground sandbox (PHP, Node.js, Python, cURL), and scopes category overview.
  - Upgraded Developer Guides (`/developer/guides`) with a sticky Table of Contents (TOC), corrected OAuth 2.0 Authorization Code Flow lifecycle and valid scope strings, multi-language SDK code examples, interactive 20+ endpoint directory with HTTP method badges (`GET`/`POST`) and parameter specifications, complete 26-scope OAuth 2.0 catalog table with sensitive warning badges, ready-to-use HTML snippets for 3 embed widgets (Follow, Profile, Feed), External Web Share API documentation with live link builder, and standard JSON response envelopes.
  - Added custom styling tokens for HTTP method badges (`GET`, `POST`, `PUT`, `DELETE`), endpoint cards, custom tables, and responsive code tabs in `styles.blade.php`.
- **Metadata & Config:**
  - Updated `theme.json` configuration version to `1.9.0`.

## [1.8.0] - 2026-08-15

### Added
- **Developer Platform Scopes & Categorization (`views/developer/partials/scope_grid.blade.php`, `views/oauth/authorize.blade.php`):**
  - Updated developer scope grid to display expanded OAuth 2.0 permissions grouped into 7 clean categories (`Identity & Profile`, `Content & Interactions`, `Messages & Notifications`, `Wallet & Rewards`, `Community & Media`, `Store & Advertising`, and `App Owner Integrations`) with Bootstrap 5 cards and category headers.
  - Added visual indicators for sensitive permissions (`badge bg-danger`) and code tags.
  - Synchronized OAuth consent screen (`views/oauth/authorize.blade.php`) with complete icon mappings across all 27 developer scopes.
- **Metadata & Config:**
  - Updated `theme.json` configuration version to `1.8.0`.

## [1.7.0] - 2026-08-11

### Security & Privacy
- **Member ID Privacy & Anti-Enumeration System (`views/profile/show.blade.php`, `views/profile/block_create.blade.php`, `views/profile/blocks.blade.php`, `views/profile/partials/*`, `views/partials/widgets/widget_members.blade.php`, `views/forum/video.blade.php`, `views/partials/ajax/user_popover.blade.php`):**
  - Updated all follow forms across profile, widgets, and video watch views to use `username` parameters instead of internal database `id`s (`route('profile.follow', $user->username)`).
  - Updated block (`route('profile.block.create', $user->username)`), store block (`route('profile.block.store', $user->username)`), unblock (`route('profile.block.destroy', $block->blockedUser->username)`), and report (`route('report.index', ['user' => $user->username])`) actions to use `username` instead of numeric member `id`s.
  - Updated inline JavaScript handlers in `user_popover.blade.php` to pass `username` string parameters to `toggleFollow()`.

### Metadata & Config
- Updated `theme.json` configuration version to `1.7.0`.

## [1.6.0] - 2026-08-08

### Added
- **Referral System Overhaul & ADStn Integration (`views/ads/referrals.blade.php`, `views/ads/referrals_list.blade.php`, `views/home.blade.php`):**
  - Upgraded referral views to match `@.superdesign` glassmorphic standards with stat counters, multi-size banner embed codes, multi-language support (i18n), 1-click clipboard copy tools, and direct sharing to 7 networks including **ADStn Network (`https://www.adstn.ovh/share`)** using `fa-brands fa-buysellads`.
  - Integrated high-visibility **Referral Hub Widget** (`modern-card` > `modern-service-block`) on home dashboard with crisp text contrast colors and live metric counters.
- **Metadata & Config:**
  - Updated `theme.json` configuration version to `1.6.0`.

## [1.5.0] - 2026-08-07

### Added
- **Continuous Audio Player Bar (`views/partials/continuous_audio_player.blade.php`):**
  - Integrated persistent floating glassmorphic audio player bar supporting continuous HTML5 audio playback across page navigations with `sessionStorage` state management, track title/avatar rendering, spinning disc animation, scrubber progress, and volume controls.
  - Included partial in `views/layouts/master.blade.php`.
- **Plugin Dynamic Widgets Integration (`<x-widget-column>`):**
  - Full compatibility with MYADS v4.5.2's `registered_plugin_widgets` filter hook allowing 3rd-party plugins under `plugins/` to inject custom widgets dynamically.
- **Metadata & Config:**
  - Updated `theme.json` configuration and bumped minimum supported MyAds version to `4.5.2`.

## [1.4.0] - 2026-08-06


### Added
- **Mini Floating Picture-in-Picture (PIP) Video Player (`views/forum/video.blade.php`):**
  - Integrated `IntersectionObserver` on `#videoStageCard` that automatically transitions active playing video to a floating corner card (`.v-mini-floating-player`) when scrolling down.
  - Added floating overlay controls providing expand-back to main player and PIP dismiss actions without interrupting playback position.
- **Shorts Clips Sound Tagging & Spinning Disc (`views/clips/partials/clips_list.blade.php` & `views/clips/index.blade.php`):**
  - Added sound tag pill (`messages.original_audio`) displaying sound title and creator.
  - Integrated CSS keyframe spinning audio disc animation (`.spinning-audio-disc`) in bottom corner overlay.
- **Metadata & Config:**
  - Updated `theme.json` configuration and bumped minimum supported MyAds version to `4.5.1`.

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
