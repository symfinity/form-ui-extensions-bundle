<?php

declare(strict_types=1);

namespace Symfinity\FormUiExtensionsBundle\Tests\Integration;

use Symfinity\FormUiExtensionsBundle\FormUiExtensionsBundle;
use Symfinity\UiKernel\UiKernelBundle;
use Symfinity\UxBlocksCore\SymfinityUxBlocksCoreBundle;
use Symfinity\UxBlocksForm\SymfinityUxBlocksFormBundle;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Form\FormRenderer;
use Symfony\UX\StimulusBundle\StimulusBundle;
use Symfony\UX\TwigComponent\TwigComponentBundle;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

final class FormUiBridgeTestKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct(
        string $environment,
        bool $debug,
        private readonly bool $themeEnabled = true,
        private readonly string $themeWrapper = 'field',
    ) {
        parent::__construct($environment, $debug);
    }

    public function getProjectDir(): string
    {
        return \dirname(__DIR__, 2);
    }

    public function getCacheDir(): string
    {
        $suffix = $this->themeEnabled ? 'on' : 'off';

        return $this->getProjectDir() . '/var/cache/test_bridge_' . $suffix . '_' . $this->themeWrapper;
    }

    public function registerBundles(): array
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new StimulusBundle(),
            new TwigComponentBundle(),
            new UiKernelBundle(),
            new SymfinityUxBlocksCoreBundle(),
            new SymfinityUxBlocksFormBundle(),
            new FormUiExtensionsBundle(),
        ];
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->extension('symfinity_form_ui', [
            'theme' => [
                'enabled' => $this->themeEnabled,
                'wrapper' => $this->themeWrapper,
                'live_date' => false,
                'live_tags' => false,
            ],
        ]);

        $container->extension('symfinity_ux_blocks_core', [
            'fragment_ids' => false,
        ]);

        $container->extension('symfinity_ux_blocks_form', [
            'fragment_ids' => false,
        ]);

        $container->extension('symfinity_ui_kernel', [
            'schema_version' => '1.0',
            'default_theme' => 'default',
            'default_variant' => 'default',
        ]);

        $container->extension('framework', [
            'secret' => 'test-secret',
            'test' => true,
            'router' => ['utf8' => true],
            'php_errors' => ['log' => false],
            'form' => ['enabled' => true],
            'validation' => ['enabled' => true],
            'csrf_protection' => true,
            'session' => [
                'storage_factory_id' => 'session.storage.factory.mock_file',
            ],
        ]);

        $container->extension('twig_component', [
            'anonymous_template_directory' => 'components',
            'defaults' => [
                'Symfinity\\UxBlocksCore\\Twig\\Components\\' => 'components',
                'Symfinity\\UxBlocksForm\\Twig\\Components\\' => 'components',
            ],
        ]);

        if (!$this->themeEnabled) {
            $container->extension('twig', [
                'form_themes' => ['form_div_layout.html.twig'],
                'paths' => [
                    '%kernel.project_dir%/tests/templates' => 'FormUiExtensionsTests',
                    '%kernel.project_dir%/templates' => 'SymfinityFormUi',
                ],
            ]);
        } else {
            $container->extension('twig', [
                'paths' => [
                    '%kernel.project_dir%/tests/templates' => 'FormUiExtensionsTests',
                    '%kernel.project_dir%/templates' => 'SymfinityFormUi',
                ],
            ]);
        }

        $formThemes = $this->themeEnabled
            ? ['@SymfinityFormUi/form/theme.html.twig']
            : ['form_div_layout.html.twig'];

        $container->services()
            ->set('twig.extension.form', FormExtension::class)
                ->args([service('translator')->nullOnInvalid()])
                ->tag('twig.extension')
            ->set('twig.form.engine', TwigRendererEngine::class)
                ->args([$formThemes, service('twig')])
            ->set('twig.form.renderer', FormRenderer::class)
                ->args([service('twig.form.engine'), service('security.csrf.token_manager')->nullOnInvalid()])
                ->tag('twig.runtime');
    }
}
