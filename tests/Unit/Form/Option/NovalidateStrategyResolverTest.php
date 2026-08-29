<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Tests\Unit\Form\Option;

use PHPUnit\Framework\TestCase;
use Symfinity\FormUiExtensionsBundle\Form\Option\InvalidFormUiOptionException;
use Symfinity\FormUiExtensionsBundle\Form\Option\NovalidateStrategyResolver;

final class NovalidateStrategyResolverTest extends TestCase
{
    public function testItResolvesKnownStrategies(): void
    {
        $resolver = new NovalidateStrategyResolver();

        self::assertFalse($resolver->resolve(NovalidateStrategyResolver::INHERIT));
        self::assertTrue($resolver->resolve(NovalidateStrategyResolver::FORCE_ON));
        self::assertFalse($resolver->resolve(NovalidateStrategyResolver::FORCE_OFF));
    }

    public function testItRejectsUnknownStrategies(): void
    {
        $resolver = new NovalidateStrategyResolver();

        $this->expectException(InvalidFormUiOptionException::class);
        $resolver->resolve('invalid');
    }
}
