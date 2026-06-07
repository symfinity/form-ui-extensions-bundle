# Contract: FormView vars catalog (R1 + R2)

**Feature**: symfinity **050**  
**Package**: `packages/form-ui-extensions-bundle/`  
**Namespace**: `symfinity_form_ui` (nested under `FormView.vars`)  
**Status**: Normative for implement — supersedes R1 catalog appendix when merged to package docs

## R1 keys (021 — unchanged)

| Key | Type | Scope | Description |
|-----|------|-------|-------------|
| `button_metadata` | map | field | `{ enabled, intent?, variant?, tags?, disabled_reason? }` |
| `novalidate` | bool | form root | Resolved novalidate flag |
| `novalidate_strategy` | string | form root | `inherit` \| `force_on` \| `force_off` |
| `uppercase` | map | field | `{ enabled, mode?, applied }` |

## R2 keys (050 — additive)

### Wizard (`wizard`)

| Key | Type | Scope | Description |
|-----|------|-------|-------------|
| `wizard.enabled` | bool | form root | Option active |
| `wizard.steps` | list | form root | `[{ id, label, description?, field_names[] }]` |
| `wizard.step_count` | int | form root | Count of steps |
| `wizard.field_step_map` | map | form root | `{ fieldName: stepIndex }` (1-based index) |
| `wizard.invalid_steps` | int[] | form root | Step indices with errors after submit |
| `wizard.linear` | bool | form root | When true, client should block skip-ahead |
| `wizard.initial_step` | int | form root | Default visible step (1-based) |

**Per-field (optional):**

| Key | Type | Scope | Description |
|-----|------|-------|-------------|
| `wizard.step_index` | int | field | Step owning this field |
| `wizard.visible_in_wizard` | bool | field | Hint for theme (true when step matches client current) |

### Collection (`collection`)

| Key | Type | Scope | Description |
|-----|------|-------|-------------|
| `collection.enabled` | bool | collection root | Option active |
| `collection.allow_add` | bool | collection root | Effective add (respects max) |
| `collection.allow_delete` | bool | collection root | Effective delete (respects min) |
| `collection.count` | int | collection root | Current entry count |
| `collection.min` | int? | collection root | Configured minimum |
| `collection.max` | int? | collection root | Configured maximum |
| `collection.empty_state` | string? | collection root | Message key or literal for empty UI |
| `collection.prototype_name` | string | collection root | Symfony prototype field name |
| `collection.add_label` | string? | collection root | Accessible label for add control |
| `collection.delete_label` | string? | collection root | Accessible label per row remove |

**Per collection entry row:**

| Key | Type | Scope | Description |
|-----|------|-------|-------------|
| `collection.row_index` | int | entry | 0-based display index |
| `collection.row_id` | string | entry | Stable id for ARIA (`collection-{name}-{index}`) |
| `collection.is_prototype` | bool | entry | True for prototype row template |
| `collection.allow_row_delete` | bool | entry | Row-level delete affordance |

### Upload (`upload`)

| Key | Type | Scope | Description |
|-----|------|-------|-------------|
| `upload.enabled` | bool | file field | Option active |
| `upload.progress` | int | file field | 0–100; client-updated |
| `upload.status` | string | file field | `idle` \| `uploading` \| `success` \| `error` |
| `upload.error` | string? | file field | Last error message |
| `upload.max_size` | int? | file field | Bytes hint for client validation |
| `upload.accept` | string? | file field | MIME/extension accept string |
| `upload.controller` | string | file field | Stimulus controller id (`form-ui--upload`) |

### Date range (`date_range`)

| Key | Type | Scope | Description |
|-----|------|-------|-------------|
| `date_range.enabled` | bool | form root | Option active |
| `date_range.start_name` | string | form root | Child field name for range start |
| `date_range.end_name` | string | form root | Child field name for range end |
| `date_range.preset` | string? | form root | Optional preset key (`last_7_days`, etc.) |
| `date_range.range_error` | bool | form root | Cross-field validation failed |
| `date_range.range_error_message` | string? | form root | Message for summary/link |

**Per paired field:**

| Key | Type | Scope | Description |
|-----|------|-------|-------------|
| `date_range.role` | string | field | `start` \| `end` |
| `date_range.partner_name` | string | field | Opposite field name |

### Errors and ARIA (`errors`, `field_group`)

| Key | Type | Scope | Description |
|-----|------|-------|-------------|
| `errors.has_errors` | bool | form root | Any field or global errors |
| `errors.summary` | list | form root | `[{ name, label, message, target_id }]` |
| `errors.global` | list | form root | `[{ message, target_id }]` |
| `errors.error_count` | int | form root | Total field errors |

**Field group (fieldset compound):**

| Key | Type | Scope | Description |
|-----|------|-------|-------------|
| `field_group.enabled` | bool | compound | Option active |
| `field_group.group_id` | string | compound | DOM id for fieldset |
| `field_group.legend` | string? | compound | Legend text |
| `field_group.describedby_ids` | string[] | compound | Ids for aria-describedby chain |
| `field_group.invalid` | bool | compound | Any child has errors |

## Compatibility rules

1. All R2 keys are **additive**; consumers MUST ignore unknown keys.
2. Keys remain **presentation-agnostic** — no HTML fragments in values.
3. Option names use `ui_*` prefix in PHP (`FormUiOptionKeys`); view keys use snake segments under `symfinity_form_ui`.
4. Breaking renames require new major recipe version — not in **050** scope.

## Verification

- Integration test per key group asserts keys present when option enabled and absent/default when disabled.
- Contract parity: package `docs/contracts/form-view-vars-surface.md` updated at implement (T0xx polish phase).
