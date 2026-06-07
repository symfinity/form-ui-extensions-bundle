<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Extension;

use Symfinity\FormUiExtensionsBundle\Contract\FormUiOptionKeys;
use Symfinity\FormUiExtensionsBundle\Contract\FormUiViewNamespace;
use Symfinity\FormUiExtensionsBundle\Form\Option\UploadUxOptionParser;
use Symfinity\FormUiExtensionsBundle\Form\Resolver\FormUiStateMerger;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UploadUxTypeExtension extends AbstractTypeExtension
{
    private const CONTROLLER = 'form-ui--upload';

    public function __construct(
        private readonly UploadUxOptionParser $parser,
        private readonly FormUiStateMerger $stateMerger,
    ) {
    }

    public static function getExtendedTypes(): iterable
    {
        return [FileType::class];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault(FormUiOptionKeys::UPLOAD_UX, null);
        $resolver->setAllowedTypes(FormUiOptionKeys::UPLOAD_UX, ['null', 'array']);
        $resolver->setNormalizer(FormUiOptionKeys::UPLOAD_UX, function (Options $options, mixed $value): ?array {
            return $this->parser->parse($value);
        });
    }

    /**
     * @param array<string, mixed> $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $uploadUx = $options[FormUiOptionKeys::UPLOAD_UX];
        if (!\is_array($uploadUx) || !($uploadUx['enabled'] ?? false)) {
            return;
        }

        $error = null;
        if ($form->isSubmitted() && !$form->isValid()) {
            foreach ($form->getErrors() as $formError) {
                $error = $formError->getMessage();
                break;
            }
        }

        $view->vars = $this->stateMerger->merge($view->vars, [
            FormUiViewNamespace::UPLOAD => [
                'enabled' => true,
                'progress' => 0,
                'status' => $error !== null ? 'error' : 'idle',
                'error' => $error,
                'max_size' => $uploadUx['max_size'],
                'accept' => $uploadUx['accept'],
                'controller' => self::CONTROLLER,
                'xhr_upload' => $uploadUx['xhr_upload'],
            ],
        ]);
    }
}
