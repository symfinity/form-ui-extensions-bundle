<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Tests\Unit\Form\Transformer;

use PHPUnit\Framework\TestCase;
use Symfinity\FormUiExtensionsBundle\Form\Transformer\UppercaseTransformer;

final class UppercaseTransformerTest extends TestCase
{
    public function testItUppercasesStringInput(): void
    {
        $transformer = new UppercaseTransformer('strict_ascii');

        self::assertSame('HELLO', $transformer->reverseTransform('hello'));
    }

    public function testItKeepsUnsupportedTypesUntouched(): void
    {
        $transformer = new UppercaseTransformer();

        self::assertSame(42, $transformer->reverseTransform(42));
    }
}
