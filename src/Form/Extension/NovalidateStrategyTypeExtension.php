<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Extension;

use Symfinity\FormUiExtensionsBundle\Contract\FormUiOptionKeys;
use Symfinity\FormUiExtensionsBundle\Contract\FormUiViewNamespace;
use Symfinity\FormUiExtensionsBundle\Form\Option\NovalidateStrategyResolver;
use Symfinity\FormUiExtensionsBundle\Form\Resolver\FormUiStateMerger;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class NovalidateStrategyTypeExtension extends AbstractTypeExtension
{
    public function __construct(
        private readonly NovalidateStrategyResolver $resolver,
        private readonly FormUiStateMerger $stateMerger,
    ) {
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault(FormUiOptionKeys::NOVALIDATE_STRATEGY, NovalidateStrategyResolver::INHERIT);
        $resolver->setAllowedValues(FormUiOptionKeys::NOVALIDATE_STRATEGY, [
            NovalidateStrategyResolver::INHERIT,
            NovalidateStrategyResolver::FORCE_ON,
            NovalidateStrategyResolver::FORCE_OFF,
        ]);
    }

    /**
     * @param array<string, mixed> $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $strategy = $options[FormUiOptionKeys::NOVALIDATE_STRATEGY];
        \assert(\is_string($strategy));

        $view->vars = $this->stateMerger->merge($view->vars, [
            FormUiViewNamespace::NOVALIDATE => $this->resolver->resolve($strategy),
            FormUiViewNamespace::NOVALIDATE_STRATEGY => $strategy,
        ]);
    }
}
