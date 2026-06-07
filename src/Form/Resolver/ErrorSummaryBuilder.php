<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Resolver;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

final class ErrorSummaryBuilder
{
    /**
     * @param array{enabled: bool, link_to_fields: bool, global_target_id: string} $options
     *
     * @return array{
     *     has_errors: bool,
     *     error_count: int,
     *     summary: list<array{name: string, label: string, message: string, target_id: string}>,
     *     global: list<array{message: string, target_id: string}>
     * }
     */
    public function build(FormInterface $form, FormView $view, array $options): array
    {
        $summary = [];
        $this->collectFieldErrors($form, $view, $form->getName(), $options, $summary);

        $global = [];
        foreach ($form->getErrors() as $error) {
            $global[] = [
                'message' => $error->getMessage(),
                'target_id' => $options['global_target_id'],
            ];
        }

        $errorCount = \count($summary);

        return [
            'has_errors' => $errorCount > 0 || $global !== [],
            'error_count' => $errorCount,
            'summary' => $summary,
            'global' => $global,
        ];
    }

    /**
     * @param list<array{name: string, label: string, message: string, target_id: string}> $summary
     * @param array{link_to_fields: bool} $options
     */
    private function collectFieldErrors(
        FormInterface $form,
        FormView $view,
        string $formName,
        array $options,
        array &$summary,
    ): void {
        foreach ($form as $name => $child) {
            if (!isset($view[$name])) {
                continue;
            }

            $childView = $view[$name];
            \assert($childView instanceof FormView);

            if ($child->count() > 0) {
                $this->collectFieldErrors($child, $childView, $this->qualifiedName($formName, (string) $name), $options, $summary);
            }

            foreach ($child->getErrors() as $error) {
                if (!$error instanceof FormError) {
                    continue;
                }

                $fieldName = (string) $name;
                $targetId = $options['link_to_fields']
                    ? $this->targetIdForField($formName, $fieldName)
                    : '';

                $summary[] = [
                    'name' => $this->qualifiedName($formName, $fieldName),
                    'label' => $this->labelForField($childView, $fieldName),
                    'message' => $error->getMessage(),
                    'target_id' => $targetId,
                ];
            }
        }
    }

    private function qualifiedName(string $formName, string $fieldName): string
    {
        return $formName !== '' ? $formName.'_'.$fieldName : $fieldName;
    }

    private function targetIdForField(string $formName, string $fieldName): string
    {
        return $this->qualifiedName($formName, $fieldName);
    }

    private function labelForField(FormView $view, string $fallback): string
    {
        $label = $view->vars['label'] ?? null;

        return \is_string($label) && $label !== '' ? $label : $fallback;
    }
}
