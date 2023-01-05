<?php

error_reporting(error_reporting() & ~E_USER_DEPRECATED);

use Doctrine\Common\Annotations\AnnotationRegistry;

// Composer
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    $loader = require __DIR__ . '/../vendor/autoload.php';

    if (method_exists(AnnotationRegistry::class, 'registerLoader')) {
        AnnotationRegistry::registerLoader('class_exists');
    }

    return $loader;
}

throw new \RuntimeException('Could not find vendor/autoload.php, make sure you ran composer.');
