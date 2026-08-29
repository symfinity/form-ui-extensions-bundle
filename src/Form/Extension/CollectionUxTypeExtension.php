<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Extension;

use Symfinity\FormUiExtensionsBundle\Contract\FormUiOptionKeys;
use Symfinity\FormUiExtensionsBundle\Contract\FormUiViewNamespace;
use Symfinity\FormUiExtensionsBundle\Form\Option\CollectionUxOptionParser;
use Symfinity\FormUiExtensionsBundle\Form\Resolver\FormUiStateMerger;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CollectionUxTypeExtension extends AbstractTypeExtension
{
    public function __construct(
        private readonly CollectionUxOptionParser $parser,
        private readonly FormUiStateMerger $stateMerger,
    ) {
    }

    public static function getExtendedTypes(): iterable
    {
        return [CollectionType::class];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault(FormUiOptionKeys::COLLECTION_UX, null);
        $resolver->setAllowedTypes(FormUiOptionKeys::COLLECTION_UX, ['null', 'array']);
        $resolver->setNormalizer(FormUiOptionKeys::COLLECTION_UX, function (Options $options, mixed $value): ?array {
            return $this->parser->parse($value);
        });
    }

    /**
     * @param array<string, mixed> $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $collectionUx = $options[FormUiOptionKeys::COLLECTION_UX];
        if (!\is_array($collectionUx) || !($collectionUx['enabled'] ?? false)) {
            return;
        }

        $count = $form->count();
        $allowAdd = (bool) ($options['allow_add'] ?? false);
        $allowDelete = (bool) ($options['allow_delete'] ?? false);
        $minValue = $collectionUx['min'];
        $maxValue = $collectionUx['max'];

        if ($collectionUx['enforce_min_max_on_vars']) {
            if ($maxValue !== null && $count >= $maxValue) {
                $allowAdd = false;
            }
            if ($minValue !== null && $count <= $minValue) {
                $allowDelete = false;
            }
        }

        $collectionName = $form->getName();
        $prototypeName = (string) ($options['prototype_name'] ?? '__name__');

        $view->vars = $this->stateMerger->merge($view->vars, [
            FormUiViewNamespace::COLLECTION => [
                'enabled' => true,
                'allow_add' => $allowAdd,
                'allow_delete' => $allowDelete,
                'count' => $count,
                'min' => $minValue,
                'max' => $maxValue,
                'empty_state' => $collectionUx['empty_state'],
                'prototype_name' => $prototypeName,
                'add_label' => $collectionUx['add_label'],
                'delete_label' => $collectionUx['delete_label'],
            ],
        ]);
    }

    /**
     * @param array<string, mixed> $options
     */
    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $collectionUx = $options[FormUiOptionKeys::COLLECTION_UX];
        if (!\is_array($collectionUx) || !($collectionUx['enabled'] ?? false)) {
            return;
        }

        $collectionName = $form->getName();
        $prototypeName = (string) ($options['prototype_name'] ?? '__name__');
        $allowDelete = $view->vars[FormUiViewNamespace::ROOT][FormUiViewNamespace::COLLECTION]['allow_delete'] ?? false;

        $rowIndex = 0;
        foreach ($view as $entryName => $entryView) {
            if ($entryName === $prototypeName) {
                $entryView->vars = $this->stateMerger->mergeSlice($entryView->vars, FormUiViewNamespace::COLLECTION, [
                    'row_index' => -1,
                    'row_id' => \sprintf('%s-prototype', $collectionName),
                    'is_prototype' => true,
                    'allow_row_delete' => false,
                ]);
                continue;
            }

            $entryView->vars = $this->stateMerger->mergeSlice($entryView->vars, FormUiViewNamespace::COLLECTION, [
                'row_index' => $rowIndex,
                'row_id' => \sprintf('%s-%d', $collectionName, $rowIndex),
                'is_prototype' => false,
                'allow_row_delete' => (bool) $allowDelete,
            ]);
            ++$rowIndex;
        }
    }
}
