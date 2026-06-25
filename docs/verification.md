# Verification

Checks after installing **symfinity/form-ui-extensions-bundle** in a Symfony app.

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

## Clean-app smoke

On a fresh Symfony 7.4+ project:

```bash
composer require symfinity/form-ui-extensions-bundle symfinity/ux-blocks-form
```

Submit a sample form — expect HTTP 200 and ux-blocks field markup in the response body.

## See also

- [Quickstart](quickstart.md)
- [Troubleshooting](troubleshooting.md)
- [CHANGELOG](../CHANGELOG.md)
