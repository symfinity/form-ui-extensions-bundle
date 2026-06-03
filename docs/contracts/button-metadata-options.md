# Contract: Button Metadata Options

## Purpose

Provide structured metadata options for submit/button form controls.

## Public Option Surface

- `ui_button_metadata` (map, optional)
  - allowed keys: `intent`, `channel`, `tags`, `extras`
  - `intent`: short string enum domain (consumer-defined list allowed via config contract)
  - `channel`: short string
  - `tags`: list of short strings
  - `extras`: constrained scalar map

## Resolution Output

Resolved state under FormView namespace key:

- `symfinity_form_ui.button_metadata`

Output shape:

- `enabled` (bool)
- `intent` (string|null)
- `channel` (string|null)
- `tags` (list<string>)
- `extras` (map<string, scalar>)

## Error Cases

- Unsupported field type for metadata options.
- Invalid key/value types.
- Forbidden/reserved keys in extras.
