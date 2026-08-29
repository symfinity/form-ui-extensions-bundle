<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
final class DateRangeValid extends Constraint
{
    public string $message = 'End date must be on or after start date.';

    public function __construct(
        public string $startField = 'start',
        public string $endField = 'end',
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct($options, $groups, $payload);
    }
}
