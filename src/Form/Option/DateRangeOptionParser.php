<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Option;

final class DateRangeOptionParser
{
    /**
     * @return array{
     *     enabled: bool,
     *     start: string,
     *     end: string,
     *     preset: ?string
     * }|null
     */
    public function parse(mixed $raw): ?array
    {
        if ($raw === null) {
            return null;
        }

        if (!\is_array($raw)) {
            throw new InvalidFormUiOptionException('ui_date_range must be an array or null.');
        }

        $start = $raw['start'] ?? null;
        $end = $raw['end'] ?? null;

        if (!\is_string($start) || $start === '') {
            throw new InvalidFormUiOptionException('ui_date_range.start must be a non-empty string.');
        }
        if (!\is_string($end) || $end === '') {
            throw new InvalidFormUiOptionException('ui_date_range.end must be a non-empty string.');
        }
        if ($start === $end) {
            throw new InvalidFormUiOptionException('ui_date_range.start and end must be different field names.');
        }

        $preset = $raw['preset'] ?? null;
        if ($preset !== null && !\is_string($preset)) {
            throw new InvalidFormUiOptionException('ui_date_range.preset must be a string or null.');
        }

        return [
            'enabled' => true,
            'start' => $start,
            'end' => $end,
            'preset' => $preset,
        ];
    }
}
