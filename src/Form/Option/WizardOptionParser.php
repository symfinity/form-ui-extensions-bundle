<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Option;

final class WizardOptionParser
{
    /**
     * @return array{
     *     enabled: bool,
     *     steps: list<array{id: string, label: string, description: ?string, field_names: list<string>}>,
     *     linear: bool,
     *     initial_step: int
     * }|null
     */
    public function parse(mixed $raw): ?array
    {
        if ($raw === null) {
            return null;
        }

        if (!\is_array($raw)) {
            throw new InvalidFormUiOptionException('ui_wizard must be an array or null.');
        }

        if ($raw === []) {
            return null;
        }

        $steps = $raw['steps'] ?? null;
        if (!\is_array($steps) || $steps === []) {
            throw new InvalidFormUiOptionException('ui_wizard.steps must be a non-empty array.');
        }

        $linear = $raw['linear'] ?? true;
        if (!\is_bool($linear)) {
            throw new InvalidFormUiOptionException('ui_wizard.linear must be a boolean.');
        }

        $initialStep = $raw['initial_step'] ?? 1;
        if (!\is_int($initialStep) || $initialStep < 1) {
            throw new InvalidFormUiOptionException('ui_wizard.initial_step must be a positive integer.');
        }

        $normalizedSteps = [];
        $seenIds = [];
        $seenFields = [];

        foreach ($steps as $index => $step) {
            if (!\is_array($step)) {
                throw new InvalidFormUiOptionException(\sprintf('ui_wizard.steps[%d] must be an array.', $index));
            }

            $id = $step['id'] ?? null;
            $label = $step['label'] ?? null;
            $fields = $step['fields'] ?? null;

            if (!\is_string($id) || $id === '') {
                throw new InvalidFormUiOptionException(\sprintf('ui_wizard.steps[%d].id must be a non-empty string.', $index));
            }
            if (isset($seenIds[$id])) {
                throw new InvalidFormUiOptionException(\sprintf('ui_wizard contains duplicate step id "%s".', $id));
            }
            $seenIds[$id] = true;

            if (!\is_string($label) || $label === '') {
                throw new InvalidFormUiOptionException(\sprintf('ui_wizard.steps[%d].label must be a non-empty string.', $index));
            }

            if (!\is_array($fields) || $fields === []) {
                throw new InvalidFormUiOptionException(\sprintf('ui_wizard.steps[%d].fields must be a non-empty array.', $index));
            }

            $fieldNames = [];
            foreach ($fields as $fieldIndex => $fieldName) {
                if (!\is_string($fieldName) || $fieldName === '') {
                    throw new InvalidFormUiOptionException(\sprintf('ui_wizard.steps[%d].fields[%d] must be a non-empty string.', $index, $fieldIndex));
                }
                if (isset($seenFields[$fieldName])) {
                    throw new InvalidFormUiOptionException(\sprintf('ui_wizard field "%s" appears in more than one step.', $fieldName));
                }
                $seenFields[$fieldName] = true;
                $fieldNames[] = $fieldName;
            }

            $description = $step['description'] ?? null;
            if ($description !== null && !\is_string($description)) {
                throw new InvalidFormUiOptionException(\sprintf('ui_wizard.steps[%d].description must be a string or null.', $index));
            }

            $normalizedSteps[] = [
                'id' => $id,
                'label' => $label,
                'description' => $description,
                'field_names' => $fieldNames,
            ];
        }

        if ($initialStep > \count($normalizedSteps)) {
            throw new InvalidFormUiOptionException('ui_wizard.initial_step exceeds step count.');
        }

        return [
            'enabled' => true,
            'steps' => $normalizedSteps,
            'linear' => $linear,
            'initial_step' => $initialStep,
        ];
    }
}
