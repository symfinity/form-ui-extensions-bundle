<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Extension;

use Symfinity\FormUiExtensionsBundle\Contract\FormUiOptionKeys;
use Symfinity\FormUiExtensionsBundle\Contract\FormUiViewNamespace;
use Symfinity\FormUiExtensionsBundle\Form\Option\FieldGroupOptionParser;
use Symfinity\FormUiExtensionsBundle\Form\Resolver\FormUiStateMerger;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class FieldGroupTypeExtension extends AbstractTypeExtension
{
    public function __construct(
        private readonly FieldGroupOptionParser $parser,
        private readonly FormUiStateMerger $stateMerger,
    ) {
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault(FormUiOptionKeys::FIELD_GROUP, null);
        $resolver->setAllowedTypes(FormUiOptionKeys::FIELD_GROUP, ['null', 'array']);
        $resolver->setNormalizer(FormUiOptionKeys::FIELD_GROUP, function (Options $options, mixed $value): ?array {
            return $this->parser->parse($value);
        });
    }

    /**
     * @param array<string, mixed> $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $fieldGroup = $options[FormUiOptionKeys::FIELD_GROUP];
        if (!\is_array($fieldGroup) || !($fieldGroup['enabled'] ?? false)) {
            return;
        }

        if ($form->count() === 0) {
            return;
        }

        $groupId = $fieldGroup['group_id'] ?? \sprintf('%s-group', $form->getName());
        $describedbyIds = $fieldGroup['describedby'];

        $invalid = $this->hasChildErrors($form);

        $view->vars = $this->stateMerger->merge($view->vars, [
            FormUiViewNamespace::FIELD_GROUP => [
                'enabled' => true,
                'group_id' => $groupId,
                'legend' => $fieldGroup['legend'],
                'describedby_ids' => $describedbyIds,
                'invalid' => $invalid,
            ],
        ]);
    }

    private function hasChildErrors(FormInterface $form): bool
    {
        foreach ($form as $child) {
            if (\count($child->getErrors(true)) > 0) {
                return true;
            }

            if ($child->count() > 0 && $this->hasChildErrors($child)) {
                return true;
            }
        }

        return false;
    }
}
