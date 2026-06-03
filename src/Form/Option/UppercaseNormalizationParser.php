<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Option;

final class UppercaseNormalizationParser
{
    /**
     * @param mixed $raw
     *
     * @return array{enabled: bool, mode: ?string, trim_before: bool}
     */
    public function parse(mixed $raw): array
    {
        if (\is_bool($raw)) {
            return ['enabled' => $raw, 'mode' => $raw ? 'strict_ascii' : null, 'trim_before' => false];
        }

        if (!\is_array($raw)) {
            throw new InvalidFormUiOptionException('ui_uppercase_normalization must be bool or array.');
        }

        $enabled = $raw['enabled'] ?? true;
        $mode = $raw['mode'] ?? 'strict_ascii';
        $trimBefore = $raw['trim_before'] ?? false;

        if (!\is_bool($enabled)) {
            throw new InvalidFormUiOptionException('ui_uppercase_normalization.enabled must be bool.');
        }
        if (!\is_string($mode)) {
            throw new InvalidFormUiOptionException('ui_uppercase_normalization.mode must be string.');
        }
        if (!\in_array($mode, ['strict_ascii', 'mb_upper'], true)) {
            throw new InvalidFormUiOptionException(\sprintf('ui_uppercase_normalization.mode "%s" is not supported.', $mode));
        }
        if (!\is_bool($trimBefore)) {
            throw new InvalidFormUiOptionException('ui_uppercase_normalization.trim_before must be bool.');
        }

        return [
            'enabled' => $enabled,
            'mode' => $enabled ? $mode : null,
            'trim_before' => $trimBefore,
        ];
    }
}
