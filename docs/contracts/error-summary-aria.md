# Contract: Error summary and field-group ARIA

**Feature**: symfinity **050** (v0.3)  
**Reference**: react-aria form **accessibility patterns only**  
**Status**: Normative for implement

## Form options

### Error summary

**Key**: `FormUiOptionKeys::ERROR_SUMMARY` (`ui_error_summary`)

**Shape**:

```php
[
    'enabled' => true,                    // default true when key present
    'link_to_fields' => true,           // include target_id in summary entries
    'global_target_id' => 'form-errors-global',
]
```

### Field group

**Key**: `FormUiOptionKeys::FIELD_GROUP` (`ui_field_group`)

**Applies to**: compound fields / fieldset-like compounds

**Shape**:

```php
[
    'legend' => 'Billing address',
    'describedby' => ['billing-help'],  // optional extra id refs
    'group_id' => 'billing-group',      // optional; auto-generated if omitted
]
```

## MUST

| ID | Rule |
|----|------|
| ERR-1 | When form submitted and invalid, build `errors.summary` from unique field errors in stable order (form definition order) |
| ERR-2 | Each summary entry `target_id` MUST match id theme assigns to input or `aria-labelledby` target — document convention `{formName}_{fieldName}` |
| ERR-3 | Global form errors (FormError on root) populate `errors.global` |
| ERR-4 | `errors.has_errors` and `errors.error_count` consistent with summary length + globals |
| ERR-5 | Field group vars expose `group_id`, `legend`, `describedby_ids` including help text element ids when configured |
| ERR-6 | `field_group.invalid` true when any descendant field has error |

## MUST NOT

| ID | Rule |
|----|------|
| ERR-N1 | Render `<ul>` error summary HTML in PHP |
| ERR-N2 | Replace Symfony `form_errors()` — vars augment themes |
| ERR-N3 | Set ARIA attributes in PHP on FormView (hints only in vars) |

## Accessibility notes (informative)

- Summary links SHOULD use `href="#{target_id}"` in theme Twig
- Invalid fields SHOULD set `aria-invalid="true"` in theme when `errors` present on field
- Field group SHOULD use `<fieldset>` + `<legend>` in theme matching `group_id` / `legend` vars

## Verification

- Integration: three field errors → summary length 3 with correct names
- Integration: root FormError → global entry
- Integration: field group with child error → `field_group.invalid` true
- Manual: keyboard navigation from summary link to field in dogfood
