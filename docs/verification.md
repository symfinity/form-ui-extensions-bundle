# Verification

**Integration profile:** P2 — embed (Symfony form theme bridge → ux-blocks field markup).

Checks after installing **symfinity/form-ui-extensions-bundle** in a Symfony app.

Run the clean-app smoke below on a fresh Symfony 7.4+ project to confirm the theme bridge renders styled ux-blocks fields.

## Local commands

```bash
composer test
composer phpstan
php bin/console debug:config symfinity_form_ui
```

## Browser or WebTestCase

Render a form with the theme bridge enabled (`symfinity_form_ui.theme.enabled: true`) and confirm ux-blocks field markup:

- `data-ui-role="field"` or `data-ui-role="floating-field"` on field shells
- Checkbox rows use horizontal label/control layout (`data-ui-orientation="horizontal"`)
- `id="ux-blocks-core-css"` and `id="ux-blocks-form-css"` once per request (theme auto-injects inline CSS)

## Clean-app smoke

On a fresh Symfony 7.4+ project:

```bash
composer require symfinity/form-ui-extensions-bundle symfinity/ux-blocks-form
```

Submit a sample form — expect HTTP 200, ux-blocks field markup (`data-ui-role="field"`), and inline CSS markers `id="ux-blocks-core-css"` / `id="ux-blocks-form-css"` in the response body (auto-injected by the theme bridge — no manual layout wiring).

## See also

- [Quickstart](quickstart.md)
- [Troubleshooting](troubleshooting.md)
- [CHANGELOG](../CHANGELOG.md)
