# Quickstart: symfinity/form-ui-extensions-bundle

## Install

```bash
composer require symfinity/form-ui-extensions-bundle
```

Requires `symfony/form` in the host application.

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
]);
```

## FormView namespace

Read vars under `symfinity_form_ui` in Twig — see [form-view-vars-surface](contracts/form-view-vars-surface.md).  
The bundle does not render HTML.

## Dogfood

`symfinity/ux-blocks-demo` — route `/form-ui-extensions`.

## Tests

```bash
cd src/symfinity
./sbin/composer --working-dir=packages/form-ui-extensions-bundle test
```
