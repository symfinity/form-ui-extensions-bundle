# Contract: FormView Vars Surface

## Purpose

Define stable, namespaced FormView variable keys exposed by this bundle (R1 + R2).

## Namespace

All view vars MUST be nested under:

- `symfinity_form_ui`

## R1 keys (021)

- `button_metadata` (map)
- `novalidate` (bool)
- `novalidate_strategy` (string)
- `uppercase` (map)
  - `enabled` (bool)
  - `mode` (string|null)
  - `applied` (bool)

## R2 keys (050)

See [form-view-vars-catalog.md](./form-view-vars-catalog.md) for the full additive catalog:

- `wizard` — multi-step form structure (v0.2)
- `collection` — collection row UX (v0.2)
- `upload` — file upload progress vars (v0.3)
- `date_range` — start/end pairing for filters (v0.3)
- `errors` — error summary list (v0.3)
- `field_group` — fieldset ARIA hints (v0.3)

Slice contracts: [wizard-step-state.md](./wizard-step-state.md) · [collection-row-ux.md](./collection-row-ux.md) · [file-upload-progress.md](./file-upload-progress.md) · [date-range-filter.md](./date-range-filter.md) · [error-summary-aria.md](./error-summary-aria.md)

## Compatibility Rules

1. Keys are additive and backward-compatible within feature scope.
2. Keys must stay presentation-agnostic.
3. Consumers may read keys in Twig/runtime layers; bundle does not enforce template contract.
