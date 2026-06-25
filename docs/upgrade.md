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

## See also

[Configuration](configuration.md) · [Installation](installation.md)
