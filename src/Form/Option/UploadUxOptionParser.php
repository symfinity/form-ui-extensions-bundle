<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Option;

final class UploadUxOptionParser
{
    /**
     * @return array{
     *     enabled: bool,
     *     max_size: ?int,
     *     accept: ?string,
     *     xhr_upload: bool
     * }|null
     */
    public function parse(mixed $raw): ?array
    {
        if ($raw === null) {
            return null;
        }

        if (!\is_array($raw)) {
            throw new InvalidFormUiOptionException('ui_upload must be an array or null.');
        }

        if ($raw === []) {
            return ['enabled' => true, 'max_size' => null, 'accept' => null, 'xhr_upload' => false];
        }

        $maxSize = $raw['max_size'] ?? null;
        if ($maxSize !== null && (!\is_int($maxSize) || $maxSize <= 0)) {
            throw new InvalidFormUiOptionException('ui_upload.max_size must be a positive integer or null.');
        }

        $accept = $raw['accept'] ?? null;
        if ($accept !== null && !\is_string($accept)) {
            throw new InvalidFormUiOptionException('ui_upload.accept must be a string or null.');
        }

        $xhrUpload = $raw['xhr_upload'] ?? false;
        if (!\is_bool($xhrUpload)) {
            throw new InvalidFormUiOptionException('ui_upload.xhr_upload must be a boolean.');
        }

        return [
            'enabled' => true,
            'max_size' => $maxSize,
            'accept' => $accept,
            'xhr_upload' => $xhrUpload,
        ];
    }
}
