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
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ErrorSummaryTypeExtensionTest extends TestCase
{
    public function testItBuildsSummaryForFieldErrors(): void
    {
        $factory = FormUiTestFormFactory::create();

        $form = $factory->createBuilder(FormType::class, null, [
            FormUiOptionKeys::ERROR_SUMMARY => ['enabled' => true],
        ])
            ->add('alpha', TextType::class, ['label' => 'Alpha', 'constraints' => [new NotBlank()]])
            ->add('beta', TextType::class, ['label' => 'Beta', 'constraints' => [new NotBlank()]])
            ->add('gamma', TextType::class, ['label' => 'Gamma', 'constraints' => [new NotBlank()]])
            ->add('save', SubmitType::class)
            ->getForm();

        $form->submit(['alpha' => '', 'beta' => '', 'gamma' => '']);

        $errors = $form->createView()->vars[FormUiViewNamespace::ROOT][FormUiViewNamespace::ERRORS];

        self::assertTrue($errors['has_errors']);
        self::assertSame(3, $errors['error_count']);
        self::assertCount(3, $errors['summary']);
        self::assertSame('form_alpha', $errors['summary'][0]['name']);
    }

    public function testItIncludesGlobalErrors(): void
    {
        $factory = FormUiTestFormFactory::create();

        $form = $factory->createBuilder(FormType::class, null, [
            FormUiOptionKeys::ERROR_SUMMARY => ['enabled' => true],
        ])
            ->add('name', TextType::class)
            ->getForm();

        $form->submit(['name' => '']);
        $form->addError(new FormError('Global failure'));

        $errors = $form->createView()->vars[FormUiViewNamespace::ROOT][FormUiViewNamespace::ERRORS];
        self::assertCount(1, $errors['global']);
        self::assertSame('Global failure', $errors['global'][0]['message']);
    }

    public function testFieldGroupMarksInvalidWhenChildHasError(): void
    {
        $factory = FormUiTestFormFactory::create();

        $builder = $factory->createBuilder(FormType::class)
            ->add('billing', FormType::class, [
                FormUiOptionKeys::FIELD_GROUP => ['legend' => 'Billing', 'group_id' => 'billing-group'],
            ]);
        $builder->get('billing')->add('city', TextType::class, ['constraints' => [new NotBlank()]]);
        $form = $builder->getForm();

        $form->submit(['billing' => ['city' => '']]);

        $billingView = $form->createView()['billing'];
        $group = $billingView->vars[FormUiViewNamespace::ROOT][FormUiViewNamespace::FIELD_GROUP];

        self::assertTrue($group['enabled']);
        self::assertSame('billing-group', $group['group_id']);
        self::assertSame('Billing', $group['legend']);
        self::assertTrue($group['invalid']);
    }
}
