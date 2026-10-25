# Implementation state

## Current objective

No implementation is in progress. The last completed tasks were the Draggable
Boxes and Numeric Chart widgets (tested manually after commit `f7cbc35`) and the
rename to `elementor-widgets` (commit `476a01d`). The open items below were
collected from the code and from earlier agent sessions.

## Open items

1. **Check every feature after the rename.** The rename changed option names,
   control IDs, CSS classes and custom properties. Re-enable the features in the
   dashboard and check Animated Border Beam, Animated Background and Animated Menu
   Background in the editor and on the frontend. If the local sandbox still has
   the plugin under its old folder, reactivate it.
2. **Unprefixed global names.** `register_new_widgets`,
   `add_elementor_widget_categories`, `enqueue_editor_widget_styles`,
   `swiper_cdn_script_attributes` and `swiper_cdn_style_attributes` in
   `elementor-widgets.php`, and the widget classes (`Coverflow_Slider`,
   `Toggle_Content`, `Draggable_Boxes`, `Numeric_Chart`), have no prefix or
   namespace. This already caused fatal collisions once (see `AGENTS.md`). The
   changelog entry for the rename says functions use `elementor_widgets_`; these
   five do not.
3. **Plugin header.** `Requires: Elementor, Elementor Pro` is not a WordPress
   header, and Pro is only needed by Animated Menu Background. Consider
   `Requires Plugins: elementor` plus a runtime check for Pro in
   `animated-menu.php`, and add `Version`, `Requires at least: 6.8` and
   `Requires PHP: 8.0`.
4. **Animated Border Beam follow-ups** (from the August sessions): load assets
   only where the effect is used; replace the document-wide `MutationObserver`;
   decide whether to support per-widget Style borders.
5. **Animated Menu Background:** confirm the `section_style_main-menu` section ID
   on the installed Elementor Pro version and record that version in `README.md`;
   the newer *Menu* widget is not supported.
6. **Coverflow Slider:** slides are images only; an earlier audit suggested a
   generic default `alt`, which was rightly not applied (the title is the
   fallback).
7. **Optional tooling** (from an earlier scaffold that was never adopted): a
   `new-widget` generator that creates `widgets/<slug>.php` and
   `assets/{css,js}/<slug>.*` from templates with the prefix already applied and
   prints the registration entry for `widget-visibility.php`; and checks beyond
   `php -l` / `node --check` (PHPCS with the WordPress standard, ESLint and
   Stylelint with the `@wordpress` configs).
8. Optionally record the border-beam upstream commit in
   `THIRD-PARTY-NOTICES.md`; the local copy is a `main` download at 1.4.0 and its
   commit was never recorded.

## History

The project history before `CHANGELOG.md` existed (March–October 2026) is
summarised in `CHANGELOG.md` under *Before the changelog*.

## Validation

- `php -l` passes for all 11 tracked PHP files and `node --check` for the 7
  plugin JavaScript files (checked on 2026-10-25, after the rename).
- `node` is not on this machine's `PATH`; Zed's bundled runtime works:
  `C:\Users\adria\AppData\Local\Zed\node\node-v24.21.0-win-x64\node.exe`.
- Zed's terminal rejects commands containing `$`; put such commands in a script
  file.
- The user tested Draggable Boxes and Numeric Chart manually in a WordPress site
  with Elementor after commit `f7cbc35`; everything worked.

## Constraints

- Do not modify `.gitignore`; this is an explicit user instruction recorded
  in `AGENTS.md`.
- `libs/` is ignored by Git and holds local reference trees. Anything that
  must be versioned (bundles, license texts, notices) lives outside `libs/`.
- Do not replace the Coverflow Slider's CDN dependency without a decision from
  the user; new widgets must not introduce CDN runtime requests.
