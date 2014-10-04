<?php

namespace MyBuilder\Bundle\CronosBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class MyBuilderCronosExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $container->setParameter('mybuilder.cronos_bundle.commands.exclude', $config['commands']['exclude']);
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $baseLog = $container->getParameter('kernel.logs_dir') . '/'
            . $container->getParameter('kernel.environment')
            . '_cron.log';
        array_walk(
            $config['commands']['include'],
            function (&$val) use ($baseLog) {
                if (!$val['noLogs'] && empty($val['logFile'])) {
                    $val['logFile'] = $baseLog;
                }
            }
        );

        $container->setParameter('mybuilder.cronos_bundle.exporter_config', $config['exporter']);
        $container->setParameter('mybuilder.cronos_bundle.commands.include', $config['commands']['include']);

    }
}
