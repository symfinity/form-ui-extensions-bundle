<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Tests\Integration\Form;

use PHPUnit\Framework\TestCase;
use Symfinity\FormUiExtensionsBundle\Contract\FormUiOptionKeys;
use Symfinity\FormUiExtensionsBundle\Contract\FormUiViewNamespace;
use Symfinity\FormUiExtensionsBundle\Tests\Integration\Form\FormUiTestFormFactory;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;

final class WizardTypeExtensionTest extends TestCase
{
    public function testItExposesWizardMetadataOnRootView(): void
    {
        $factory = FormUiTestFormFactory::create();

        $form = $factory->createBuilder(FormType::class, null, [
            FormUiOptionKeys::WIZARD => [
                'steps' => [
                    ['id' => 'account', 'label' => 'Account', 'fields' => ['email']],
                    ['id' => 'profile', 'label' => 'Profile', 'fields' => ['name', 'bio']],
                ],
            ],
        ])
            ->add('email', TextType::class)
            ->add('name', TextType::class)
            ->add('bio', TextType::class)
            ->getForm();

        $root = $form->createView()->vars[FormUiViewNamespace::ROOT][FormUiViewNamespace::WIZARD];

        self::assertTrue($root['enabled']);
        self::assertSame(2, $root['step_count']);
        self::assertSame(1, $root['field_step_map']['email']);
        self::assertSame(2, $root['field_step_map']['name']);
        self::assertSame(['account', 'profile'], array_column($root['steps'], 'id'));
    }

    public function testItComputesInvalidStepsAfterFailedSubmit(): void
    {
        $factory = FormUiTestFormFactory::create();

        $form = $factory->createBuilder(FormType::class, null, [
            FormUiOptionKeys::WIZARD => [
                'steps' => [
                    ['id' => 'one', 'label' => 'One', 'fields' => ['a']],
                    ['id' => 'two', 'label' => 'Two', 'fields' => ['b']],
                ],
            ],
        ])
            ->add('a', TextType::class, ['constraints' => [new NotBlank()]])
            ->add('b', TextType::class, ['constraints' => [new NotBlank()]])
            ->getForm();

        $form->submit(['a' => 'ok', 'b' => null]);

        $root = $form->createView()->vars[FormUiViewNamespace::ROOT][FormUiViewNamespace::WIZARD];
        self::assertSame([2], $root['invalid_steps']);
    }

    public function testFieldViewsReceiveStepIndex(): void
    {
        $factory = FormUiTestFormFactory::create();

        $form = $factory->createBuilder(FormType::class, null, [
            FormUiOptionKeys::WIZARD => [
                'steps' => [
                    ['id' => 's1', 'label' => 'S1', 'fields' => ['email']],
                ],
            ],
        ])
            ->add('email', TextType::class)
            ->getForm();

        $view = $form->createView();
        $fieldWizard = $view['email']->vars[FormUiViewNamespace::ROOT][FormUiViewNamespace::WIZARD];

        self::assertSame(1, $fieldWizard['step_index']);
    }
}
