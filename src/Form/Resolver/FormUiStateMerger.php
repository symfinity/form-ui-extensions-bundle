<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Resolver;

use Symfinity\FormUiExtensionsBundle\Contract\FormUiViewNamespace;

final class FormUiStateMerger
{
    /**
     * @param array<string, mixed> $existing
     * @param array<string, mixed> $newState
     *
     * @return array<string, mixed>
     */
    public function merge(array $existing, array $newState): array
    {
        $root = $existing[FormUiViewNamespace::ROOT] ?? [];
        if (!\is_array($root)) {
            $root = [];
        }

        foreach ($newState as $key => $value) {
            $root[$key] = $value;
        }

        $existing[FormUiViewNamespace::ROOT] = $root;

        return $existing;
    }
}
