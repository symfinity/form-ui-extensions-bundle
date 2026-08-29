<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('symfinity_form_ui');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('theme')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
                        ->enumNode('wrapper')->values(['field', 'floating-field'])->defaultValue('field')->end()
                        ->booleanNode('live_date')->defaultFalse()->end()
                        ->booleanNode('live_tags')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
