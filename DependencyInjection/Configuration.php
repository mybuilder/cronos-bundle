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
                    ->children()
                        ->scalarNode('mailto')->example('cron@example.com')->end()
                        ->scalarNode('path')->example('/usr/local/bin::/usr/bin:/bin') ->end()
                        ->scalarNode('executor')->example('php') ->end()
                        ->scalarNode('console')->example('app/console') ->end()
                        ->scalarNode('shell')->example('/bin/sh') ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
