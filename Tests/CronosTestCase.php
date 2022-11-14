<?php

namespace MyBuilder\Bundle\CronosBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CronosTestCase extends WebTestCase
{
    protected static function getKernelClass(): string
    {
        require_once __DIR__ . '/Fixtures/app/AppKernel.php';

        return 'MyBuilder\Bundle\CronosBundle\Tests\Fixtures\app\AppKernel';
    }
}
