# Usage

## FormView vars (R1 + R2)

Options on form types resolve to nested `symfinity_form_ui.*` keys on `FormView.vars`. Import keys from `FormUiOptionKeys`:

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

$formFactory->createBuilder(FormType::class, null, [
    FormUiOptionKeys::NOVALIDATE_STRATEGY => NovalidateStrategyResolver::FORCE_ON,
    FormUiOptionKeys::ERROR_SUMMARY => ['enabled' => true],
]);
```

Full key catalog: [contracts/form-view-vars-catalog.md](contracts/form-view-vars-catalog.md).

## ux-blocks theme rendering

With `symfinity_form_ui.theme.enabled: true`, Symfony delegates widgets to ux-blocks Twig components per the type map. Unmapped types fall back to `form_div_layout`.

```twig
{{ form_start(form) }}
{{ form_widget(form) }}
{{ form_end(form) }}
```

Inspect rendered HTML for `data-ui-role="field"` (or `floating-field`) on bridged fields.

## Common patterns

### Button metadata

Attach intent, variant, tags, or disabled reason to submit/button fields — consumed by the theme `Button` role.

### Error summary

Enable at form root with `FormUiOptionKeys::ERROR_SUMMARY`; theme renders an accessible summary region on `form_start`.

### Wizard, collection, upload, date range

R2 options populate `wizard.*`, `collection.*`, `upload.*`, and `date_range.*` vars — see package contracts for field-level detail.

### Uppercase normalization

Field-level uppercase transformer via `FormUiOptionKeys::UPPERCASE`.

## Optional packages

| Package | Enables |
|---------|---------|
| `symfinity/ux-blocks-live` | `theme.live_date` / `theme.live_tags` |
| `symfinity/ui-kernel` | Full design-token CSS (Chameleon look) |

## Pitfalls

- Installing without `symfinity/ux-blocks-form` fails at Composer resolve time (hard require).
- Live theme flags without `ux-blocks-live` installed may render incorrectly — keep flags `false` unless the package is present.
- See [Troubleshooting](troubleshooting.md) for Flex recipe and theme issues.

## See also

[Quick start](quickstart.md) · [Configuration](configuration.md) · [Reference](reference.md)
