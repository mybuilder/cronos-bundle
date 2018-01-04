<?php

namespace MyBuilder\Bundle\CronosBundle\Exporter;

use Symfony\Component\PropertyAccess\PropertyAccessor;

class ArrayHeaderConfigurator
{
    private $configFields = array('mailto', 'path', 'shell', 'encoding', 'contentType', 'timezone');

    private $header;

    public function __construct($header)
    {
        $this->header = $header;
    }

    public function configureFrom(array $config)
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
