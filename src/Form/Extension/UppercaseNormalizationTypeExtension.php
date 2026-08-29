<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Extension;

use Symfinity\FormUiExtensionsBundle\Contract\FormUiOptionKeys;
use Symfinity\FormUiExtensionsBundle\Contract\FormUiViewNamespace;
use Symfinity\FormUiExtensionsBundle\Form\Option\UppercaseNormalizationParser;
use Symfinity\FormUiExtensionsBundle\Form\Resolver\FormUiStateMerger;
use Symfinity\FormUiExtensionsBundle\Form\Transformer\UppercaseTransformer;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UppercaseNormalizationTypeExtension extends AbstractTypeExtension
{
    public function __construct(
        private readonly UppercaseNormalizationParser $parser,
        private readonly FormUiStateMerger $stateMerger,
    ) {
    }

    public static function getExtendedTypes(): iterable
    {
        return [TextType::class];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault(FormUiOptionKeys::UPPERCASE_NORMALIZATION, false);
        $resolver->setAllowedTypes(FormUiOptionKeys::UPPERCASE_NORMALIZATION, ['bool', 'array']);
        $resolver->setNormalizer(FormUiOptionKeys::UPPERCASE_NORMALIZATION, function (Options $options, mixed $value): array {
            return $this->parser->parse($value);
        });
    }

    /**
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $config = $options[FormUiOptionKeys::UPPERCASE_NORMALIZATION];
        \assert(\is_array($config));

        if (($config['enabled'] ?? false) !== true) {
            return;
        }

        $builder->addModelTransformer(
            new UppercaseTransformer(
                (string) ($config['mode'] ?? 'strict_ascii'),
                (bool) ($config['trim_before'] ?? false),
            ),
            true,
        );
    }

    /**
     * @param array<string, mixed> $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $config = $options[FormUiOptionKeys::UPPERCASE_NORMALIZATION];
        \assert(\is_array($config));

        $view->vars = $this->stateMerger->merge($view->vars, [
            FormUiViewNamespace::UPPERCASE => [
                'enabled' => (bool) ($config['enabled'] ?? false),
                'mode' => $config['mode'] ?? null,
                'applied' => (bool) ($config['enabled'] ?? false),
            ],
        ]);
    }
}
