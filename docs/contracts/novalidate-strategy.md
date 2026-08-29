# Contract: Novalidate Strategy

## Purpose

Normalize browser validation policy selection for Symfony forms.

## Strategy Values

- `inherit` — use parent/default policy
- `force_on` — resulting form state resolves novalidate=true
- `force_off` — resulting form state resolves novalidate=false

## Precedence

1. Explicit local override where supported
2. Form-level strategy
3. Bundle default strategy

Precedence must always resolve to one boolean output (`true|false`) in FormView vars.

## Output Surface

- `symfinity_form_ui.novalidate` (bool)
- `symfinity_form_ui.novalidate_strategy` (string strategy identifier for diagnostics)

## Error Cases

- Unknown strategy value.
- Conflicting override payload shape.
