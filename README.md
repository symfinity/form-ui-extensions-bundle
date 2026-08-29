<div align="center">

# Form UI Extensions Bundle

### Symfony Form UI extensions for metadata and novalidate strategy

[![PHP Version](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php&logoColor=white)](composer.json)
[![Symfony](https://img.shields.io/badge/Symfony-7.4+-343434?style=flat&logo=symfony&logoColor=white)](composer.json)
<br/>
[![CI](https://github.com/symfinity/form-ui-extensions-bundle/actions/workflows/ci.yml/badge.svg)](https://github.com/symfinity/form-ui-extensions-bundle/actions/workflows/ci.yml)
<br/>
[![Release](https://img.shields.io/packagist/v/symfinity/form-ui-extensions-bundle.svg?style=flat&logo=packagist&logoColor=white)](https://packagist.org/packages/symfinity/form-ui-extensions-bundle)
[![Downloads](https://img.shields.io/packagist/dt/symfinity/form-ui-extensions-bundle.svg?style=flat&logo=packagist&logoColor=white)](https://packagist.org/packages/symfinity/form-ui-extensions-bundle)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat)](LICENSE)

</div>

> [!NOTE]
> **Read-only mirror.**
> See [CONTRIBUTING.md](CONTRIBUTING.md) for how to propose changes.

## Features
- **FormView vars** — button metadata, novalidate strategy, uppercase normalization
- **Theme-agnostic** — works with any Symfony form theme
- **Optional ux-blocks bridge** — pair with `symfinity/ux-blocks-form` for widgets
- **Flex recipe** — bundle registration on install

## Prerequisites

Add the [symfinity/recipes](https://github.com/symfinity/recipes) Flex endpoint to your project's `composer.json` (see [recipes README](https://github.com/symfinity/recipes/blob/main/README.md)) — recipes are not in Symfony's official recipe repository yet.

## Installation
```bash
composer require symfinity/form-ui-extensions-bundle symfinity/ux-blocks-form
```

The theme bridge auto-injects ux-blocks inline CSS on `form_start` — ui-kernel optional for full theming. See [Quick start](docs/quickstart.md).

## Documentation
- **[Quick start](docs/quickstart.md)** — minimal setup path
- **[Installation](docs/installation.md)** — Flex, dependencies, verify
- **[Configuration](docs/configuration.md)** — bundle and app options
- **[Usage](docs/usage.md)** — day-to-day patterns
- **[Upgrade](docs/upgrade.md)** — version migrations

## Requirements
- PHP 8.2 or higher
- Symfony **7.4** or **8.x**

## Support
- [GitHub Issues](https://github.com/symfinity/form-ui-extensions-bundle/issues)
- [Security](.github/SECURITY.md)
- [Contributing](CONTRIBUTING.md)

## License

[MIT](LICENSE)
