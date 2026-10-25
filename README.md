# Elementor Custom Widgets

WordPress plugin with extended widgets and global features for Elementor.

## Requirements

- WordPress 6.8+
- Elementor (tested with 4.x)
- Elementor Pro only for **Animated Menu Background** (it extends the Pro Nav Menu
  widget). The plugin header still lists Pro as required; see `STATE.md`.
- PHP 8.0+

## Features

The plugin is split in two kinds of features, both manageable from the plugin dashboard:

### Widgets

New standalone widgets, registered in the **Custom Elementor Widgets** category:

- **Coverflow Slider** — based on the [DMmotionarts tutorial](https://dmmotionarts.com/coverflow-slider-carousel-elementor-swiperjs-slider-tutorial/),
  which sets up the SwiperJS Coverflow effect with looping, navigation/pagination and responsive breakpoints; adapted as an Elementor widget.
  The source can be obtained from the [original free slider template](https://dmmotionarts.com/product/custom-sliders-carousels-elementor-free-template/);
  neither the tutorial nor the product page specifies a reuse or redistribution license. This widget loads Swiper 11 from a CDN with SRI and `crossorigin`.
- **Toggle Content** — button that shows/hides a WYSIWYG content block, with styling
  controls for the button and the content alignment.
- **Draggable Boxes** — freely positionable content boxes. Visitors can drag
  each box with a mouse or touch input; focused boxes can also be moved with
  the arrow keys. Each widget instance constrains its boxes to its own canvas.
  Initial positions, canvas size, inertia, keyboard move distance, colors, and
  content are editable in Elementor.
  Pointer/touch dragging, inertia, and canvas containment come from
  [interact.js](https://github.com/taye/interact.js). The box markup and
  styling, arrow-key movement, keeping boxes inside a resized canvas, and
  screen-reader announcements are implemented by the widget.
- **Numeric Chart** — configurable bar, line, and doughnut charts. Add labeled
  numeric data points, choose a chart type and colors, and control the title,
  legend, grid, filled line area, and responsive chart height.
  Rendering, tooltips, legend, animation, and responsive resizing come from
  [Chart.js](https://github.com/chartjs/Chart.js); the widget maps Elementor
  controls to a Chart.js configuration. It currently exposes a single data
  series and three of Chart.js's chart types.

### Global configurations

Modifications that extend **existing** Elementor elements instead of adding new widgets.
Each configuration is opt-in from the dashboard and only registers its controls and
loads its assets when enabled:

- **Animated Border Beam** — adds an animated, light-beam style border to any
  Elementor element that has a **Border** section. Adapted from
  [border-beam](https://github.com/Jakubantalik/border-beam) by Jakub Antalik
  (MIT):

  - Widgets: **Advanced → Border** (`elementor/element/common/_section_border`)
  - Containers, Sections and Columns: **Style → Border**
    (`elementor/element/{container,section,column}/section_border`)

  Available options:

  - **Effect type**: Compact rotation (`sm`), Full border rotation (`md`), Full border rotation outside (`md-outside`),
    Bottom traveling line (`line`), Inner pulse (`pulse-inner`), Outside pulse (`pulse-outside`)
  - **Color palette**: Colorful, Monochrome, Ocean, Sunset
  - **Background theme**: Dark, Light, System preference (auto)
  - **Duration** (seconds), **Strength**, **Brightness**, **Saturation**, **Hue range** (degrees)
  - **Static colors**: disables hue shifting while keeping the border motion
  - Respects `prefers-reduced-motion` and pauses when the element is out of view
    (IntersectionObserver), so the animation loop only runs while visible.
  - The effect is intentionally not rendered while editing in Elementor; check it in
    *Preview* or on the frontend.

- **Animated Background** — soft, configurable gradient backgrounds drawn on a canvas
  behind the content of any element with a **Background** section
  (`elementor/element/{common/_section_background,container,section,column}/section_background`).
  The canvas sits behind the content with `pointer-events: none`, resolves Elementor
  global colors at runtime, respects `prefers-reduced-motion` and pauses while the tab
  is hidden.
- **Animated Menu Background** — the same kind of gradient for the Elementor Pro
  **Nav Menu** widget (`nav-menu/section_style_main-menu`). Requires Elementor Pro.

## Dashboard

The plugin adds an **Elementor Widgets** admin page (Settings via the WordPress Settings API)
where every widget and configuration can be enabled or disabled independently:

- `widgets/` are listed in the **Widgets** section (opt-in, default off).
- `configs/` are listed in the **Elementor configurations** section (opt-in, default off).

Disabled features do not register controls, widgets, or frontend assets.

## Project structure

```
elementor-widgets/
├── elementor-widgets.php          # Plugin entry point: loads dashboard, configs, widgets
├── widgets/                       # Standalone Elementor widgets
├── assets/                        # Frontend assets for the widgets (css/js)
│   └── vendor/                    # Bundled interact.js/Chart.js runtimes + licenses
├── configs/                       # Global Elementor modifications, not widgets
│   ├── border-beam.php            # Border Beam: registers controls + assets
│   ├── animated-background.php    # Animated Background: controls + assets
│   ├── animated-menu.php          # Animated Menu Background (Pro Nav Menu)
│   └── assets/                    # Config assets (css/js)
│       └── border-beam.LICENSE    # MIT license of the original border-beam library
├── dashboard/                     # Admin dashboard
│   ├── dashboard-configs.php      # Dashboard page + settings registration
│   ├── dash-configs/              # Per-feature settings (widgets, configs)
│   └── assets/                    # Dashboard-only admin styles
├── libs/                          # External read-only library source (local reference)
├── THIRD-PARTY-NOTICES.md         # Origin, authorship, and licenses of third-party works
├── CHANGELOG.md                   # User-visible changes
├── AGENTS.md                      # Conventions for AI agents and contributors
└── STATE.md                       # Handoff notes for in-progress work
```

Notes:

- `assets/` is reserved for widget assets; configuration assets live under `configs/assets/`.
- `assets/vendor/` contains the version-pinned, local browser bundles used by
  the Draggable Boxes and Numeric Chart widgets. Their corresponding MIT
  license texts ship beside the bundles.
- `libs/` contains local third-party source reference code, including
  interact.js 1.10.27, Chart.js 4.5.1, and border-beam 1.4.0. It is read-only
  and ignored by Git.
  See [`THIRD-PARTY-NOTICES.md`](THIRD-PARTY-NOTICES.md) for authorship,
  licenses, source URLs, and pinned commits.
- Config assets are enqueued on the frontend and the Elementor preview iframe only
  (`wp_enqueue_scripts`, `elementor/preview/*`) — never on the editor panel.

## Usage

1. Install and activate the plugin (requires Elementor).
2. Go to **Elementor Widgets** in the admin menu and enable the widgets/configurations you need.
3. In the Elementor editor:
   - **Coverflow Slider**, **Toggle Content**, **Draggable Boxes**, and
     **Numeric Chart** appear under *Custom Elementor Widgets*.
   - **Animated Border Beam** appears inside the **Border** section of any supported element
     (Advanced → Border on widgets, Style → Border on containers/sections/columns).
4. Toggle *Enable animated border* on and configure the effect.

## Known limitations

- **Animated Border Beam** covers the common Border sections only (Advanced → Border
  on widgets, Style → Border on containers/sections/columns). It does not extend the
  border controls inside each widget's own Style tab (for example a button border) and
  it does not support Atomic (Editor V4) widgets.
- `md-outside` and `pulse-outside` draw outside the element: a parent with
  `overflow: hidden` clips them (the control shows a notice).
- Enabled configurations load their CSS/JS on every frontend page, not only on pages
  that use them. Border Beam also uses a document-wide `MutationObserver`.
- **Animated Background** forces `overflow: hidden` on the element, which can clip
  child content that overflows on purpose.
- **Animated Menu Background** targets the classic Nav Menu widget; the newer *Menu*
  widget is not supported.
- **Coverflow Slider** slides are images only (the original template allowed any
  container content).
- **Numeric Chart** exposes one data series and three chart types.
- Renaming a widget (`get_name()`) breaks pages that already use it, because Elementor
  stores the name as `widgetType` in `_elementor_data`. Widget names must stay stable or
  ship with a migration.

## Troubleshooting

- **The plugin's widgets disappear from the editor.** Check whether the editor opened in
  Elementor **Safe Mode**: Safe Mode only loads the plugins in its allow list, so this
  plugin's widgets and controls are missing. Elementor offers Safe Mode when the editor
  takes more than about 30 seconds to load, which a slow local Docker setup can reach on
  its own. It is not necessarily a plugin error.
- **The editor panel keeps loading forever.** Usually a JavaScript exception in a widget
  script, or `start_controls_tabs()` nested inside a section that already has
  Normal/Hover tabs. Check the browser console.
- **CSS or JS changes do not show up.** Assets are versioned with `filemtime()`; if a
  change still does not appear, regenerate Elementor's CSS (Elementor → Tools →
  Regenerate CSS & Data) and clear any page cache.
- **A widget or setting stopped working after the rename.** Dashboard settings and the
  Border Beam, Animated Background and Animated Menu values saved before the
  `elementor-widgets` rename are not migrated (see `CHANGELOG.md`). If the plugin was
  installed under the old folder name, reactivate it.

## Third-party libraries

| Library | Version | Author / copyright | Used by | Source | License |
| --- | --- | --- | --- | --- | --- |
| interact.js | 1.10.27 | Taye Adeyemi | Draggable Boxes | [taye/interact.js](https://github.com/taye/interact.js) | MIT |
| Chart.js | 4.5.1 | Chart.js Contributors | Numeric Chart | [chartjs/Chart.js](https://github.com/chartjs/Chart.js) | MIT |
| border-beam | 1.4.0 | Jakub Antalik | Animated Border Beam (adapted port) | [Jakubantalik/border-beam](https://github.com/Jakubantalik/border-beam) | MIT |
| Swiper | 11.0.5 | Vladimir Kharlampidi | Coverflow Slider (CDN) | [nolimits4web/swiper](https://github.com/nolimits4web/swiper) | MIT |

[`THIRD-PARTY-NOTICES.md`](THIRD-PARTY-NOTICES.md) records the full
copyright lines, pinned commits, distributed files, and license text locations
for each library, plus the DMmotionarts tutorial the Coverflow Slider is based
on. interact.js and Chart.js run from the local bundles in `assets/vendor/`,
which ship with their license texts; those widgets make no CDN requests. The
Border Beam port ships the original MIT license as
`configs/assets/border-beam.LICENSE`.
