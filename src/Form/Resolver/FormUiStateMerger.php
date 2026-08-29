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

    /**
     * Deep-merge a slice under symfinity_form_ui (e.g. collection row vars).
     *
     * @param array<string, mixed> $existing
     * @param array<string, mixed> $slice
     *
     * @return array<string, mixed>
     */
    public function mergeSlice(array $existing, string $namespace, array $slice): array
    {
        $root = $existing[FormUiViewNamespace::ROOT] ?? [];
        if (!\is_array($root)) {
            $root = [];
        }

        $current = $root[$namespace] ?? [];
        if (!\is_array($current)) {
            $current = [];
        }

        $root[$namespace] = array_merge($current, $slice);
        $existing[FormUiViewNamespace::ROOT] = $root;

        return $existing;
    }
}
