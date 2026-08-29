# Reference

## PHP entry points

| Class | Role |
|-------|------|
| `FormUiExtensionsBundle` | Bundle registration; extension alias `symfinity_form_ui` |
| `FormUiOptionKeys` | Canonical form option key constants |
| `FormUiViewNamespace` | Twig / FormView namespace helper |
| `Configuration` | Config tree under `symfinity_form_ui` |

Form type extensions and resolvers live under `src/Form/`.

## Configuration reference

See [Configuration](configuration.md) for the full `symfinity_form_ui.theme.*` option table and triple-alignment note.

## Contracts

Normative detail for FormView vars, novalidate, wizard, collection, upload, and theme behaviour:

- [FormView var catalog](contracts/form-view-vars-catalog.md) — including [form-view-vars-surface](contracts/form-view-vars-surface.md) and other contract pages under `docs/contracts/`

## Glossary

| Term | Meaning |
|------|---------|
| Bridge | Symfony Form theme mapping FormView trees to ux-blocks components |
| FormView var | Value on `FormView.vars` under `symfinity_form_ui.*` |
| Wrapper | `field` or `floating-field` ux-blocks role for a form row |
