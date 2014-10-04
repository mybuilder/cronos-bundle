<?php

namespace MyBuilder\Bundle\CronosBundle\Tests\DependencyInjection;

use MyBuilder\Bundle\CronosBundle\DependencyInjection\MyBuilderCronosExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

/**
* MyBuilderCronosExtension
*/
class MyBuilderCronosExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MyBuilderCronosExtension
     */
    private $loader;

    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * Setup the test.
     */
    public function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.logs_dir','logs_dir');
        $this->container->setParameter('kernel.environment','test');
        $this->loader = new MyBuilderCronosExtension();
    }

    /**
     * @param array  $expected
     * @param string $file yaml config file to load
     *
     * @dataProvider providerTestConfig
     */
    public function testConfig(array $expected, $file)
    {
        $this->loader->load($this->getConfig($file), $this->container);

        $this->assertEquals($expected, $this->container->getParameter('mybuilder.cronos_bundle.exporter_config'));
    }

    public function providerTestConfig()
    {
        return array(
            array(
                array(
                    'key' => 'my_builder_cronos',
                    'path' => '/usr/local/bin:/usr/bin:/bin',
                    'executor' => 'php',
                    'console' => 'app/console',
                ),
                'empty.yml'
            ),
            array(
                array(
                    'key' => 'test',
                    'mailto' => 'config-test@example.com',
                    'path' => '/bin:/usr/local/bin',
                    'executor' => 'php',
                    'console' => 'app/console',
                    'shell' => '/bin/bash'
                ),
                'full.yml'
            ),
        );
    }

    /**
     * Load the specified yaml config file.
     *
     * @param string $fileName
     *
     * @return array
     */
    private function getConfig($fileName)
    {
        $locator = new FileLocator(__DIR__ . '/config');
        $file = $locator->locate($fileName, null, true);

        $config = Yaml::parse($file);
        if (null === $config) {
            return array();
        }

        return $config;
    }
}
