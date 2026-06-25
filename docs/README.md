# symfinity/form-ui-extensions-bundle

Symfony Form extensions for `symfinity_form_ui.*` FormView vars and an optional **ux-blocks form theme bridge**.

## Capabilities

| Layer | Role |
|-------|------|
| PHP extensions | Button metadata, novalidate, uppercase, wizard, collection, upload, error summary, field groups |
| Form theme (`theme.enabled`) | Maps Symfony Form blocks to ux-blocks components |

See [contracts/](contracts/) for the FormView var catalog and option semantics.

## Bridge install

```bash
composer require symfinity/form-ui-extensions-bundle symfinity/ux-blocks-form
```

```yaml
# config/packages/symfinity_form_ui.yaml
symfinity_form_ui:
    theme:
        enabled: true
```

The Flex recipe copies this config with `theme.enabled: true`. Theme registration is automatic via bundle prepend — no manual `framework.form.themes` entry is required.

## FormView vars (theme-agnostic)

Read resolved vars from the `symfinity_form_ui` namespace on `FormView` — see [form-view-vars-surface](contracts/form-view-vars-surface.md).

## Handbook

Start at [index.md](index.md) or [quickstart.md](quickstart.md).
