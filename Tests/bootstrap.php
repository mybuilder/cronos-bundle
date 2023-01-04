<?php

error_reporting(error_reporting() & ~E_USER_DEPRECATED);

// Composer
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    $loader = require __DIR__ . '/../vendor/autoload.php';

    return $loader;
}

throw new \RuntimeException('Could not find vendor/autoload.php, make sure you ran composer.');
