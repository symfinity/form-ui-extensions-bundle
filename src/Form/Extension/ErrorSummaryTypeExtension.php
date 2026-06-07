<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Extension;

use Symfinity\FormUiExtensionsBundle\Contract\FormUiOptionKeys;
use Symfinity\FormUiExtensionsBundle\Contract\FormUiViewNamespace;
use Symfinity\FormUiExtensionsBundle\Form\Option\ErrorSummaryOptionParser;
use Symfinity\FormUiExtensionsBundle\Form\Resolver\ErrorSummaryBuilder;
use Symfinity\FormUiExtensionsBundle\Form\Resolver\FormUiStateMerger;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ErrorSummaryTypeExtension extends AbstractTypeExtension
{
    public function __construct(
        private readonly ErrorSummaryOptionParser $parser,
        private readonly ErrorSummaryBuilder $errorSummaryBuilder,
        private readonly FormUiStateMerger $stateMerger,
    ) {
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault(FormUiOptionKeys::ERROR_SUMMARY, null);
        $resolver->setAllowedTypes(FormUiOptionKeys::ERROR_SUMMARY, ['null', 'array', 'bool']);
        $resolver->setNormalizer(FormUiOptionKeys::ERROR_SUMMARY, function (Options $options, mixed $value): ?array {
            if (\is_bool($value)) {
                return $this->parser->parse(['enabled' => $value]);
            }

            return $this->parser->parse($value);
        });
    }

    /**
     * @param array<string, mixed> $options
     */
    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $errorSummary = $options[FormUiOptionKeys::ERROR_SUMMARY];
        if (!\is_array($errorSummary) || !($errorSummary['enabled'] ?? false)) {
            return;
        }

        if (!$form->isSubmitted()) {
            return;
        }

        $errors = $this->errorSummaryBuilder->build($form, $view, $errorSummary);

        $view->vars = $this->stateMerger->merge($view->vars, [
            FormUiViewNamespace::ERRORS => $errors,
        ]);
    }
}
