<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\DependencyInjection;

use phpDocumentor\Guides\DependencyInjection\Compiler\NodeRendererPass;
use phpDocumentor\Guides\DependencyInjection\Compiler\ParserRulesPass;
use phpDocumentor\Guides\Twig\Theme\ThemeConfig;
use phpDocumentor\Guides\Twig\Theme\ThemeManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

use function assert;
use function dirname;
use function is_array;

class GuidesExtension extends Extension implements CompilerPassInterface, ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('guides');
        $rootNode = $treeBuilder->getRootNode();
        assert($rootNode instanceof ArrayNodeDefinition);

        $rootNode
            ->children()
                ->arrayNode('base_template_paths')
                    ->defaultValue([])
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('themes')
                    ->defaultValue([])
                    ->arrayPrototype()
                        ->beforeNormalization()
                            ->ifTrue(static fn ($v) => !is_array($v) || !isset($v['templates']))
                            ->then(static fn (string $v) => ['templates' => (array) $v])
                        ->end()
                        ->children()
                            ->scalarNode('extends')->end()
                            ->arrayNode('templates')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->scalarPrototype()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

    /** @param mixed[] $configs */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new PhpFileLoader(
            $container,
            new FileLocator(dirname(__DIR__, 2) . '/resources/config'),
        );

        $loader->load('command_bus.php');
        $loader->load('guides.php');

        $config['base_template_paths'][] = dirname(__DIR__, 2) . '/resources/template/html';
        $container->setParameter('phpdoc.guides.base_template_paths', $config['base_template_paths']);

        foreach ($config['themes'] as $themeName => $themeConfig) {
            $container->getDefinition(ThemeManager::class)
                ->addMethodCall('registerTheme', [new ThemeConfig($themeName, $themeConfig['templates'], $themeConfig['extends'] ?? null)]);
        }
    }

    public function process(ContainerBuilder $container): void
    {
        (new NodeRendererPass())->process($container);
        (new ParserRulesPass())->process($container);
    }

    /** @param mixed[] $config */
    public function getConfiguration(array $config, ContainerBuilder $container): ConfigurationInterface
    {
        return $this;
    }
}
