<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Extension;

use Symfinity\FormUiExtensionsBundle\Contract\FormUiOptionKeys;
use Symfinity\FormUiExtensionsBundle\Contract\FormUiViewNamespace;
use Symfinity\FormUiExtensionsBundle\Form\Option\WizardOptionParser;
use Symfinity\FormUiExtensionsBundle\Form\Resolver\FormUiStateMerger;
use Symfinity\FormUiExtensionsBundle\Form\Resolver\WizardStateResolver;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class WizardTypeExtension extends AbstractTypeExtension
{
    public function __construct(
        private readonly WizardOptionParser $parser,
        private readonly WizardStateResolver $stateResolver,
        private readonly FormUiStateMerger $stateMerger,
    ) {
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault(FormUiOptionKeys::WIZARD, null);
        $resolver->setAllowedTypes(FormUiOptionKeys::WIZARD, ['null', 'array']);
        $resolver->setNormalizer(FormUiOptionKeys::WIZARD, function (Options $options, mixed $value): ?array {
            return $this->parser->parse($value);
        });
    }

    /**
     * @param array<string, mixed> $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $wizard = $options[FormUiOptionKeys::WIZARD];
        if (!\is_array($wizard) || !($wizard['enabled'] ?? false)) {
            return;
        }

        $wizardState = $this->stateResolver->resolveRootState($wizard, $form);
        $view->vars = $this->stateMerger->merge($view->vars, [
            FormUiViewNamespace::WIZARD => $wizardState,
        ]);
    }

    /**
     * @param array<string, mixed> $options
     */
    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $wizard = $options[FormUiOptionKeys::WIZARD];
        if (!\is_array($wizard) || !($wizard['enabled'] ?? false)) {
            return;
        }

        $wizardState = $view->vars[FormUiViewNamespace::ROOT][FormUiViewNamespace::WIZARD] ?? null;
        if (!\is_array($wizardState)) {
            return;
        }

        $fieldStepMap = $wizardState['field_step_map'];
        \assert(\is_array($fieldStepMap));

        foreach ($fieldStepMap as $fieldName => $stepIndex) {
            if (!isset($view[$fieldName])) {
                continue;
            }

            $fieldView = $view[$fieldName];
            $fieldView->vars = $this->stateMerger->mergeSlice($fieldView->vars, FormUiViewNamespace::WIZARD, [
                'step_index' => $stepIndex,
                'visible_in_wizard' => false,
            ]);
        }
    }
}
