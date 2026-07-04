<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class FormUiThemeUiAssetsExtension extends AbstractExtension
{
    public function __construct(
        private readonly FormUiThemeUiAssetsRenderer $uiAssetsRenderer,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('form_ui_extensions_ui_assets', $this->uiAssetsRenderer->renderInlineStylesOnce(...), ['is_safe' => ['html']]),
        ];
    }
}
