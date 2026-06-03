# Contract: FormView Vars Surface

## Purpose

Define stable, namespaced FormView variable keys exposed by this bundle.

## Namespace

All view vars MUST be nested under:

- `symfinity_form_ui`

## Keys

- `button_metadata` (map)
- `novalidate` (bool)
- `novalidate_strategy` (string)
- `uppercase` (map)
  - `enabled` (bool)
  - `mode` (string|null)
  - `applied` (bool)

## Compatibility Rules

1. Keys are additive and backward-compatible within feature scope.
2. Keys must stay presentation-agnostic.
3. Consumers may read keys in Twig/runtime layers; bundle does not enforce template contract.
