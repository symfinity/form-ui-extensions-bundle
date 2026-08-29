<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Option;

final class ErrorSummaryOptionParser
{
    /**
     * @return array{
     *     enabled: bool,
     *     link_to_fields: bool,
     *     global_target_id: string
     * }|null
     */
    public function parse(mixed $raw): ?array
    {
        if ($raw === null) {
            return null;
        }

        if (!\is_array($raw)) {
            throw new InvalidFormUiOptionException('ui_error_summary must be an array or null.');
        }

        $enabled = $raw['enabled'] ?? true;
        if (!\is_bool($enabled)) {
            throw new InvalidFormUiOptionException('ui_error_summary.enabled must be a boolean.');
        }

        $linkToFields = $raw['link_to_fields'] ?? true;
        if (!\is_bool($linkToFields)) {
            throw new InvalidFormUiOptionException('ui_error_summary.link_to_fields must be a boolean.');
        }

        $globalTargetId = $raw['global_target_id'] ?? 'form-errors-global';
        if (!\is_string($globalTargetId) || $globalTargetId === '') {
            throw new InvalidFormUiOptionException('ui_error_summary.global_target_id must be a non-empty string.');
        }

        if (!$enabled) {
            return null;
        }

        return [
            'enabled' => true,
            'link_to_fields' => $linkToFields,
            'global_target_id' => $globalTargetId,
        ];
    }
}
