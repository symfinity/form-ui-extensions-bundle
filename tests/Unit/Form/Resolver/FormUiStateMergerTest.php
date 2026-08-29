<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Tests\Unit\Form\Resolver;

use PHPUnit\Framework\TestCase;
use Symfinity\FormUiExtensionsBundle\Contract\FormUiViewNamespace;
use Symfinity\FormUiExtensionsBundle\Form\Resolver\FormUiStateMerger;

final class FormUiStateMergerTest extends TestCase
{
    public function testItPreservesR1VarsWhenMergingR2Slices(): void
    {
        $merger = new FormUiStateMerger();

        $existing = [
            FormUiViewNamespace::ROOT => [
                FormUiViewNamespace::NOVALIDATE => true,
                FormUiViewNamespace::BUTTON_METADATA => ['enabled' => false],
            ],
        ];

        $merged = $merger->merge($existing, [
            FormUiViewNamespace::WIZARD => ['enabled' => true, 'step_count' => 2],
        ]);

        $root = $merged[FormUiViewNamespace::ROOT];
        self::assertTrue($root[FormUiViewNamespace::NOVALIDATE]);
        self::assertSame(['enabled' => false], $root[FormUiViewNamespace::BUTTON_METADATA]);
        self::assertTrue($root[FormUiViewNamespace::WIZARD]['enabled']);
    }

    public function testMergeSliceDeepMergesCollectionRowVars(): void
    {
        $merger = new FormUiStateMerger();

        $existing = [
            FormUiViewNamespace::ROOT => [
                FormUiViewNamespace::COLLECTION => ['enabled' => true, 'count' => 1],
            ],
        ];

        $merged = $merger->mergeSlice($existing, FormUiViewNamespace::COLLECTION, [
            'row_index' => 0,
            'row_id' => 'items-0',
        ]);

        $collection = $merged[FormUiViewNamespace::ROOT][FormUiViewNamespace::COLLECTION];
        self::assertTrue($collection['enabled']);
        self::assertSame(1, $collection['count']);
        self::assertSame(0, $collection['row_index']);
        self::assertSame('items-0', $collection['row_id']);
    }
}
