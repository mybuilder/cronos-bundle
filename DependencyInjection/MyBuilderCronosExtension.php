<?php

namespace MyBuilder\Bundle\CronosBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class MyBuilderCronosExtension extends Extension
{
    /** @noinspection PhpUnhandledExceptionInspection */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $exporterConfig = $config['exporter'] ?? [];
        $container->setParameter('mybuilder.cronos_bundle.exporter_config', $exporterConfig);
    }
}
