## symfinity/form-ui-extensions-bundle

Theme-agnostic Symfony Form extensions for:

- button metadata options (`ui_button_metadata`)
- novalidate strategy (`ui_novalidate_strategy`)
- optional uppercase normalization (`ui_uppercase_normalization`)

Contracts: [contracts/](contracts/) · [quickstart](quickstart.md)

### Minimal usage

- Register bundle and ensure Form component is enabled.
- Read resolved vars from `FormView` namespace `symfinity_form_ui`.
- Keep rendering decisions in Twig/frontend runtime; this bundle does not render HTML.

### Primal lab reference (WoWi)

Source: [`var/primal/td-cc-wowi`](../../../../var/primal/td-cc-wowi) (reference only).

| WoWi pattern | Notes |
|--------------|-------|
| `FormErrorSerializer` → `{ global, fields }` JSON for AJAX POST | Pair with Stimulus/`ux-runtime` — expose field-level errors via FormView vars + host controller |
| Newsletter subscribe/unsubscribe AJAX forms | Same validation JSON contract; rendering stays in host Twig |

### FormView vars

- `symfinity_form_ui.button_metadata`
- `symfinity_form_ui.novalidate`
- `symfinity_form_ui.novalidate_strategy`
- `symfinity_form_ui.uppercase`
