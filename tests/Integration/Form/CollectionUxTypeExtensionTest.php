<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Tests\Integration\Form;

use PHPUnit\Framework\TestCase;
use Symfinity\FormUiExtensionsBundle\Contract\FormUiOptionKeys;
use Symfinity\FormUiExtensionsBundle\Contract\FormUiViewNamespace;
use Symfinity\FormUiExtensionsBundle\Tests\Integration\Form\FormUiTestFormFactory;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class CollectionUxTypeExtensionTest extends TestCase
{
    public function testItEnforcesMinMaxOnCollectionVars(): void
    {
        $factory = FormUiTestFormFactory::create();

        $form = $factory->createBuilder(FormType::class)
            ->add('items', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'data' => ['a', 'b', 'c'],
                FormUiOptionKeys::COLLECTION_UX => ['min' => 1, 'max' => 3],
            ])
            ->getForm();

        $collection = $form->createView()['items']->vars[FormUiViewNamespace::ROOT][FormUiViewNamespace::COLLECTION];

        self::assertTrue($collection['enabled']);
        self::assertFalse($collection['allow_add']);
        self::assertTrue($collection['allow_delete']);
        self::assertSame(3, $collection['count']);
    }

    public function testItBlocksDeleteAtMinCount(): void
    {
        $factory = FormUiTestFormFactory::create();

        $form = $factory->createBuilder(FormType::class)
            ->add('items', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'data' => ['only'],
                FormUiOptionKeys::COLLECTION_UX => ['min' => 1, 'max' => 5],
            ])
            ->getForm();

        $collection = $form->createView()['items']->vars[FormUiViewNamespace::ROOT][FormUiViewNamespace::COLLECTION];
        self::assertFalse($collection['allow_delete']);
        self::assertTrue($collection['allow_add']);
    }

    public function testEmptyCollectionExposesEmptyState(): void
    {
        $factory = FormUiTestFormFactory::create();

        $form = $factory->createBuilder(FormType::class)
            ->add('items', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                FormUiOptionKeys::COLLECTION_UX => ['empty_state' => 'No items yet'],
            ])
            ->getForm();

        $collection = $form->createView()['items']->vars[FormUiViewNamespace::ROOT][FormUiViewNamespace::COLLECTION];
        self::assertSame('No items yet', $collection['empty_state']);
        self::assertSame(0, $collection['count']);
    }

    public function testEntryRowsExposeStableIds(): void
    {
        $factory = FormUiTestFormFactory::create();

        $form = $factory->createBuilder(FormType::class)
            ->add('items', CollectionType::class, [
                'entry_type' => TextType::class,
                'data' => ['a', 'b'],
                FormUiOptionKeys::COLLECTION_UX => [],
            ])
            ->getForm();

        $itemsView = $form->createView()['items'];
        $row0 = $itemsView[0]->vars[FormUiViewNamespace::ROOT][FormUiViewNamespace::COLLECTION];
        $row1 = $itemsView[1]->vars[FormUiViewNamespace::ROOT][FormUiViewNamespace::COLLECTION];

        self::assertSame(0, $row0['row_index']);
        self::assertSame('items-0', $row0['row_id']);
        self::assertSame(1, $row1['row_index']);
        self::assertSame('items-1', $row1['row_id']);
    }
}
