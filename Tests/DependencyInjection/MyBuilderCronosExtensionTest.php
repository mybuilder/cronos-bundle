<?php

namespace MyBuilder\Bundle\CronosBundle\Tests\DependencyInjection;

use MyBuilder\Bundle\CronosBundle\DependencyInjection\MyBuilderCronosExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Yaml\Yaml;

class MyBuilderCronosExtensionTest extends TestCase
{
    private MyBuilderCronosExtension $loader;
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $this->container = new ContainerBuilder();
        $this->loader = new MyBuilderCronosExtension();
    }

    /**
     * @dataProvider providerTestConfig
     */
    public function test_config(array $expected, string $file): void
    {
        $this->loader->load($this->getConfig($file), $this->container);

        static::assertEquals($expected, $this->container->getParameter('mybuilder.cronos_bundle.exporter_config'));
    }

    public function providerTestConfig(): array
    {
        if (method_exists(Kernel::class, 'getProjectDir')) {
            $pathToConsole = '%kernel.project_dir%/bin/console';
        } else {
            $pathToConsole = '%kernel.root_dir%/../bin/console';
        }

        return [
            [
                [
                    'executor' => 'php',
                    'console' => $pathToConsole,
                ],
                'empty.yml',
            ],
            [
                [
                    'key' => 'test',
                    'mailto' => 'config-test@example.com',
                    'path' => '/bin:/usr/local/bin',
                    'executor' => 'php',
                    'console' => 'bin/console',
                    'shell' => '/bin/bash',
                ],
                'full.yml',
            ],
        ];
    }

    /**
     * Load the specified yaml config file.
     */
    private function getConfig(string $fileName): array
    {
        $locator = new FileLocator(__DIR__ . '/config');
        $file = $locator->locate($fileName, null, true);

        $config = Yaml::parse(file_get_contents($file));

        return $config ?? [];
    }
}
