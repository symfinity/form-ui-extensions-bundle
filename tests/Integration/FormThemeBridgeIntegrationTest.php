<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Tests\Integration;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfinity\FormUiExtensionsBundle\Contract\FormUiOptionKeys;
use Symfinity\FormUiExtensionsBundle\Tests\Integration\Form\FormUiTestFormFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;

final class FormThemeBridgeIntegrationTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return FormUiBridgeTestKernel::class;
    }

    #[Test]
    public function itRendersTextFieldWithUxBlocksRoles(): void
    {
        self::bootKernel();

        $html = $this->renderForm(static function (FormFactoryInterface $factory) {
            return $factory->createBuilder(FormType::class)
                ->add('email', TextType::class, ['label' => 'Email'])
                ->getForm();
        });

        self::assertStringContainsString('data-ui-role="field"', $html);
        self::assertStringContainsString('data-ui-role="input"', $html);
        self::assertStringContainsString('>Email<', $html);
        self::assertDoesNotMatchRegularExpression('/id="[^"]+"[^>]+id="/', $html);
    }

    #[Test]
    public function itRendersEmailAndTextareaWidgets(): void
    {
        $html = $this->renderForm(static function (FormFactoryInterface $factory) {
            return $factory->createBuilder(FormType::class)
                ->add('email', EmailType::class)
                ->add('bio', TextareaType::class)
                ->getForm();
        });

        self::assertStringContainsString('type="email"', $html);
        self::assertStringContainsString('<textarea', $html);
        self::assertStringContainsString('data-ui-role="input"', $html);
    }

    #[Test]
    public function itUsesLegacyMarkupWhenThemeDisabled(): void
    {
        self::ensureKernelShutdown();
        self::bootKernel(['themeEnabled' => false]);

        $html = $this->renderForm(static function (FormFactoryInterface $factory) {
            return $factory->createBuilder(FormType::class)
                ->add('name', TextType::class)
                ->getForm();
        });

        self::assertStringNotContainsString('data-ui-role="field"', $html);
        self::assertStringContainsString('<input type="text"', $html);
    }

    /**
     * @param callable(FormFactoryInterface): \Symfony\Component\Form\FormInterface $builder
     */
    private function renderForm(callable $builder): string
    {
        if (!static::$booted) {
            self::bootKernel();
        }

        $factory = FormUiTestFormFactory::create();
        $form = $builder($factory);
        $twig = self::getContainer()->get('twig');

        return (string) $twig->render('@FormUiExtensionsTests/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public static function mappedFieldTypeProvider(): iterable
    {
        yield 'choice collapsed' => [
            ChoiceType::class,
            ['choices' => ['A' => 'a', 'B' => 'b']],
            ['<select', 'data-ui-role="select"'],
        ];
        yield 'checkbox' => [
            CheckboxType::class,
            ['label' => 'Accept'],
            ['type="checkbox"', 'data-ui-role="checkbox"'],
        ];
        yield 'file upload' => [
            FileType::class,
            ['label' => 'Avatar'],
            ['type="file"', 'data-ui-role="file-upload"'],
        ];
        yield 'range' => [
            RangeType::class,
            ['label' => 'Volume'],
            ['type="range"', 'data-ui-role="range"'],
        ];
        yield 'date native' => [
            DateType::class,
            ['widget' => 'single_text', 'html5' => true],
            ['type="date"', 'data-ui-role="input"'],
        ];
        yield 'submit button' => [
            SubmitType::class,
            ['label' => 'Save'],
            ['type="submit"', 'data-ui-role="button"'],
        ];
    }
    #[Test]
    #[DataProvider('mappedFieldTypeProvider')]
    public function itMapsCommonSymfonyFieldTypes(string $type, array $options, array $needles): void
    {
        self::bootKernel();

        $html = $this->renderForm(static function (FormFactoryInterface $factory) use ($type, $options) {
            return $factory->createBuilder(FormType::class)
                ->add('field', $type, $options)
                ->getForm();
        });

        foreach ($needles as $needle) {
            self::assertStringContainsString($needle, $html);
        }
    }

    #[Test]
    public function itFallsBackForUnmappedCustomWidget(): void
    {
        $html = $this->renderForm(static function (FormFactoryInterface $factory) {
            return $factory->createBuilder(FormType::class)
                ->add('custom', TextType::class, ['block_name' => 'custom_unmapped'])
                ->getForm();
        });

        self::assertStringContainsString('name="form[custom]"', $html);
        self::assertStringNotContainsString('data-ui-role="input"', $html);
    }

    #[Test]
    public function itRendersFieldsetFromFieldGroupVar(): void
    {
        $html = $this->renderForm(static function (FormFactoryInterface $factory) {
            $builder = $factory->createBuilder(FormType::class)
                ->add('billing', FormType::class, [
                    FormUiOptionKeys::FIELD_GROUP => [
                        'legend' => 'Billing',
                        'group_id' => 'billing-group',
                    ],
                ]);
            $builder->get('billing')->add('line1', TextType::class);

            return $builder->getForm();
        });

        self::assertStringContainsString('<fieldset', $html);
        self::assertStringContainsString('<legend>Billing</legend>', $html);
        self::assertStringContainsString('data-ui-role="fieldset"', $html);
    }

    #[Test]
    public function itRendersErrorSummaryFromR2Vars(): void
    {
        $html = $this->renderForm(static function (FormFactoryInterface $factory) {
            $form = $factory->createBuilder(FormType::class, null, [
                FormUiOptionKeys::ERROR_SUMMARY => ['enabled' => true],
            ])
                ->add('email', EmailType::class, ['constraints' => [new \Symfony\Component\Validator\Constraints\NotBlank()]])
                ->getForm();

            $form->submit(['email' => '']);

            return $form;
        });

        self::assertStringContainsString('data-ui-part="error-summary"', $html);
    }

    #[Test]
    public function itExposesCollectionRowMetadata(): void
    {
        $html = $this->renderForm(static function (FormFactoryInterface $factory) {
            return $factory->createBuilder(FormType::class)
                ->add('tags', CollectionType::class, [
                    'entry_type' => TextType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'data' => ['alpha'],
                    FormUiOptionKeys::COLLECTION_UX => ['enabled' => true],
                ])
                ->getForm();
        });

        self::assertStringContainsString('data-ui-collection="1"', $html);
        self::assertStringContainsString('data-ui-collection-row="1"', $html);
    }

    protected static function createKernel(array $options = []): \Symfony\Component\HttpKernel\KernelInterface
    {
        return new FormUiBridgeTestKernel(
            $options['environment'] ?? 'test',
            (bool) ($options['debug'] ?? true),
            (bool) ($options['themeEnabled'] ?? true),
            (string) ($options['themeWrapper'] ?? 'field'),
        );
    }
}
