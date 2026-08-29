<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Option;

final class ButtonMetadataOptionParser
{
    /**
     * @param mixed $raw
     *
     * @return array{enabled: bool, intent: ?string, channel: ?string, tags: array<int, string>, extras: array<string, scalar|null>}
     */
    public function parse(mixed $raw): array
    {
        if ($raw === null) {
            return ['enabled' => false, 'intent' => null, 'channel' => null, 'tags' => [], 'extras' => []];
        }

        if (!\is_array($raw)) {
            throw new InvalidFormUiOptionException('ui_button_metadata must be an array or null.');
        }

        $allowed = ['intent', 'channel', 'tags', 'extras'];
        foreach (array_keys($raw) as $key) {
            if (!\in_array($key, $allowed, true)) {
                throw new InvalidFormUiOptionException(\sprintf('ui_button_metadata contains unsupported key "%s".', (string) $key));
            }
        }

        $intent = $raw['intent'] ?? null;
        $channel = $raw['channel'] ?? null;
        $tags = $raw['tags'] ?? [];
        $extras = $raw['extras'] ?? [];

        if ($intent !== null && !\is_string($intent)) {
            throw new InvalidFormUiOptionException('ui_button_metadata.intent must be a string or null.');
        }
        if ($channel !== null && !\is_string($channel)) {
            throw new InvalidFormUiOptionException('ui_button_metadata.channel must be a string or null.');
        }
        if (!\is_array($tags)) {
            throw new InvalidFormUiOptionException('ui_button_metadata.tags must be an array of strings.');
        }
        if (!\is_array($extras)) {
            throw new InvalidFormUiOptionException('ui_button_metadata.extras must be an associative array.');
        }

        $normalizedTags = [];
        foreach ($tags as $tag) {
            if (!\is_string($tag)) {
                throw new InvalidFormUiOptionException('ui_button_metadata.tags must only contain strings.');
            }

            $normalizedTags[] = $tag;
        }

        $reserved = ['enabled', 'intent', 'channel', 'tags', 'extras'];
        $normalizedExtras = [];
        foreach ($extras as $key => $value) {
            if (!\is_string($key)) {
                throw new InvalidFormUiOptionException('ui_button_metadata.extras keys must be strings.');
            }
            if (\in_array($key, $reserved, true)) {
                throw new InvalidFormUiOptionException(\sprintf('ui_button_metadata.extras cannot override reserved key "%s".', $key));
            }
            if (!\is_scalar($value) && $value !== null) {
                throw new InvalidFormUiOptionException('ui_button_metadata.extras values must be scalar or null.');
            }

            /** @var scalar|null $value */
            $normalizedExtras[$key] = $value;
        }

        return [
            'enabled' => true,
            'intent' => $intent,
            'channel' => $channel,
            'tags' => $normalizedTags,
            'extras' => $normalizedExtras,
        ];
    }
}
