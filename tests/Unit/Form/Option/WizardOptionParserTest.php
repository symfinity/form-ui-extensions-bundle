<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Tests\Unit\Form\Option;

use PHPUnit\Framework\TestCase;
use Symfinity\FormUiExtensionsBundle\Form\Option\InvalidFormUiOptionException;
use Symfinity\FormUiExtensionsBundle\Form\Option\WizardOptionParser;

final class WizardOptionParserTest extends TestCase
{
    public function testItRejectsDuplicateFieldAcrossSteps(): void
    {
        $parser = new WizardOptionParser();

        $this->expectException(InvalidFormUiOptionException::class);
        $this->expectExceptionMessage('email');

        $parser->parse([
            'steps' => [
                ['id' => 'a', 'label' => 'A', 'fields' => ['email']],
                ['id' => 'b', 'label' => 'B', 'fields' => ['email']],
            ],
        ]);
    }

    public function testItRejectsUnknownDuplicateStepId(): void
    {
        $parser = new WizardOptionParser();

        $this->expectException(InvalidFormUiOptionException::class);
        $this->expectExceptionMessage('duplicate step id');

        $parser->parse([
            'steps' => [
                ['id' => 'same', 'label' => 'A', 'fields' => ['a']],
                ['id' => 'same', 'label' => 'B', 'fields' => ['b']],
            ],
        ]);
    }

    public function testItParsesValidWizardConfig(): void
    {
        $parser = new WizardOptionParser();

        $parsed = $parser->parse([
            'steps' => [
                ['id' => 'account', 'label' => 'Account', 'fields' => ['email']],
                ['id' => 'profile', 'label' => 'Profile', 'fields' => ['name']],
            ],
            'linear' => false,
            'initial_step' => 2,
        ]);

        self::assertTrue($parsed['enabled']);
        self::assertSame(2, $parsed['initial_step']);
        self::assertFalse($parsed['linear']);
        self::assertCount(2, $parsed['steps']);
    }
}
