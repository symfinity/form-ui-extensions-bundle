<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Option;

final class CollectionUxOptionParser
{
    /**
     * @return array{
     *     enabled: bool,
     *     empty_state: ?string,
     *     add_label: ?string,
     *     delete_label: ?string,
     *     enforce_min_max_on_vars: bool,
     *     min: ?int,
     *     max: ?int
     * }|null
     */
    public function parse(mixed $raw): ?array
    {
        if ($raw === null) {
            return null;
        }

        if (!\is_array($raw)) {
            throw new InvalidFormUiOptionException('ui_collection must be an array or null.');
        }

        if ($raw === []) {
            return ['enabled' => true, 'empty_state' => null, 'add_label' => null, 'delete_label' => null, 'enforce_min_max_on_vars' => true, 'min' => null, 'max' => null];
        }

        $emptyState = $raw['empty_state'] ?? null;
        if ($emptyState !== null && !\is_string($emptyState)) {
            throw new InvalidFormUiOptionException('ui_collection.empty_state must be a string or null.');
        }

        $addLabel = $raw['add_label'] ?? null;
        if ($addLabel !== null && !\is_string($addLabel)) {
            throw new InvalidFormUiOptionException('ui_collection.add_label must be a string or null.');
        }

        $deleteLabel = $raw['delete_label'] ?? null;
        if ($deleteLabel !== null && !\is_string($deleteLabel)) {
            throw new InvalidFormUiOptionException('ui_collection.delete_label must be a string or null.');
        }

        $enforceMinMax = $raw['enforce_min_max_on_vars'] ?? true;
        if (!\is_bool($enforceMinMax)) {
            throw new InvalidFormUiOptionException('ui_collection.enforce_min_max_on_vars must be a boolean.');
        }

        $min = $raw['min'] ?? null;
        if ($min !== null && (!\is_int($min) || $min < 0)) {
            throw new InvalidFormUiOptionException('ui_collection.min must be a non-negative integer or null.');
        }

        $max = $raw['max'] ?? null;
        if ($max !== null && (!\is_int($max) || $max < 0)) {
            throw new InvalidFormUiOptionException('ui_collection.max must be a non-negative integer or null.');
        }

        return [
            'enabled' => true,
            'empty_state' => $emptyState,
            'add_label' => $addLabel,
            'delete_label' => $deleteLabel,
            'enforce_min_max_on_vars' => $enforceMinMax,
            'min' => $min,
            'max' => $max,
        ];
    }
}
