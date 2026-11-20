# Upgrade and migration

## First release — 0.1.0

Initial public release on Packagist. Includes:

- Symfony bundle `FormUiExtensionsBundle` with config root `symfinity_form_ui`
- R1 FormView vars: button metadata, novalidate strategy, uppercase normalization
- R2 FormView vars: wizard, collection, upload, date range, field groups, error summary
- ux-blocks form theme bridge when `theme.enabled` is true
- Flex recipe at `symfinity/form-ui-extensions-bundle` 0.1

### Install

```bash
composer require symfinity/form-ui-extensions-bundle symfinity/ux-blocks-form
```

### Breaking changes

None — first tagged release.

### Migration steps

Not applicable for greenfield installs.

## 0.1.1

Patch release after [v0.1.0](https://github.com/symfinity/form-ui-extensions-bundle/releases/tag/v0.1.0).

```bash
composer update symfinity/form-ui-extensions-bundle
```

After upgrade:

1. Clear Symfony cache — Twig form theme paths are registered via bundle prepend.
2. Checkbox fields use horizontal field orientation in the default theme; adjust custom form theme overrides if you relied on the previous vertical shell.
3. No FormView var names or `symfinity_form_ui` configuration keys changed.

## 0.1.2

Patch release after [v0.1.1](https://github.com/symfinity/form-ui-extensions-bundle/releases/tag/v0.1.1).

```bash
composer update symfinity/form-ui-extensions-bundle
```

After upgrade:

1. No FormView var names or `symfinity_form_ui` configuration keys changed.
2. Handbook pages no longer reference `symfinity/ux-blocks-live` as a published package — keep `theme.live_date` and `theme.live_tags` at `false` until live-tier widgets are available on Packagist.
3. Bundle no longer ships `config/reference.php` (Symfony app-only artifact); use `php bin/console debug:config symfinity_form_ui` in your application instead.

## 0.1.3

Patch release after [v0.1.2](https://github.com/symfinity/form-ui-extensions-bundle/releases/tag/v0.1.2).

```bash
composer update symfinity/form-ui-extensions-bundle
```

After upgrade:

1. With `symfinity_form_ui.theme.enabled: true`, root forms now auto-inject ux-blocks core + form inline CSS on `form_start` — confirm `id="ux-blocks-core-css"` and `id="ux-blocks-form-css"` in rendered HTML.
2. No FormView var names or `symfinity_form_ui` configuration keys changed.
3. Optional `symfinity/ui-kernel` remains recommended for full design-token theming beyond inline tier CSS.
4. Clear Symfony cache if Twig form themes were cached in dev.

## 0.1.4

Patch release after 0.1.3. `form_end` prints fields not rendered yet, including a hidden CSRF token, unless the template sets `render_rest` to false. A row, checkbox, radio, or collection without an explicit label shows `label_format` or the humanized field name. `label: false` still prints no label. No FormView variable names or `symfinity_form_ui` keys changed. Dev installs require `symfinity/ui-kernel` `^0.3`.

```bash
composer update symfinity/form-ui-extensions-bundle
```

Clear the Symfony cache if Twig form themes were cached in dev.

## See also

[Configuration](configuration.md) · [Installation](installation.md)
