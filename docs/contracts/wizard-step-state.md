# Contract: Wizard step state

**Feature**: symfinity **050** (v0.2)  
**Reference**: formcn multi-step **structure only** (no React port)  
**Status**: Normative for implement

## Form option

**Key**: `FormUiOptionKeys::WIZARD` (`ui_wizard`)

**Shape**:

```php
[
    'steps' => [
        ['id' => 'account', 'label' => 'Account', 'fields' => ['email', 'password']],
        ['id' => 'profile', 'label' => 'Profile', 'fields' => ['name', 'bio']],
    ],
    'linear' => true,           // default true
    'initial_step' => 1,        // default 1, 1-based
]
```

## MUST

| ID | Rule |
|----|------|
| WIZ-1 | Validate unique step `id` strings and non-empty `fields` arrays at form build |
| WIZ-2 | Every field name in `steps[*].fields` MUST exist as direct child of root form |
| WIZ-3 | A field MUST NOT appear in more than one step |
| WIZ-4 | Expose `symfinity_form_ui.wizard` on root FormView per [form-view-vars-catalog](./form-view-vars-catalog.md) |
| WIZ-5 | After failed submit, compute `invalid_steps` from child field errors |
| WIZ-6 | Per-field views include `wizard.step_index` for assigned fields |
| WIZ-7 | Document Stimulus optional controller `form-ui--wizard` for client step switching (data attributes only) |

## MUST NOT

| ID | Rule |
|----|------|
| WIZ-N1 | Persist wizard progress in session or database in v0.2 |
| WIZ-N2 | Split form into multiple HTTP requests automatically |
| WIZ-N3 | Render step tabs or progress UI in PHP/Twig from bundle |

## Stimulus (optional asset)

| Attribute | Purpose |
|-----------|---------|
| `data-form-ui--wizard-current-step-value` | 1-based current step |
| `data-form-ui--wizard-linear-value` | Mirror of `wizard.linear` |
| `data-action="click->form-ui--wizard#goNext"` | Client navigation hooks |

## Verification

- Unit: invalid duplicate field in steps throws `InvalidFormUiOptionException`
- Integration: three-step form → vars match contract
- Manual: dogfood wizard demo switches steps without full reload
