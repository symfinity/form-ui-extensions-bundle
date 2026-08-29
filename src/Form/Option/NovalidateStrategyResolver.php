<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Option;

final class NovalidateStrategyResolver
{
    public const INHERIT = 'inherit';
    public const FORCE_ON = 'force_on';
    public const FORCE_OFF = 'force_off';

    public function resolve(string $strategy): bool
    {
        return match ($strategy) {
            self::FORCE_ON => true,
            self::FORCE_OFF => false,
            self::INHERIT => false,
            default => throw new InvalidFormUiOptionException(\sprintf('Unsupported novalidate strategy "%s".', $strategy)),
        };
    }
}
