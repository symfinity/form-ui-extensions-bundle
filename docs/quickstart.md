# Quickstart: symfinity/form-ui-extensions-bundle

## Install (FormView vars only)

```bash
composer require symfinity/form-ui-extensions-bundle
```

Requires `symfony/form` in the host application.

## Bridge install (113 — ux-blocks rendering)

```bash
composer require symfinity/form-ui-extensions-bundle symfinity/ux-blocks-form
```

```yaml
# config/packages/symfinity_form_ui.yaml
symfinity_form_ui:
    theme:
        enabled: true
        wrapper: field   # or floating-field
        live_date: false # set true when symfinity/ux-blocks-live is installed
        live_tags: false
```

When `theme.enabled` is true, the bundle prepends `@SymfinityFormUi/form/theme.html.twig` — no extra `framework.form.themes` entry is required.

## Form options

```php
use Symfinity\FormUiExtensionsBundle\Contract\FormUiOptionKeys;
use Symfinity\FormUiExtensionsBundle\Form\Option\NovalidateStrategyResolver;

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

R2 options (wizard, collection, field groups, …) use existing `FormUiOptionKeys` — the theme reads the resulting `symfinity_form_ui.*` vars.

## Dogfood

```bash
make dogfood-new SLUG=form-ui-extensions-lab VERSION='7.4.*'
make dogfood-serve SLUG=form-ui-extensions-lab
```

Browse `/form-ui-extensions`.

## Tests

```bash
cd src/symfinity
./bin/php vendor/bin/phpunit packages/form-ui-extensions-bundle/tests/
```
