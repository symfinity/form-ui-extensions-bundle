<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

final class FormThemeTwigExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private readonly string $wrapper,
        private readonly bool $liveDate,
        private readonly bool $liveTags,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getGlobals(): array
    {
        return [
            'symfinity_form_ui_theme_wrapper' => $this->wrapper,
            'symfinity_form_ui_theme_live_date' => $this->liveDate,
            'symfinity_form_ui_theme_live_tags' => $this->liveTags,
            'symfinity_form_ui_live_date_available' => $this->liveDate && class_exists('Symfinity\\UxBlocksLive\\Twig\\Components\\DatePickerLive'),
            'symfinity_form_ui_live_tags_available' => $this->liveTags && class_exists('Symfinity\\UxBlocksLive\\Twig\\Components\\TagsInputLive'),
        ];
    }
}
