<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Tests\Integration;

use PHPUnit\Framework\Attributes\Test;
use Symfinity\FormUiExtensionsBundle\Tests\Integration\Form\FormUiTestFormFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Twig\Environment;

/**
 * Templates that print fields one by one and then call form_end(), the usual Symfony pattern
 * (Face's public contact form, usability step 1, F11/F12).
 */
final class FormThemeFieldByFieldTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return FormUiBridgeTestKernel::class;
    }

    #[Test]
    public function formEndPrintsTheFieldsNotYetRenderedIncludingHiddenTokens(): void
    {
        $html = $this->render($this->formWithHiddenToken());

        self::assertStringContainsString('name="form[_token]"', $html);
        self::assertStringContainsString('value="token-value"', $html);
        self::assertStringContainsString('</form>', $html);
    }

    #[Test]
    public function formEndLeavesTheRestOutWhenTheTemplateAsksForIt(): void
    {
        $html = $this->render($this->formWithHiddenToken(), ['render_rest' => false]);

        self::assertStringNotContainsString('name="form[_token]"', $html);
        self::assertStringContainsString('</form>', $html);
    }

    #[Test]
    public function aFieldWithoutAnExplicitLabelGetsItsHumanizedName(): void
    {
        $form = FormUiTestFormFactory::create()->createBuilder(FormType::class)
            ->add('name', TextType::class)
            ->getForm();

        $html = $this->render($form);

        self::assertMatchesRegularExpression('#<label[^>]*for="form_name"[^>]*>Name</label>#', $html);
    }

    #[Test]
    public function aFieldWithLabelFalseStaysWithoutLabel(): void
    {
        $form = FormUiTestFormFactory::create()->createBuilder(FormType::class)
            ->add('name', TextType::class, ['label' => false])
            ->getForm();

        $html = $this->render($form);

        self::assertStringNotContainsString('<label', $html);
    }

    #[Test]
    public function aCheckboxWithoutAnExplicitLabelGetsItsHumanizedName(): void
    {
        $form = FormUiTestFormFactory::create()->createBuilder(FormType::class)
            ->add('name', CheckboxType::class)
            ->getForm();

        $html = $this->render($form);

        self::assertMatchesRegularExpression('#<label[^>]*for="form_name"[^>]*>Name</label>#', $html);
    }

    /** @return FormInterface<mixed> */
    private function formWithHiddenToken(): FormInterface
    {
        return FormUiTestFormFactory::create()->createBuilder(FormType::class)
            ->add('name', TextType::class, ['label' => 'Name'])
            ->add('_token', HiddenType::class, ['data' => 'token-value'])
            ->getForm();
    }

    /**
     * @param FormInterface<mixed> $form
     * @param array<string, mixed> $context
     */
    private function render(FormInterface $form, array $context = []): string
    {
        self::bootKernel();
        $twig = self::getContainer()->get('twig');
        self::assertInstanceOf(Environment::class, $twig);

        return $twig->render('@FormUiExtensionsTests/form_field_by_field.html.twig', ['form' => $form->createView()] + $context);
    }
}
