<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Extension;

use Symfinity\FormUiExtensionsBundle\Contract\FormUiOptionKeys;
use Symfinity\FormUiExtensionsBundle\Contract\FormUiViewNamespace;
use Symfinity\FormUiExtensionsBundle\Form\Option\ButtonMetadataOptionParser;
use Symfinity\FormUiExtensionsBundle\Form\Resolver\FormUiStateMerger;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ButtonMetadataTypeExtension extends AbstractTypeExtension
{
    public function __construct(
        private readonly ButtonMetadataOptionParser $parser,
        private readonly FormUiStateMerger $stateMerger,
    ) {
    }

    public static function getExtendedTypes(): iterable
    {
        return [ButtonType::class, SubmitType::class];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault(FormUiOptionKeys::BUTTON_METADATA, null);
        $resolver->setAllowedTypes(FormUiOptionKeys::BUTTON_METADATA, ['null', 'array']);
        $resolver->setNormalizer(FormUiOptionKeys::BUTTON_METADATA, function (Options $options, mixed $value): array {
            return $this->parser->parse($value);
        });
    }

    /**
     * @param array<string, mixed> $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars = $this->stateMerger->merge($view->vars, [
            FormUiViewNamespace::BUTTON_METADATA => $options[FormUiOptionKeys::BUTTON_METADATA],
        ]);
    }
}
