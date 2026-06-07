<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Tests\Integration\Form;

use PHPUnit\Framework\TestCase;
use Symfinity\FormUiExtensionsBundle\Contract\FormUiOptionKeys;
use Symfinity\FormUiExtensionsBundle\Contract\FormUiViewNamespace;
use Symfinity\FormUiExtensionsBundle\Tests\Integration\Form\FormUiTestFormFactory;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

final class DateRangeTypeExtensionTest extends TestCase
{
    public function testItExposesPairingVarsOnParentAndChildren(): void
    {
        $factory = FormUiTestFormFactory::create();

        $form = $factory->createBuilder(FormType::class, null, [
            FormUiOptionKeys::DATE_RANGE => ['start' => 'from', 'end' => 'to', 'preset' => 'last_7_days'],
        ])
            ->add('from', DateType::class, ['widget' => 'single_text'])
            ->add('to', DateType::class, ['widget' => 'single_text'])
            ->getForm();

        $view = $form->createView();
        $parent = $view->vars[FormUiViewNamespace::ROOT][FormUiViewNamespace::DATE_RANGE];

        self::assertTrue($parent['enabled']);
        self::assertSame('from', $parent['start_name']);
        self::assertSame('to', $parent['end_name']);
        self::assertSame('last_7_days', $parent['preset']);

        $start = $view['from']->vars[FormUiViewNamespace::ROOT][FormUiViewNamespace::DATE_RANGE];
        $end = $view['to']->vars[FormUiViewNamespace::ROOT][FormUiViewNamespace::DATE_RANGE];

        self::assertSame('start', $start['role']);
        self::assertSame('to', $start['partner_name']);
        self::assertSame('end', $end['role']);
        self::assertSame('from', $end['partner_name']);
    }

    public function testInvalidRangeSetsRangeError(): void
    {
        $factory = FormUiTestFormFactory::create();

        $form = $factory->createBuilder(FormType::class, null, [
            FormUiOptionKeys::DATE_RANGE => ['start' => 'from', 'end' => 'to'],
        ])
            ->add('from', DateType::class, ['widget' => 'single_text'])
            ->add('to', DateType::class, ['widget' => 'single_text'])
            ->getForm();

        $form->submit([
            'from' => '2026-06-10',
            'to' => '2026-06-01',
        ]);

        $range = $form->createView()->vars[FormUiViewNamespace::ROOT][FormUiViewNamespace::DATE_RANGE];
        self::assertTrue($range['range_error']);
        self::assertNotEmpty($range['range_error_message']);
    }
}
