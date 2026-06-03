# Contract: Core Domain

## Scope

Defines the package-level domain boundaries for `symfinity/form-ui-extensions-bundle`.

## In Scope

- Form extension options and option validation
- Resolution of UI extension state for FormView
- Optional uppercase normalization behavior
- Deterministic error model for invalid option usage

## Out of Scope

- HTML rendering in PHP
- Required Twig template overrides
- CSS/theming policy generation
- Frontend runtime implementation details

## Domain Rules

1. Every public option has explicit type/default semantics.
2. Invalid option payloads fail early with deterministic error category.
3. Resolved state is presentation-agnostic and serializable.
4. Transform behavior is opt-in and non-destructive for unsupported value types.
