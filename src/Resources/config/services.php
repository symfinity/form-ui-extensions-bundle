<?php

declare(strict_types=1);

use Symfinity\FormUiExtensionsBundle\Form\Extension\ButtonMetadataTypeExtension;
use Symfinity\FormUiExtensionsBundle\Form\Extension\NovalidateStrategyTypeExtension;
use Symfinity\FormUiExtensionsBundle\Form\Extension\UppercaseNormalizationTypeExtension;
use Symfinity\FormUiExtensionsBundle\Form\Option\ButtonMetadataOptionParser;
use Symfinity\FormUiExtensionsBundle\Form\Option\NovalidateStrategyResolver;
use Symfinity\FormUiExtensionsBundle\Form\Option\UppercaseNormalizationParser;
use Symfinity\FormUiExtensionsBundle\Form\Resolver\FormUiStateMerger;
use Symfinity\FormUiExtensionsBundle\Form\Transformer\UppercaseTransformer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(FormUiStateMerger::class);
    $services->set(ButtonMetadataOptionParser::class);
    $services->set(NovalidateStrategyResolver::class);
    $services->set(UppercaseNormalizationParser::class);
    $services->set(UppercaseTransformer::class);

    $services->set(ButtonMetadataTypeExtension::class)->tag('form.type_extension');
    $services->set(NovalidateStrategyTypeExtension::class)->tag('form.type_extension');
    $services->set(UppercaseNormalizationTypeExtension::class)->tag('form.type_extension');
};
