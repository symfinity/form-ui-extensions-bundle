<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * @implements DataTransformerInterface<mixed, mixed>
 */
final readonly class UppercaseTransformer implements DataTransformerInterface
{
    public function __construct(
        private string $mode = 'strict_ascii',
        private bool $trimBefore = false,
    ) {
    }

    public function transform(mixed $value): mixed
    {
        return $value;
    }

    public function reverseTransform(mixed $value): mixed
    {
        if (!\is_string($value)) {
            return $value;
        }

        $normalized = $this->trimBefore ? \trim($value) : $value;

        if ($normalized === '') {
            return $normalized;
        }

        if ($this->mode === 'mb_upper' && \function_exists('mb_strtoupper')) {
            return \mb_strtoupper($normalized);
        }

        return \strtoupper($normalized);
    }
}
