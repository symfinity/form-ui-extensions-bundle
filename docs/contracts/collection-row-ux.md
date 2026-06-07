# Contract: Collection row UX

**Feature**: symfinity **050** (v0.2)  
**Status**: Normative for implement

## Form option

**Key**: `FormUiOptionKeys::COLLECTION_UX` (`ui_collection`)

**Applies to**: `CollectionType` only

**Shape**:

```php
[
    'empty_state' => 'No items yet',   // optional
    'add_label' => 'Add row',        // optional
    'delete_label' => 'Remove',      // optional
    'enforce_min_max_on_vars' => true, // default true — override allow_* on vars
]
```

## MUST

| ID | Rule |
|----|------|
| COL-1 | When option present, set `collection.enabled` true on collection FormView |
| COL-2 | Compute effective `allow_add` / `allow_delete` from Symfony options AND min/max when `enforce_min_max_on_vars` true |
| COL-3 | Each entry view receives `collection.row_index`, `collection.row_id`, `collection.is_prototype`, `collection.allow_row_delete` |
| COL-4 | Prototype entry view has `is_prototype: true` and `row_index: -1` or omitted per test fixture choice (document in implement) |
| COL-5 | `row_id` MUST be stable for re-render: `{collectionName}-{index}` slug |
| COL-6 | Optional Stimulus `form-ui--collection` handles prototype add/remove DOM sync; vars remain SSOT for affordance flags |

## MUST NOT

| ID | Rule |
|----|------|
| COL-N1 | Replace Symfony `CollectionType` prototype mechanism |
| COL-N2 | Implement drag-and-drop reorder in v0.2 |
| COL-N3 | Nest wizard inside collection row in dogfood v0.2 demo |

## Integration with **024**

Themes using `ux-blocks-core` field/fieldset SHOULD read `symfinity_form_ui.collection.*` for row chrome; bundle does not import ux-blocks.

## Verification

- Integration: min=1 max=3 — at count 3 `allow_add` false; at count 1 `allow_delete` false
- Integration: empty collection exposes `empty_state`
- Manual: dogfood add/remove row updates indices
