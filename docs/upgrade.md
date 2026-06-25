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

## Unreleased development

Changes on `main` after `v0.1.0` appear under `[Unreleased]` in [CHANGELOG.md](../CHANGELOG.md) until the next tag.

## See also

[Configuration](configuration.md) · [Installation](installation.md)
