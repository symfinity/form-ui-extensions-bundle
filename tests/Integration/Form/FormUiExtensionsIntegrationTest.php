<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Tests\Integration\Form;

use PHPUnit\Framework\TestCase;
use Symfinity\FormUiExtensionsBundle\Contract\FormUiOptionKeys;
use Symfinity\FormUiExtensionsBundle\Contract\FormUiViewNamespace;
use Symfinity\FormUiExtensionsBundle\Form\Extension\ButtonMetadataTypeExtension;
use Symfinity\FormUiExtensionsBundle\Form\Extension\NovalidateStrategyTypeExtension;
use Symfinity\FormUiExtensionsBundle\Form\Extension\UppercaseNormalizationTypeExtension;
use Symfinity\FormUiExtensionsBundle\Form\Option\ButtonMetadataOptionParser;
use Symfinity\FormUiExtensionsBundle\Form\Option\NovalidateStrategyResolver;
use Symfinity\FormUiExtensionsBundle\Form\Option\UppercaseNormalizationParser;
use Symfinity\FormUiExtensionsBundle\Form\Resolver\FormUiStateMerger;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Forms;

final class FormUiExtensionsIntegrationTest extends TestCase
{
    public function testItExposesButtonMetadataAndNovalidateVars(): void
    {
        $factory = Forms::createFormFactoryBuilder()
            ->addTypeExtension(new ButtonMetadataTypeExtension(new ButtonMetadataOptionParser(), new FormUiStateMerger()))
            ->addTypeExtension(new NovalidateStrategyTypeExtension(new NovalidateStrategyResolver(), new FormUiStateMerger()))
            ->getFormFactory();

        $form = $factory->createBuilder(FormType::class, null, [
            FormUiOptionKeys::NOVALIDATE_STRATEGY => NovalidateStrategyResolver::FORCE_ON,
        ])
            ->add('save', SubmitType::class, [
                FormUiOptionKeys::BUTTON_METADATA => ['intent' => 'primary', 'tags' => ['checkout']],
            ])
            ->getForm();

        $rootVars = $form->createView()->vars[FormUiViewNamespace::ROOT];
        self::assertTrue($rootVars[FormUiViewNamespace::NOVALIDATE]);
        self::assertSame(NovalidateStrategyResolver::FORCE_ON, $rootVars[FormUiViewNamespace::NOVALIDATE_STRATEGY]);

        $buttonVars = $form->createView()['save']->vars[FormUiViewNamespace::ROOT];
        self::assertTrue($buttonVars[FormUiViewNamespace::BUTTON_METADATA]['enabled']);
        self::assertSame('primary', $buttonVars[FormUiViewNamespace::BUTTON_METADATA]['intent']);
    }

    public function testItAppliesOptionalUppercaseNormalization(): void
    {
        $factory = Forms::createFormFactoryBuilder()
            ->addTypeExtension(new UppercaseNormalizationTypeExtension(new UppercaseNormalizationParser(), new FormUiStateMerger()))
            ->getFormFactory();

        $form = $factory->createBuilder(FormType::class)
            ->add('code', TextType::class, [
                FormUiOptionKeys::UPPERCASE_NORMALIZATION => ['enabled' => true, 'mode' => 'strict_ascii'],
            ])
            ->getForm();

        $form->submit(['code' => 'abc123']);
        self::assertSame('ABC123', $form->get('code')->getData());

        $vars = $form->createView()['code']->vars[FormUiViewNamespace::ROOT][FormUiViewNamespace::UPPERCASE];
        self::assertTrue($vars['enabled']);
        self::assertSame('strict_ascii', $vars['mode']);
    }
}
