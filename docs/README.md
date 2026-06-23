## symfinity/form-ui-extensions-bundle

Symfony Form extensions for `symfinity_form_ui.*` FormView vars (R1+R2) and an optional **ux-blocks form theme bridge** (**113**).

### Capabilities

| Layer | Role |
|-------|------|
| PHP extensions | Button metadata, novalidate, uppercase, wizard, collection, upload, error summary, field groups |
| Form theme (`theme.enabled`) | Maps Symfony Form blocks → ux-blocks components |

Contracts: [contracts/](contracts/) · bridge: [_org symfony-form-theme-bridge](../../../../specs/symfinity/symfinity/_org/contracts/form-ui-extensions/symfony-form-theme-bridge.md) · [quickstart](quickstart.md)

### Bridge install (113)

```bash
composer require symfinity/form-ui-extensions-bundle symfinity/ux-blocks-form
```

```yaml
# config/packages/symfinity_form_ui.yaml
symfinity_form_ui:
    theme:
        enabled: true
```

Flex recipe `0.1` copies config with `theme.enabled: true`. Theme registration is automatic via bundle prepend — no manual `framework.form.themes` entry required.

### FormView vars (theme-agnostic)

Read resolved vars from `FormView` namespace `symfinity_form_ui` — see [form-view-vars-surface](contracts/form-view-vars-surface.md).

### Dogfood

```bash
make dogfood-new SLUG=form-ui-extensions-lab VERSION='7.4.*'
make dogfood-serve SLUG=form-ui-extensions-lab
```

Route: `/form-ui-extensions`

### Tests

```bash
cd src/symfinity
./bin/php vendor/bin/phpunit packages/form-ui-extensions-bundle/tests/
```
