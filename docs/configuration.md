# Configuration

## Configuration root (triple alignment)

| Piece | Value |
|-------|-------|
| `Configuration.php` root | `symfinity_form_ui` |
| Consumer file | `config/packages/symfinity_form_ui.yaml` |
| YAML document root | `symfinity_form_ui:` |

The short alias `symfinity_form_ui` is intentional — not derived from the Composer slug.

## Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `symfinity_form_ui.theme.enabled` | bool | `false` (no YAML) / `true` (Flex recipe copy) | When true, prepends `@SymfinityFormUi/form/theme.html.twig` to Twig form themes |
| `symfinity_form_ui.theme.wrapper` | `field` \| `floating-field` | `field` | Default field wrapper role for mapped types |
| `symfinity_form_ui.theme.live_date` | bool | `false` | Use ux-blocks live date widget (requires `symfinity/ux-blocks-live`) |
| `symfinity_form_ui.theme.live_tags` | bool | `false` | Use ux-blocks live tags widget (requires `symfinity/ux-blocks-live`) |

Programmatic defaults match `Configuration.php` when no YAML is present. The Flex recipe copies package config with `theme.enabled: true`.

## Theme disabled

When `theme.enabled` is `false`, FormView var extensions (button metadata, wizard, collection, upload, error summary, …) still work. Only the ux-blocks theme prepend is skipped.

## Environment variables

This bundle does not define dedicated env vars. Map Symfony `%env()%` in your own `config/packages/symfinity_form_ui.yaml` if needed.

## See also

- [Usage](usage.md) — Form option keys and rendering patterns
- [Quick start](quickstart.md) — minimal bridge install
- Package contracts under `docs/contracts/` for FormView var catalog
