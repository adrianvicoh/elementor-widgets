# AGENTS.md

## Project overview

This repository is a WordPress plugin that adds opt-in Elementor widgets and
opt-in extensions to existing Elementor elements.

## Conventions

- Never modify `.gitignore`, including to allow vendored files or generated
  artifacts. Ask for explicit user direction if its rules block the work.
- Keep standalone widget PHP classes in `widgets/`.
- Register every widget in
  `dashboard/dash-configs/widget-visibility.php`; the dashboard controls
  whether Elementor registers it.
- Register widget assets in `elementor-widgets.php`. Load frontend
  behavior through Elementor's `frontend/element_ready/<widget>.default` hook
  and provide a standalone frontend fallback.
- Reuse the `custom-elementor-widgets` text domain and escape all rendered
  Elementor control values.
- Keep global element extensions in `configs/` and their assets in
  `configs/assets/`.
- Make widget JavaScript instance-safe. It must support several instances on
  one page and Elementor preview rerenders without duplicate listeners or
  leaked library instances.
- Elementor keeps the `.elementor-widget-<name>` wrapper across editor
  rerenders and replaces only its content. Never use a flag on the wrapper to
  skip initialisation. Store the library instance on the wrapper, skip only
  when it is bound to the current inner node, and otherwise destroy it
  (`destroy()`, `unset()`, observer `disconnect()`) before binding again.
- Prefer the library's own features over custom code. In `README.md`, state
  what each widget gets from its library and what it implements itself.

## Third-party libraries

- Vendor only the pinned upstream package source needed for a feature under
  `libs/<package>-<version>/`.
- Copy a runtime bundle and its matching license to `assets/vendor/` when a
  widget must work from a normal plugin archive. Keep the full source only in
  `libs/`.
- Preserve the upstream `LICENSE` file. Record package name, version,
  author and exact copyright line, source URL, pinned commit, license, the
  feature that uses it, and how it is distributed (unmodified bundle, adapted
  port, or CDN) in the root `THIRD-PARTY-NOTICES.md`, which is versioned.
  `libs/THIRD-PARTY-NOTICES.md` is only a local index because `libs/` is
  ignored. Also add a row to the *Third-party libraries* table in `README.md`.
- When code is adapted or ported from a third-party work rather than shipped
  unchanged, ship its license text beside the adapted assets and start each
  adapted file with a comment naming the original work, author, copyright,
  and license.
- Credit tutorials, templates, and other non-library sources in
  `THIRD-PARTY-NOTICES.md` too, and state when no license is given.
- Do not update a vendored dependency incidentally. Update its source,
  runtime integration, notice, and changelog together.
- Do not add build caches, `node_modules`, or unrelated reference trees under
  `libs/`.

## Documentation and validation

- Add user-visible changes to `CHANGELOG.md` under `Unreleased`.
- Update `README.md` when adding or changing a widget, setting, dependency,
  or activation step. The project uses `README.md`; do not create a duplicate
  `README.txt`.
- Keep `STATE.md` current while an implementation is in progress so another
  agent can continue safely. Remove only stale completed-task details.
- Validate PHP syntax with `php -l` for each changed PHP file and JavaScript
  syntax with `node --check` for each changed JavaScript file.
- Syntax checks are not a functional test. Do not report a feature as done or
  "compatible" until it has been checked in the Elementor editor and on the
  frontend; if you cannot test it, say so in `STATE.md` and in your answer.

## Elementor lessons from previous sessions

These come from earlier agent sessions on this plugin (April–October 2026).

- Prefix every global name: PHP functions, classes, constants, asset handles and
  JS hook names. An early copy of this plugin collided with another widget plugin
  through generic functions such as `register_new_widgets` and a shared class
  name, which caused fatal errors.
- Keep widget names (`get_name()`) stable. Elementor stores them as `widgetType`
  in `_elementor_data`, so a rename breaks existing pages unless a migration ships
  with it.
- Version registered assets with `filemtime()`; unversioned assets keep serving
  stale CSS/JS.
- Do not nest `start_controls_tabs()` inside a section that already has
  Normal/Hover tabs: the editor panel hangs. Split the controls into separate
  sections instead.
- A control with `selectors` only prints the property it declares. If a layout
  depends on `display: flex`, declare it explicitly.
- In `content_template()` a SLIDER value is an object `{size, unit}`; concatenate
  both parts.
- Initialise widget scripts on `frontend/element_ready/<name>.default` (with a
  fallback when Elementor is not present) and normalise `$scope` (jQuery or DOM).
  See the rerender rule above.
- Build widgets on `Widget_Base`. Extending Elementor Pro internals (for example
  `Nested_Carousel`) requires registering the type in the editor and depends on
  script load order; an earlier attempt was abandoned for that reason. If a
  feature does rely on Pro markup or classes, note the Pro version it was tested
  with.
- Load third-party libraries only where the widget is used: register them as
  dependencies of the widget handles (`get_script_depends()` /
  `get_style_depends()`), and add SRI attributes through `script_loader_tag` /
  `style_loader_tag` for those handles only (see Coverflow Slider).
- Pass widget settings to JavaScript as JSON in a `data-settings` attribute built
  in `render()`. When a widget runs its own Swiper, give it its own
  `wrapperClass` / `slideClass` so it does not clash with Elementor's.
- Choose defaults that make the effect visible as soon as the widget is inserted,
  and reuse them as the fallback values of the CSS custom properties.
- Inject controls into existing sections with
  `elementor/element/{stack}/{section_id}/before_section_end`. With the optimized
  markup experiment, `common-optimized` re-fires the `common` hooks, so hooking
  `common` is enough. Guard against duplicates with `get_controls( 'id' )`.
- Load configuration assets with `wp_enqueue_scripts` and
  `elementor/preview/enqueue_*`, never with `elementor/editor/*`. The body class
  `elementor-editor-active` exists in both the editor panel and the preview
  iframe.
- If the widgets vanish from the editor, check Elementor Safe Mode before looking
  for a fatal error (see *Troubleshooting* in `README.md`).

## Working with sites and environments

- Do not change the database, Elementor settings (for example the Safe Mode allow
  list) or files on a remote server without asking first.
- Never mirror a local copy onto a server with deletions unless the local copy is
  confirmed complete: a past sync from an incomplete local stub deleted widgets on
  a test site.
- In the Docker sandbox, read logs with `docker logs`; `tail` on logs symlinked to
  `/dev/stdout` hangs.
- Subagents cannot ask the user questions: give them everything they need and
  expect a report back. Low-cost models produced generic audits with invented file
  paths in past sessions; verify their claims against the code.
