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

When `theme.enabled` is true, Symfony delegates widgets to ux-blocks Twig components per the type map. Unmapped types fall back to `form_div_layout`.

The theme bridge calls `form_ui_extensions_ui_assets()` on each root `form_start` — core + form tier CSS inline once per request. No host `importmap.php` entry or layout paste required for baseline styling.

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
| [symfinity/ui-kernel](https://packagist.org/packages/symfinity/ui-kernel) | Full design-token CSS (Chameleon look) beyond inline tier CSS |

`theme.live_date` and `theme.live_tags` require optional live-tier UX Blocks widgets (not published on Packagist yet).

## Pitfalls

- Installing without `symfinity/ux-blocks-form` fails at Composer resolve time (hard require).
- Live theme flags without live-tier widgets installed may render incorrectly — keep flags `false` unless those widgets are present.
- Unstyled fields with missing `id="ux-blocks-core-css"` — confirm `theme.enabled: true` and ux-blocks-form is installed; see [Troubleshooting](troubleshooting.md).

## See also

[Quick start](quickstart.md) · [Configuration](configuration.md) · [Reference](reference.md)
