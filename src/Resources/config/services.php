<?php

declare(strict_types=1);

use Symfinity\FormUiExtensionsBundle\Form\Extension\ButtonMetadataTypeExtension;
use Symfinity\FormUiExtensionsBundle\Form\Extension\CollectionUxTypeExtension;
use Symfinity\FormUiExtensionsBundle\Form\Extension\DateRangeTypeExtension;
use Symfinity\FormUiExtensionsBundle\Form\Extension\ErrorSummaryTypeExtension;
use Symfinity\FormUiExtensionsBundle\Form\Extension\FieldGroupTypeExtension;
use Symfinity\FormUiExtensionsBundle\Form\Extension\NovalidateStrategyTypeExtension;
use Symfinity\FormUiExtensionsBundle\Form\Extension\UploadUxTypeExtension;
use Symfinity\FormUiExtensionsBundle\Form\Extension\UppercaseNormalizationTypeExtension;
use Symfinity\FormUiExtensionsBundle\Form\Extension\WizardTypeExtension;
use Symfinity\FormUiExtensionsBundle\Form\Option\ButtonMetadataOptionParser;
use Symfinity\FormUiExtensionsBundle\Form\Option\CollectionUxOptionParser;
use Symfinity\FormUiExtensionsBundle\Form\Option\DateRangeOptionParser;
use Symfinity\FormUiExtensionsBundle\Form\Option\ErrorSummaryOptionParser;
use Symfinity\FormUiExtensionsBundle\Form\Option\FieldGroupOptionParser;
use Symfinity\FormUiExtensionsBundle\Form\Option\NovalidateStrategyResolver;
use Symfinity\FormUiExtensionsBundle\Form\Option\UploadUxOptionParser;
use Symfinity\FormUiExtensionsBundle\Form\Option\UppercaseNormalizationParser;
use Symfinity\FormUiExtensionsBundle\Form\Option\WizardOptionParser;
use Symfinity\FormUiExtensionsBundle\Form\Resolver\ErrorSummaryBuilder;
use Symfinity\FormUiExtensionsBundle\Form\Resolver\FormUiStateMerger;
use Symfinity\FormUiExtensionsBundle\Form\Resolver\WizardStateResolver;
use Symfinity\FormUiExtensionsBundle\Form\Transformer\UppercaseTransformer;
use Symfinity\FormUiExtensionsBundle\Twig\FormThemeTwigExtension;
use Symfinity\FormUiExtensionsBundle\Twig\FormUiThemeUiAssetsExtension;
use Symfinity\FormUiExtensionsBundle\Twig\FormUiThemeUiAssetsRenderer;
use Symfinity\FormUiExtensionsBundle\Validator\Constraints\DateRangeValidValidator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(FormUiStateMerger::class);
    $services->set(WizardStateResolver::class);
    $services->set(ErrorSummaryBuilder::class);

    $services->set(ButtonMetadataOptionParser::class);
    $services->set(NovalidateStrategyResolver::class);
    $services->set(UppercaseNormalizationParser::class);
    $services->set(WizardOptionParser::class);
    $services->set(CollectionUxOptionParser::class);
    $services->set(UploadUxOptionParser::class);
    $services->set(DateRangeOptionParser::class);
    $services->set(ErrorSummaryOptionParser::class);
    $services->set(FieldGroupOptionParser::class);
    $services->set(UppercaseTransformer::class);

    $services->set(ButtonMetadataTypeExtension::class)->tag('form.type_extension');
    $services->set(NovalidateStrategyTypeExtension::class)->tag('form.type_extension');
    $services->set(UppercaseNormalizationTypeExtension::class)->tag('form.type_extension');
    $services->set(WizardTypeExtension::class)->tag('form.type_extension');
    $services->set(CollectionUxTypeExtension::class)->tag('form.type_extension');
    $services->set(UploadUxTypeExtension::class)->tag('form.type_extension');
    $services->set(DateRangeTypeExtension::class)->tag('form.type_extension');
    $services->set(ErrorSummaryTypeExtension::class)->tag('form.type_extension');
    $services->set(FieldGroupTypeExtension::class)->tag('form.type_extension');

    $services->set(DateRangeValidValidator::class)->tag('validator.constraint_validator');

    $services->set(FormThemeTwigExtension::class)
        ->args([
            '$wrapper' => '%symfinity_form_ui.theme.wrapper%',
            '$liveDate' => '%symfinity_form_ui.theme.live_date%',
            '$liveTags' => '%symfinity_form_ui.theme.live_tags%',
        ])
        ->tag('twig.extension');

    $services->set(FormUiThemeUiAssetsRenderer::class)
        ->arg('$coreCssProvider', service('Symfinity\\UxBlocksCore\\Css\\BlocksCoreCssProvider')->nullOnInvalid())
        ->arg('$formCssProvider', service('Symfinity\\UxBlocksForm\\Css\\BlocksFormCssProvider')->nullOnInvalid());

    $services->set(FormUiThemeUiAssetsExtension::class)
        ->tag('twig.extension');
};
