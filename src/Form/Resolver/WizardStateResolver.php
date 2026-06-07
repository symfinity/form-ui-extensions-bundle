<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Resolver;

use Symfinity\FormUiExtensionsBundle\Form\Option\InvalidFormUiOptionException;
use Symfony\Component\Form\FormInterface;

final class WizardStateResolver
{
    /**
     * @param array{
     *     enabled: bool,
     *     steps: list<array{id: string, label: string, description: ?string, field_names: list<string>}>,
     *     linear: bool,
     *     initial_step: int
     * } $wizard
     *
     * @return array<string, mixed>
     */
    public function resolveRootState(array $wizard, FormInterface $form): array
    {
        $this->assertFieldNamesExist($wizard, $form);

        $fieldStepMap = [];
        foreach ($wizard['steps'] as $stepIndex => $step) {
            $stepNumber = $stepIndex + 1;
            foreach ($step['field_names'] as $fieldName) {
                $fieldStepMap[$fieldName] = $stepNumber;
            }
        }

        $steps = [];
        foreach ($wizard['steps'] as $step) {
            $steps[] = [
                'id' => $step['id'],
                'label' => $step['label'],
                'description' => $step['description'],
                'field_names' => $step['field_names'],
            ];
        }

        return [
            'enabled' => true,
            'steps' => $steps,
            'step_count' => \count($steps),
            'field_step_map' => $fieldStepMap,
            'invalid_steps' => $this->resolveInvalidSteps($wizard, $form),
            'linear' => $wizard['linear'],
            'initial_step' => $wizard['initial_step'],
        ];
    }

    /**
     * @param array{
     *     steps: list<array{field_names: list<string>}>
     * } $wizard
     *
     * @return list<int>
     */
    public function resolveInvalidSteps(array $wizard, FormInterface $form): array
    {
        if (!$form->isSubmitted()) {
            return [];
        }

        $invalidSteps = [];
        foreach ($wizard['steps'] as $stepIndex => $step) {
            $stepNumber = $stepIndex + 1;
            foreach ($step['field_names'] as $fieldName) {
                if (!$form->has($fieldName)) {
                    continue;
                }

                $field = $form->get($fieldName);
                if (!$field->isValid() || \count($field->getErrors(true)) > 0) {
                    $invalidSteps[$stepNumber] = $stepNumber;
                    break;
                }
            }
        }

        return array_values($invalidSteps);
    }

    /**
     * @param array{
     *     steps: list<array{field_names: list<string>}>
     * } $wizard
     */
    private function assertFieldNamesExist(array $wizard, FormInterface $form): void
    {
        foreach ($wizard['steps'] as $step) {
            foreach ($step['field_names'] as $fieldName) {
                if (!$form->has($fieldName)) {
                    throw new InvalidFormUiOptionException(\sprintf('ui_wizard references unknown field "%s".', $fieldName));
                }
            }
        }
    }
}
