<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Option;

final class FieldGroupOptionParser
{
    /**
     * @return array{
     *     enabled: bool,
     *     legend: ?string,
     *     describedby: list<string>,
     *     group_id: ?string
     * }|null
     */
    public function parse(mixed $raw): ?array
    {
        if ($raw === null) {
            return null;
        }

        if (!\is_array($raw)) {
            throw new InvalidFormUiOptionException('ui_field_group must be an array or null.');
        }

        if ($raw === []) {
            return ['enabled' => true, 'legend' => null, 'describedby' => [], 'group_id' => null];
        }

        $legend = $raw['legend'] ?? null;
        if ($legend !== null && !\is_string($legend)) {
            throw new InvalidFormUiOptionException('ui_field_group.legend must be a string or null.');
        }

        $describedby = $raw['describedby'] ?? [];
        if (!\is_array($describedby)) {
            throw new InvalidFormUiOptionException('ui_field_group.describedby must be an array of strings.');
        }

        $normalizedDescribedby = [];
        foreach ($describedby as $index => $id) {
            if (!\is_string($id) || $id === '') {
                throw new InvalidFormUiOptionException(\sprintf('ui_field_group.describedby[%d] must be a non-empty string.', $index));
            }
            $normalizedDescribedby[] = $id;
        }

        $groupId = $raw['group_id'] ?? null;
        if ($groupId !== null && !\is_string($groupId)) {
            throw new InvalidFormUiOptionException('ui_field_group.group_id must be a string or null.');
        }

        return [
            'enabled' => true,
            'legend' => $legend,
            'describedby' => $normalizedDescribedby,
            'group_id' => $groupId,
        ];
    }
}
