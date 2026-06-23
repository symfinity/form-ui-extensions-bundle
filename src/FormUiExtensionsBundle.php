<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle;

use Symfinity\FormUiExtensionsBundle\DependencyInjection\FormUiExtensionsExtension;
use Symfony\Bundle\TwigBundle\DependencyInjection\Configurator\TwigConfigurator;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class FormUiExtensionsBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function getContainerExtension(): ExtensionInterface
    {
        return new FormUiExtensionsExtension();
    }

    public function configureTwig(TwigConfigurator $configurator): void
    {
        $configurator->path($this->getPath() . '/templates', 'SymfinityFormUi');
    }
}
