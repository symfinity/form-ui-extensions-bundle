<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Form\Extension;

use Symfinity\FormUiExtensionsBundle\Contract\FormUiOptionKeys;
use Symfinity\FormUiExtensionsBundle\Contract\FormUiViewNamespace;
use Symfinity\FormUiExtensionsBundle\Form\Option\DateRangeOptionParser;
use Symfinity\FormUiExtensionsBundle\Form\Option\InvalidFormUiOptionException;
use Symfinity\FormUiExtensionsBundle\Form\Resolver\FormUiStateMerger;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DateRangeTypeExtension extends AbstractTypeExtension
{
    public function __construct(
        private readonly DateRangeOptionParser $parser,
        private readonly FormUiStateMerger $stateMerger,
    ) {
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault(FormUiOptionKeys::DATE_RANGE, null);
        $resolver->setAllowedTypes(FormUiOptionKeys::DATE_RANGE, ['null', 'array']);
        $resolver->setNormalizer(FormUiOptionKeys::DATE_RANGE, function (Options $options, mixed $value): ?array {
            return $this->parser->parse($value);
        });
    }

    /**
     * @param array<string, mixed> $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $dateRange = $options[FormUiOptionKeys::DATE_RANGE];
        if (!\is_array($dateRange) || !($dateRange['enabled'] ?? false)) {
            return;
        }

        $startName = $dateRange['start'];
        $endName = $dateRange['end'];

        $this->assertChildField($form, $startName);
        $this->assertChildField($form, $endName);

        $rangeError = false;
        $rangeErrorMessage = null;

        if ($form->isSubmitted()) {
            $startData = $form->has($startName) ? $form->get($startName)->getData() : null;
            $endData = $form->has($endName) ? $form->get($endName)->getData() : null;

            if ($startData !== null && $endData !== null) {
                $startTs = $this->toTimestamp($startData);
                $endTs = $this->toTimestamp($endData);
                if ($startTs !== null && $endTs !== null && $endTs < $startTs) {
                    $rangeError = true;
                    $rangeErrorMessage = 'End date must be on or after start date.';
                }
            }
        }

        $view->vars = $this->stateMerger->merge($view->vars, [
            FormUiViewNamespace::DATE_RANGE => [
                'enabled' => true,
                'start_name' => $startName,
                'end_name' => $endName,
                'preset' => $dateRange['preset'],
                'range_error' => $rangeError,
                'range_error_message' => $rangeErrorMessage,
            ],
        ]);
    }

    /**
     * @param array<string, mixed> $options
     */
    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $dateRange = $options[FormUiOptionKeys::DATE_RANGE];
        if (!\is_array($dateRange) || !($dateRange['enabled'] ?? false)) {
            return;
        }

        $this->markPairedField($view, $dateRange['start'], 'start', $dateRange['end']);
        $this->markPairedField($view, $dateRange['end'], 'end', $dateRange['start']);
    }

    private function assertChildField(FormInterface $form, string $name): void
    {
        if (!$form->has($name)) {
            throw new InvalidFormUiOptionException(\sprintf('ui_date_range references unknown field "%s".', $name));
        }

        $child = $form->get($name);
        $type = $child->getConfig()->getType()->getInnerType();
        $allowed = [DateType::class, DateTimeType::class, TextType::class];
        $allowed = array_map(static fn (string $class): string => $class, $allowed);

        if (!\in_array($type::class, $allowed, true)) {
            throw new InvalidFormUiOptionException(\sprintf('ui_date_range field "%s" must be DateType, DateTimeType, or TextType.', $name));
        }
    }

    private function markPairedField(FormView $view, string $fieldName, string $role, string $partnerName): void
    {
        if (!isset($view[$fieldName])) {
            return;
        }

        $fieldView = $view[$fieldName];
        \assert($fieldView instanceof FormView);

        $fieldView->vars = $this->stateMerger->mergeSlice($fieldView->vars, FormUiViewNamespace::DATE_RANGE, [
            'role' => $role,
            'partner_name' => $partnerName,
        ]);
    }

    private function toTimestamp(mixed $value): ?int
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->getTimestamp();
        }

        if (\is_string($value) && $value !== '') {
            $ts = strtotime($value);

            return $ts !== false ? $ts : null;
        }

        return null;
    }
}
