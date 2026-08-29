<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class DateRangeValidValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof DateRangeValid) {
            throw new UnexpectedTypeException($constraint, DateRangeValid::class);
        }

        if (!\is_object($value)) {
            return;
        }

        $startGetter = 'get'.ucfirst($constraint->startField);
        $endGetter = 'get'.ucfirst($constraint->endField);

        if (!method_exists($value, $startGetter) || !method_exists($value, $endGetter)) {
            return;
        }

        $start = $value->{$startGetter}();
        $end = $value->{$endGetter}();

        if ($start === null || $end === null) {
            return;
        }

        $startTs = $this->toTimestamp($start);
        $endTs = $this->toTimestamp($end);

        if ($startTs === null || $endTs === null) {
            return;
        }

        if ($endTs < $startTs) {
            $this->context->buildViolation($constraint->message)
                ->atPath($constraint->endField)
                ->addViolation();
        }
    }

    private function toTimestamp(mixed $value): ?int
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->getTimestamp();
        }

        if (\is_string($value) && $value !== '') {
            $ts = strtotime($value);

            return $ts !== false ? $ts : null;
        }

        return null;
    }
}
