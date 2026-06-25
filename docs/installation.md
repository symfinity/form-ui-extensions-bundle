# Installation

## Requirements

- PHP 8.2 or higher
- Symfony **7.4** or **8.x**
- Symfony Form component in the host application

## Bridge install (recommended)

The bundle hard-requires `symfinity/ux-blocks-form` (and transitively `symfinity/ux-blocks-core`). Install both together:

```bash
composer require symfinity/form-ui-extensions-bundle symfinity/ux-blocks-form
```

Add the [symfinity/recipes](https://github.com/symfinity/recipes) Flex endpoint to your project's `composer.json` if you have not already — see the [recipes README](https://github.com/symfinity/recipes/blob/main/README.md).

## Symfony Flex

On `composer require`, the Flex recipe:

1. Registers `Symfinity\FormUiExtensionsBundle\FormUiExtensionsBundle`
2. Copies `config/packages/symfinity_form_ui.yaml` with `theme.enabled: true`
3. Prepends the ux-blocks form theme automatically (no manual `framework.form.themes` entry)

Optional for full Chameleon token CSS:

```bash
composer require symfinity/ui-kernel
```

## Manual installation

When Flex is unavailable:

1. Register the bundle in `config/bundles.php`:

```php
Symfinity\FormUiExtensionsBundle\FormUiExtensionsBundle::class => ['all' => true],
```

2. Copy the default config from the package:

```yaml
# config/packages/symfinity_form_ui.yaml
symfinity_form_ui:
    theme:
        enabled: true
        wrapper: field
        live_date: false
        live_tags: false
```

3. Ensure `symfinity/ux-blocks-form` is installed.

## Verify installation

```bash
php bin/console debug:config symfinity_form_ui
php bin/console debug:container FormUiExtensionsBundle
```

Render any form route and confirm field markup includes `data-ui-role` attributes when the theme is enabled.

## Next steps

[Quick start](quickstart.md) · [Configuration](configuration.md)
