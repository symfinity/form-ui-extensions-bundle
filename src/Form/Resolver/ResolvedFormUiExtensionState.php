<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Resolver;

final readonly class ResolvedFormUiExtensionState
{
    /**
     * @param array<string, mixed> $buttonMetadata
     * @param array<string, mixed> $uppercase
     */
    public function __construct(
        public array $buttonMetadata,
        public bool $novalidate,
        public string $novalidateStrategy,
        public array $uppercase,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toViewVars(): array
    {
        return [
            'button_metadata' => $this->buttonMetadata,
            'novalidate' => $this->novalidate,
            'novalidate_strategy' => $this->novalidateStrategy,
            'uppercase' => $this->uppercase,
        ];
    }
}
