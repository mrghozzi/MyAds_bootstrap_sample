# MyAds Bootstrap Sample Theme

A modern, responsive Bootstrap 5.3.3 theme designed for the **MyAds v4.5.5+** platform.

## Description

The Bootstrap Sample theme serves as a fully featured, clean, and highly customizable frontend template for the MyAds community social network and advertising exchange. It includes complete styling and blade templates for all core MyAds modules, optimized for performance, accessibility, and modern user experience.

## Compatibility

- **MyAds Platform:** Version `4.5.5` or higher.
- **Frameworks/Libraries:** Bootstrap `5.3.3`, FontAwesome `6.x`, Vanilla JS.

## Key Features

1. **Responsive Design & Dark Mode:** Fluid grids and utility classes that adapt perfectly to mobile, tablet, and desktop screens with full Light/Dark mode and Arabic RTL support.
2. **Superdesign Private Messaging Overhaul:**
   - Real-time chat workspace (`/messages`, `/messages/{id}`) with conversation rail, instant search, and unread badge pulse.
   - Rich message bubbles with inline image lightbox previews, formatted file chips, and encryption notice badges.
   - Interactive composer with auto-expanding textarea, attachment preview dismiss bar, and full emoji picker with category tabs.
   - Dedicated `/messages/create` direct message initiation form.
3. **Live Theme Customizer (`THEME-07`):**
   - Bidirectional `postMessage` synchronization in the admin live theme customizer.
   - Dynamic CSS variable compilation mapped into Bootstrap 5 tokens.
4. **Real-Time Events Engine (SSE Live Stream — RT-04):**
   - Server-Sent Events `/live/stream` integration for instant message and notification alerts.
5. **Interactive Profile Settings & Badge Showcase:**
   - Client-side `FileReader` live previews for profile avatar and cover photo uploads.
   - Interactive badge showcase selector with real-time counter and 6-badge cap validation.
6. **Optimized Portal & Social Feed:**
   - Multi-format post composer (Text, Image Galleries, Links, Video, Audio, Files, Music, and Clips).
   - Dynamic quote reposting, reactions, and interactive profile popovers.
7. **Visit Exchange Anti-Fraud System:**
   - 7-layer anti-fraud visit interface with window focus tracking and math challenges.
8. **Centralized SEO Engine & PWA Support:**
   - Built-in layouts for the Free SEO Checker and PWA offline fallbacks.

## Directory Structure

```text
bootstrap-sample/
├── assets/                  # CSS styles, javascripts, images, and fonts
│   ├── css/                 # Custom CSS overrides and styling (messages.css, forum.css, etc.)
│   ├── js/                  # Vanilla javascript controllers and helpers (messages-app.js, live-events.js)
│   ├── img/                 # Screenshots and placeholders
│   └── webfonts/            # Local FontAwesome 6 icons for offline performance
├── views/                   # Laravel Blade templates namespaced as `theme::`
│   ├── auth/                # Login, registration, password recovery views
│   ├── layouts/             # Main parent layouts and partials
│   ├── messages/            # Chat and private messages inbox
│   ├── profile/             # Member profile, settings, badges, and history
│   ├── store/               # Product directory, sale pages, and knowledgebase wiki
│   ├── visits/              # Visit exchange / Traffic Surf pages
│   └── ...                  # Other module-specific views
├── theme.json               # Theme metadata configuration file
├── CHANGELOG.md             # Detailed version history and release notes
└── README.md                # This file
```

## Installation & Setup

1. Clone or download this repository into the `themes/` directory of your MyAds installation:
   ```bash
   cd path/to/myads/themes/
   git clone https://github.com/mrghozzi/MyAds_bootstrap_sample.git bootstrap-sample
   ```
2. Log into the MyAds Admin Panel.
3. Navigate to **System Settings** -> **General Settings**.
4. Locate the **Site Theme** dropdown, select **Bootstrap Sample**, and click **Save Changes**.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
