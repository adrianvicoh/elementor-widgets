# Changelog

All notable changes to this plugin are documented in this file.

## Unreleased

### Added

- Project contribution guidance in `AGENTS.md`.
- A `STATE.md` handoff document for in-progress work.
- Draggable Boxes, an Elementor widget that supports constrained mouse, touch,
  and keyboard box movement through interact.js 1.10.27. Boxes stay inside
  their canvas when it is resized, and the widget re-initialises cleanly after
  Elementor editor rerenders.
- Numeric Chart, an Elementor widget for bar, line, and doughnut charts using
  Chart.js 4.5.1. Editor rerenders replace the chart instead of leaving
  duplicate Chart.js instances.
- A *Third-party libraries* section in `README.md` and a versioned
  `THIRD-PARTY-NOTICES.md` recording the origin, author, copyright, license,
  and distribution of interact.js, Chart.js, border-beam, Swiper, and the
  DMmotionarts Coverflow tutorial.
- Local, version-pinned browser bundles and MIT license texts for interact.js
  and Chart.js under `assets/vendor/`.

### Changed

- The project is renamed from `elementor-avh-widgets` to `elementor-widgets`.
  The main plugin file is now `elementor-widgets.php`, the admin page slug is
  `elementor-widgets`, and the admin menu reads **Elementor Widgets**.
- The internal `avh` prefix is replaced: PHP functions and constants use
  `elementor_widgets_` / `ELEMENTOR_WIDGETS_`, asset handles and dashboard
  classes use `elementor-widgets-`, and Elementor control IDs, CSS classes and
  custom properties use `ew_` / `ew-`. This is a breaking change: dashboard
  settings and Border Beam, Animated Background and Animated Menu values saved
  in existing pages are not migrated.

### Fixed

- The Animated Border Beam port now credits the original border-beam library
  by Jakub Antalik: its MIT license ships as
  `configs/assets/border-beam.LICENSE`, and the adapted CSS and JavaScript
  carry an attribution header.

## Before the changelog (reconstructed)

This file was created on 2026-10-17. Earlier changes are reconstructed from the
Git history and from the AI agent sessions used to build the plugin; dates are
commit dates.

- **2026-03-29** — Plugin created as `elementor-avh-widgets` from earlier
  experiments.
- **2026-04-07 to 2026-04-09** — **Expanded Content Button**: a button that shows
  and hides a content block, with layout, size and full style controls and live
  preview in the editor.
- **2026-04-13** — **Animated Carousel**: centred carousel whose active slide is
  larger than its neighbours. A first version extending Elementor Pro's Nested
  Carousel was dropped in favour of an image carousel on `Widget_Base`.
- **2026-04-28** — Style controls of Expanded Content Button reorganised into
  separate sections (nested control tabs made the editor panel hang); the plugin
  header started listing Elementor Pro as required.
- **2026-04-29** — **Coverflow Slider**, based on the DMmotionarts tutorial, with
  Swiper 11 from a CDN and SRI attributes.
- **2026-07-19** — Expanded Content Button and Animated Carousel removed.
- **2026-07-22** — **Toggle Content** widget.
- **2026-07-23** — First removal of the `avh` prefixes; **dashboard** to enable or
  disable each widget, with Elementor icons (card layout on 2026-08-02).
- **2026-08-02** — **Animated Border Beam**, a port of the border-beam library,
  injected into the Border sections of widgets, containers, sections and columns.
- **2026-08-04** — Border Beam effect disabled while editing in Elementor. It was
  suspected of slowing the editor; a later diagnosis traced the slowness and the
  Safe Mode prompt to the local Docker environment.
- **2026-09-25** — **Animated Background** and **Animated Menu Background**
  configurations.
- **2026-10-04** — Border Beam effect type **Full border rotation outside**
  (`md-outside`).
