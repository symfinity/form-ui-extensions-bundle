# Troubleshooting

## Composer cannot resolve symfinity/ux-blocks-form

**Cause:** The bundle hard-requires `symfinity/ux-blocks-form` ^0.1. A partial install or missing Flex endpoint for symfinity recipes may leave dependencies unresolved.

**Fix:** Run the bridge install together:

```bash
composer require symfinity/form-ui-extensions-bundle symfinity/ux-blocks-form
```

Ensure your project lists the [symfinity/recipes](https://github.com/symfinity/recipes) Flex endpoint (see the recipes README).

## Form renders with default Symfony widgets (no data-ui-role)

**Cause:** `symfinity_form_ui.theme.enabled` is false or config was not copied.

**Fix:** Enable the theme in `config/packages/symfinity_form_ui.yaml`:

```yaml
symfinity_form_ui:
    theme:
        enabled: true
```

Run `php bin/console debug:config symfinity_form_ui` to confirm. Re-apply the Flex recipe or copy the default YAML from the package if needed.

## live_date or live_tags has no effect

**Cause:** Those flags require `symfinity/ux-blocks-live`.

**Fix:** Install the live tier package or set both flags to `false`.

## PHPStan or PHPUnit fails after upgrading Symfony

**Cause:** Matrix mismatch — this bundle targets Symfony 7.4+.

**Fix:** Align `symfony/*` constraints with ^7.4 or ^8.0 in your application and run `composer update`.

## Getting help

Open a [GitHub issue](https://github.com/symfinity/form-ui-extensions-bundle/issues). For security reports, see [SECURITY.md](../.github/SECURITY.md).
