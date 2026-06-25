# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.1.1] - 2026-06-25

### Changed

- **Twig wiring** — `@SymfinityFormUi` template namespace registered via DI `prepend` whenever Twig is installed; UX Blocks form theme remains optional behind `symfinity_form_ui.theme.enabled`
- **Bundle bootstrap** — removed `TwigConfigurator::path()` registration from `FormUiExtensionsBundle` (prepend-only wiring for Symfony 7.4+)
- **Form theme** — `checkbox_row` field shell uses `data-ui-orientation="horizontal"` for label/control layout
- **Handbook** — `docs/verification.md` expanded with bridge smoke checks

### Notes

- No FormView var names or configuration keys changed — patch release after v0.1.0 tag
- Clear Symfony cache after upgrade if Twig form themes were cached in dev

## [0.1.0] - 2026-06-25

### Added

- Symfony bundle `FormUiExtensionsBundle` with configuration root `symfinity_form_ui`
- FormView vars for button metadata, novalidate strategy, and uppercase normalization on fields and forms
- FormView vars for wizard steps, collection rows, file upload progress, date ranges, field groups, and accessible error summaries
- Optional UX Blocks form theme bridge: when `symfinity_form_ui.theme.enabled` is true, maps Symfony Form blocks to UX Blocks Twig components (`field`, `floating-field`, and mapped widget types)
- Flex recipe `0.1`: registers the bundle and copies default `config/packages/symfinity_form_ui.yaml` with the theme bridge enabled
- Handbook: installation, configuration, quickstart, usage, upgrade, and package contracts under `docs/`
- Split mirror CI: PHPUnit and PHPStan on PHP 8.2–8.5 × Symfony 7.4, 8.0, and 8.1

### Notes

- Hard dependency on `symfinity/ux-blocks-form` ^0.1 (and transitively `symfinity/ux-blocks-core` ^0.1) — install both for the documented bridge path
- Optional: `symfinity/ui-kernel` ^0.2 for full design-token CSS
- Symfony **7.4** or **8.x** required; PHP **8.2**+
