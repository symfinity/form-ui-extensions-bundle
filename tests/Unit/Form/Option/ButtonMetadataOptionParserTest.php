<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Tests\Unit\Form\Option;

use PHPUnit\Framework\TestCase;
use Symfinity\FormUiExtensionsBundle\Form\Option\ButtonMetadataOptionParser;
use Symfinity\FormUiExtensionsBundle\Form\Option\InvalidFormUiOptionException;

final class ButtonMetadataOptionParserTest extends TestCase
{
    public function testItNormalizesValidMetadata(): void
    {
        $parser = new ButtonMetadataOptionParser();

        $result = $parser->parse([
            'intent' => 'primary',
            'channel' => 'checkout',
            'tags' => ['checkout', 'confirm'],
            'extras' => ['ab_test' => 'A', 'priority' => 1],
        ]);

        self::assertTrue($result['enabled']);
        self::assertSame('primary', $result['intent']);
        self::assertSame('checkout', $result['channel']);
        self::assertSame(['checkout', 'confirm'], $result['tags']);
        self::assertSame(['ab_test' => 'A', 'priority' => 1], $result['extras']);
    }

    public function testItRejectsUnknownTopLevelKeys(): void
    {
        $parser = new ButtonMetadataOptionParser();

        $this->expectException(InvalidFormUiOptionException::class);
        $parser->parse(['unsupported' => true]);
    }
}
