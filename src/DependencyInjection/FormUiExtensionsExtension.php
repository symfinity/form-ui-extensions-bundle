<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\DependencyInjection;

use Symfinity\FormUiExtensionsBundle\Twig\FormThemeTwigExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class FormUiExtensionsExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @param array<array-key, mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('symfinity_form_ui.theme.enabled', (bool) $config['theme']['enabled']);
        $container->setParameter('symfinity_form_ui.theme.wrapper', (string) $config['theme']['wrapper']);
        $container->setParameter('symfinity_form_ui.theme.live_date', (bool) $config['theme']['live_date']);
        $container->setParameter('symfinity_form_ui.theme.live_tags', (bool) $config['theme']['live_tags']);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.php');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $configs = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration(new Configuration(), $configs);

        if (!$config['theme']['enabled']) {
            return;
        }

        if ($container->hasExtension('twig')) {
            $container->prependExtensionConfig('twig', [
                'form_themes' => [
                    '@SymfinityFormUi/form/theme.html.twig',
                ],
            ]);
        }
    }

    public function getAlias(): string
    {
        return 'symfinity_form_ui';
    }
}
