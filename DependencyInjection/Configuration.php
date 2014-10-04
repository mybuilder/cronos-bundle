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
                        ->scalarNode('key')->defaultValue('my_builder_cronos')->example('generated')->end()
                        ->scalarNode('mailto')->example('cron@example.com')->end()
                        ->scalarNode('path')->defaultValue('/usr/local/bin:/usr/bin:/bin')->example('/usr/local/bin:/usr/bin:/bin')->end()
                        ->scalarNode('executor')->defaultValue('php')->example('php')->end()
                        ->scalarNode('console')->defaultValue('app/console')->example('app/console or bin/console(symfony 3.0)')->end()
                        ->scalarNode('shell')->example('/bin/sh')->end()
                    ->end()
                ->end()
                ->arrayNode('commands')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('exclude')
                            ->example('["swiftmailer:spool:send"]')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('include')
                        ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('command')->isRequired()->example('swiftmailer:spool:send')->end()
                                    ->scalarNode('minute')->defaultValue('*')->example('/5 - Every 5 minutes')->end()
                                    ->scalarNode('hour')->defaultValue('*')->example('8 - 5 minutes past 8am every day')->end()
                                    ->scalarNode('dayOfWeek')->defaultValue('*')->example('0 - 5 minutes past 8am every Sunday')->end()
                                    ->scalarNode('dayOfMonth')->defaultValue('*')->example('1 -  5 minutes past 8am on first of each month')->end()
                                    ->scalarNode('month')->defaultValue('*')->example('1 - 5 minutes past 8am on first of of January')->end()
                                    ->scalarNode('comment')->example('Any comment')->end()
                                    ->scalarNode('logFile')->example('%kernel.logs_dir%/%kernel.environment%_cron.log')->end()
                                    ->scalarNode('errorFile')->example('%kernel.logs_dir%/%kernel.environment%_error.log')->end()
                                    ->booleanNode('noLogs')->defaultTrue()->end()
                                    ->scalarNode('server')->defaultValue('all')->example('web')->end()
                                    ->scalarNode('params')->example('--color=red')->end()
                                    ->scalarNode('executor')->info('add if use custom executor')->example('/usr/bin/php')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
