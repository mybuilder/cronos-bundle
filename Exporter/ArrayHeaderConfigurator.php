<?php

namespace MyBuilder\Bundle\CronosBundle\Exporter;

class ArrayHeaderConfigurator
{
    private $header;

    /**
     * @param \MyBuilder\Cronos\Formatter\Header $header
     */
    public function __construct($header)
    {
        $this->header = $header;
    }

    public function configureFrom(array $config)
    {
        $this->setupMailto($config);
        $this->setupPath($config);
        $this->setupShell($config);
        return $this->header;
    }

    private function setupMailto($config)
    {
        if (isset($config['mailto'])) {
            $this->header->setMailTo($config['mailto']);
        }
    }

    private function setupPath($config)
    {
        if (isset($config['path'])) {
            $this->header->setPath($config['path']);
        }
    }

    private function setupShell($config)
    {
        if (isset($config['shell'])) {
            $this->header->setShell($config['shell']);
        }
    }
}