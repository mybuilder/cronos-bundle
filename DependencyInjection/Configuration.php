<?php

namespace MyBuilder\Bundle\CronosBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('my_builder_cronos');

        $rootNode
            ->children()
                ->arrayNode('exporter')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('key')
                            ->cannotBeEmpty()
                            ->defaultValue('%kernel.name%_%kernel.environment%')
                            ->example('symfony_app')
                        ->end()
                        ->scalarNode('mailto')
                            ->cannotBeEmpty()
                            ->example('cron@example.com')
                        ->end()
                        ->scalarNode('path')
                            ->example('/usr/local/bin:/usr/bin:/bin')
                        ->end()
                        ->scalarNode('executor')
                            ->cannotBeEmpty()
                            ->defaultValue('php')
                            ->example('php')
                        ->end()
                        ->scalarNode('console')
                            ->cannotBeEmpty()
                            ->defaultValue('%kernel.root_dir%/../bin/console')
                            ->example('%kernel.project_dir%/bin/console')
                        ->end()
                        ->scalarNode('shell')
                            ->cannotBeEmpty()
                            ->example('/bin/sh')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
