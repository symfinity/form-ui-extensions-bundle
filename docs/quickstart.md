# Quickstart: symfinity/form-ui-extensions-bundle

## Bridge install (recommended)

```bash
composer require symfinity/form-ui-extensions-bundle symfinity/ux-blocks-form
```

Add the [symfinity/recipes](https://github.com/symfinity/recipes) Flex endpoint to your project if you have not already.

The recipe registers the bundle and copies `config/packages/symfinity_form_ui.yaml` with the theme bridge enabled. To adjust defaults:

```yaml
# config/packages/symfinity_form_ui.yaml
symfinity_form_ui:
    theme:
        enabled: true
        wrapper: field   # or floating-field
        live_date: false # true only when live-tier UX Blocks widgets are available
        live_tags: false
```

When `theme.enabled` is true, the bundle prepends `@SymfinityFormUi/form/theme.html.twig` — no extra `framework.form.themes` entry is required. Root forms auto-inject ux-blocks core + form inline CSS (see [Usage](usage.md)).

## Form options

```php
use Symfinity\FormUiExtensionsBundle\Contract\FormUiOptionKeys;
use Symfinity\FormUiExtensionsBundle\Form\Option\NovalidateStrategyResolver;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

$builder->add('save', SubmitType::class, [
    FormUiOptionKeys::BUTTON_METADATA => [
        'intent' => 'primary',
        'tags' => ['checkout'],
    ],
]);

$builder = $formFactory->createBuilder(FormType::class, null, [
    FormUiOptionKeys::NOVALIDATE_STRATEGY => NovalidateStrategyResolver::FORCE_ON,
    FormUiOptionKeys::ERROR_SUMMARY => ['enabled' => true],
]);
```

## Twig

```twig
{{ form_start(form) }}
{{ form_widget(form) }}
{{ form_end(form) }}
```

Wizard, collection, field groups, and other R2 options use `FormUiOptionKeys` — the theme reads the resulting `symfinity_form_ui.*` vars. See [usage.md](usage.md) and [contracts/form-view-vars-catalog.md](contracts/form-view-vars-catalog.md).

## Verify

```bash
php bin/console debug:config symfinity_form_ui
composer test
```

## Support

- [CHANGELOG](../CHANGELOG.md)
- [CONTRIBUTING](../CONTRIBUTING.md)
- [GitHub Issues](https://github.com/symfinity/form-ui-extensions-bundle/issues)
