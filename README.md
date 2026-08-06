# MyAds Bootstrap Sample Theme

A modern, responsive Bootstrap 5.3.3 theme designed for the **MyAds v4.5.1** platform.

## Description

The Bootstrap Sample theme serves as a fully featured, clean, and highly customizable frontend template for the MyAds community social network and advertising exchange. It includes complete styling and blade templates for all core MyAds modules, optimized for performance, accessibility, and modern user experience.

## Compatibility

- **MyAds Platform:** Version `4.5.1` or higher.
- **Frameworks/Libraries:** Bootstrap `5.3.3`, FontAwesome `6.x`, Vanilla JS.

## Key Features

1. **Responsive Design:** Fluid grids and utility classes that adapt perfectly to mobile, tablet, and desktop screens.
2. **Visit Exchange Anti-Fraud System:** Integrated 7-layer anti-fraud visit client-side interface featuring:
   - Window focus tracking (automatically pauses visit countdown when the window loses focus).
   - Math challenge solving before verification.
   - Secure AJAX token-based verification with status indicators and a custom progress bar.
3. **Optimized Portal & Social Feed:**
   - Multi-format post composer (Text, Image Galleries, Links, Video, Audio, Files, Music, and Clips).
   - Dynamic quote reposting and reactions.
   - Interactive profile popovers and skeleton loading placeholders.
4. **Improved Private Messaging:**
   - Redesigned chat interface with real-time styling.
   - Clean message entry wrapper with smooth resizeable textareas.
   - Visual attachment indicators for file uploads.
5. **Services Marketplace & Custom Ads:**
   - Seamless workflow layouts for members negotiating Custom Ad spaces and direct publisher-advertiser deals.
   - Clean interfaces for marketplace requests and structured provider offers.
6. **Centralized SEO Engine & PWA Support:**
   - Built-in layouts for the Free SEO Checker.
   - PWA styling and offline fallback assets.

## Directory Structure

```text
bootstrap-sample/
├── assets/                  # CSS styles, javascripts, images, and fonts
│   ├── css/                 # Custom CSS overrides and styling
│   ├── js/                  # Vanilla javascript controllers and helpers
│   ├── img/                 # Screenshots and placeholders
│   └── webfonts/            # Local FontAwesome 6 icons for offline performance
├── views/                   # Laravel Blade templates namespaced as `theme::`
│   ├── auth/                # Login, registration, password recovery views
│   ├── layouts/             # Main parent layouts and partials
│   ├── messages/            # Chat and private messages inbox
│   ├── store/               # Product directory, sale pages, and knowledgebase wiki
│   ├── visits/              # Visit exchange / Traffic Surf pages
│   └── ...                  # Other module-specific views
├── theme.json               # Theme metadata configuration file
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
