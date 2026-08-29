<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Tests\Unit\Form\Option;

use PHPUnit\Framework\TestCase;
use Symfinity\FormUiExtensionsBundle\Form\Option\InvalidFormUiOptionException;
use Symfinity\FormUiExtensionsBundle\Form\Option\UppercaseNormalizationParser;

final class UppercaseNormalizationParserTest extends TestCase
{
    public function testItSupportsBooleanShorthand(): void
    {
        $parser = new UppercaseNormalizationParser();

        self::assertSame(
            ['enabled' => true, 'mode' => 'strict_ascii', 'trim_before' => false],
            $parser->parse(true),
        );
        self::assertSame(
            ['enabled' => false, 'mode' => null, 'trim_before' => false],
            $parser->parse(false),
        );
    }

    public function testItRejectsInvalidMode(): void
    {
        $parser = new UppercaseNormalizationParser();

        $this->expectException(InvalidFormUiOptionException::class);
        $parser->parse(['enabled' => true, 'mode' => 'locale_magic']);
    }
}
