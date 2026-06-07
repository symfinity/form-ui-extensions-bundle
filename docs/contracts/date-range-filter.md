# Contract: Date-range filter pairing

**Feature**: symfinity **050** (v0.3)  
**Coordination**: symfinity **046** `date-range-picker` (horizon — widget block)  
**Status**: Normative for implement

## Form option

**Key**: `FormUiOptionKeys::DATE_RANGE` (`ui_date_range`)

**Applies to**: root or compound filter form

**Shape**:

```php
[
    'start' => 'created_from',   // required — child field name
    'end' => 'created_to',       // required — child field name
    'preset' => null,            // optional string key for theme presets
]
```

## MUST

| ID | Rule |
|----|------|
| DRG-1 | Validate `start` and `end` reference existing child fields of type `DateType`, `DateTimeType`, or `TextType` (ISO date) |
| DRG-2 | Expose parent-level `symfinity_form_ui.date_range` vars per catalog |
| DRG-3 | Mark start/end child views with `date_range.role` and `date_range.partner_name` |
| DRG-4 | Optional `Callback` constraint helper or documented validator for `end >= start` (bundle provides constraint class, not mandatory on all forms) |
| DRG-5 | Contract documents forward compat: **046** block MUST read same var keys |

## MUST NOT

| ID | Rule |
|----|------|
| DRG-N1 | Ship calendar/popover UI in **050** |
| DRG-N2 | Hard depend on **046** package in `composer.json` |
| DRG-N3 | Replace `DateType` with custom type |

## **046** coordination (horizon)

When **046** ships, `date-range-picker` UX block SHOULD:

- Accept `start_name` / `end_name` from parent form vars
- Emit paired hidden or visible inputs matching Symfony field names
- Use `date_range.preset` for quick filters

## Verification

- Integration: filter form → pairing vars on parent and children
- Integration: invalid range sets `range_error` true
- Manual: dogfood filter form works with two native date inputs
