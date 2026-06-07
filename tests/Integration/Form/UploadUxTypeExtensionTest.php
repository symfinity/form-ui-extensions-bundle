<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Tests\Integration\Form;

use PHPUnit\Framework\TestCase;
use Symfinity\FormUiExtensionsBundle\Contract\FormUiOptionKeys;
use Symfinity\FormUiExtensionsBundle\Contract\FormUiViewNamespace;
use Symfinity\FormUiExtensionsBundle\Tests\Integration\Form\FormUiTestFormFactory;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

final class UploadUxTypeExtensionTest extends TestCase
{
    public function testItExposesUploadDefaultsWhenEnabled(): void
    {
        $factory = FormUiTestFormFactory::create();

        $form = $factory->createBuilder(FormType::class)
            ->add('document', FileType::class, [
                FormUiOptionKeys::UPLOAD_UX => ['max_size' => 1024, 'accept' => 'image/*'],
            ])
            ->getForm();

        $upload = $form->createView()['document']->vars[FormUiViewNamespace::ROOT][FormUiViewNamespace::UPLOAD];

        self::assertTrue($upload['enabled']);
        self::assertSame(0, $upload['progress']);
        self::assertSame('idle', $upload['status']);
        self::assertSame(1024, $upload['max_size']);
        self::assertSame('form-ui--upload', $upload['controller']);
    }
}
