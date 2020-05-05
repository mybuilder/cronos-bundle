<?php

namespace MyBuilder\Bundle\CronosBundle\Exporter;

use MyBuilder\Cronos\Formatter\Header;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class ArrayHeaderConfigurator
{
    /** @var string[] */
    private $configFields = ['mailto', 'path', 'shell', 'encoding', 'contentType', 'timezone'];

    /** @var Header */
    private $header;

    public function __construct(Header $header)
    {
        $this->header = $header;
    }

    public function configureFrom(array $config): Header
    {
        $propertyAccessor = new PropertyAccessor();

        foreach ($this->configFields as $configField) {
            if (isset($config[$configField])) {
                $propertyAccessor->setValue($this->header, $configField, $config[$configField]);
            }
        }

        return $this->header;
    }
}
