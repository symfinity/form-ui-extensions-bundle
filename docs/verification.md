## Verification Notes

Maintainer QA baseline for split mirror **v0.1.0** (product monorepo checkout):

| Gate | Result |
|------|--------|
| PHPUnit | 41 tests, 110 assertions |
| PHPStan | Level max (with baseline) |
| Flex recipe | `recipes/symfinity/form-ui-extensions-bundle/0.1/` — manifest + `post-install.txt` |

Consumer verification after install:

```bash
composer test
composer phpstan
php bin/console debug:config symfinity_form_ui
```

Render a form in the browser or a WebTestCase and confirm `data-ui-role="field"` (or `floating-field`) when the theme bridge is enabled.

External smoke: clean Symfony 7.4 app, `composer require symfinity/form-ui-extensions-bundle symfinity/ux-blocks-form`, submit a sample form — HTTP 200 with ux-blocks field markup.

Org dogfood:

| Lab | Route | Expect |
|-----|-------|--------|
| `form-ui-extensions-lab` | `/form-ui-extensions` | `data-ui-role="field"` in body |
| `ux-blocks-kiosk-lab` | `/kiosk/blocks/form/symfony-bridge` | themed Symfony Form + error summary on empty submit |

Maintainer publish gate: [_org release-readiness-gate](../../../../../specs/symfinity/symfinity/_org/contracts/form-ui-extensions/release-readiness-gate.md).
