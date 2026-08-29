<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Tests\Integration\Form;

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
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Validator\Validation;

final class FormUiTestFormFactory
{
    public static function create(): FormFactoryInterface
    {
        $merger = new FormUiStateMerger();
        $validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();

        return Forms::createFormFactoryBuilder()
            ->addExtension(new ValidatorExtension($validator))
            ->addTypeExtension(new ButtonMetadataTypeExtension(new ButtonMetadataOptionParser(), $merger))
            ->addTypeExtension(new NovalidateStrategyTypeExtension(new NovalidateStrategyResolver(), $merger))
            ->addTypeExtension(new UppercaseNormalizationTypeExtension(new UppercaseNormalizationParser(), $merger))
            ->addTypeExtension(new WizardTypeExtension(new WizardOptionParser(), new WizardStateResolver(), $merger))
            ->addTypeExtension(new CollectionUxTypeExtension(new CollectionUxOptionParser(), $merger))
            ->addTypeExtension(new UploadUxTypeExtension(new UploadUxOptionParser(), $merger))
            ->addTypeExtension(new DateRangeTypeExtension(new DateRangeOptionParser(), $merger))
            ->addTypeExtension(new ErrorSummaryTypeExtension(new ErrorSummaryOptionParser(), new ErrorSummaryBuilder(), $merger))
            ->addTypeExtension(new FieldGroupTypeExtension(new FieldGroupOptionParser(), $merger))
            ->getFormFactory();
    }
}
