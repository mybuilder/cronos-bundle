<?php

namespace MyBuilder\Bundle\CronosBundle\Tests\Fixtures\app;

use MyBuilder\Bundle\CronosBundle\MyBuilderCronosBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new MyBuilderCronosBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $loader->load(__DIR__ . '/config/' . $this->getEnvironment() . '.yml');
    }
}
