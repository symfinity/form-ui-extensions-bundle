# Contract: Uppercase Normalization

## Purpose

Define optional uppercase normalization behavior for selected text-like form inputs.

## Public Option Surface

- `ui_uppercase_normalization` (bool|map)
  - bool shorthand: enable/disable
  - map form:
    - `enabled` (bool)
    - `mode` (`strict_ascii` | `mb_upper`)
    - `trim_before` (bool, optional)

## Processing Rules

1. Execute only when enabled.
2. Apply only to string-compatible values.
3. Preserve null/empty semantics.
4. For unsupported value types, skip transformation and keep original value.

## Output Surface

- transformation outcome reflected in normalized data
- optional diagnostic var: `symfinity_form_ui.uppercase_applied` (bool)

## Error Cases

- Invalid mode value.
- Unsupported option payload type.
