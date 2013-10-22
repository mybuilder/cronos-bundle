<?php

namespace MyBuilder\Bundle\CronosBundle\Service;

use MyBuilder\Bundle\CronosBundle\Annotation\Cron as CronAnnotation;
use MyBuilder\Cronos\Formatter\Cron as CronFormatter;
use Symfony\Component\Console\Command\Command;

class AnnotationCronExporter
{
    const ALL_SERVERS = 'all';

    private $annotationsReader;
    private $config = array();

    public function __construct($annotationsReader)
    {
        $this->annotationsReader = $annotationsReader;
    }

    /**
     * Set the config
     *
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * Export the cron for the given commands and server
     *
     * @param array  $commands
     * @param string $serverName
     *
     * @return CronFormatter
     */
    public function export(array $commands, $serverName)
    {
        $cron = $this->createCronConfiguration();
        foreach ($commands as $command) {
            if ($command instanceof Command) {
                $cron = $this->parseAnnotations($cron, $command, $serverName);
            }
        }

        return $cron;
    }

    private function createCronConfiguration()
    {
        $cron = new CronFormatter;
        $header = $cron->beginHeader();
        $header = $this->setupMailto($header);
        $this->setupPath($header);
        $this->setupShell($header);

        return $cron;
    }

    private function setupMailto($header)
    {
        if (isset($this->config['mailto'])) {
            $header->setMailTo($this->config['mailto']);
        }

        return $header;
    }

    private function setupPath($header)
    {
        if (isset($this->config['path'])) {
            $header->setPath($this->config['path']);
        }

        return $header;
    }

    private function setupShell($header)
    {
        if (isset($this->config['shell'])) {
            $header->setShell($this->config['shell']);
        }

        return $header;
    }

    private function parseAnnotations($cron, $command, $serverName)
    {
        foreach ($this->getAnnotations($command) as $annotation) {
            if ($this->annotationBelongsToServer($annotation, $serverName)) {
                $cron = $this->addLine($command, $annotation, $cron);
            }
        }

        return $cron;
    }

    private function annotationBelongsToServer($annotation, $serverName)
    {
        return
            $annotation instanceof CronAnnotation &&
            ($serverName === self::ALL_SERVERS || $annotation->server === $serverName);
    }

    private function addLine($command, $annotation, $cron)
    {
        $line = $cron->newLine($this->buildCommand($command->getName(), $annotation));
        $line->addComment($command->getDescription());
        $this->configureLineFromAnnotation($line, $annotation);

        return $cron;
    }

    private function getAnnotations($command)
    {
        $reflectedClass = new \ReflectionClass($command);

        return $this->annotationsReader->getClassAnnotations($reflectedClass);
    }

    private function configureLineFromAnnotation($line, $annotation)
    {
        if ($annotation->minute) {
            $line->setMinute($annotation->minute);
        }
        if ($annotation->hour) {
            $line->setHour($annotation->hour);
        }
        if ($annotation->dayOfMonth) {
            $line->setDayOfMonth($annotation->dayOfMonth);
        }
        if ($annotation->month) {
            $line->setMonth($annotation->month);
        }
        if ($annotation->dayOfWeek) {
            $line->setDayOfWeek($annotation->dayOfWeek);
        }
        if ($annotation->comment) {
            $line->addComment($annotation->comment);
        }
        if ($annotation->logFile) {
            $line->setStandardOutFile($annotation->logFile);
        }
        if ($annotation->errorFile) {
            $line->setStandardErrorFile($annotation->errorFile);
        }
        if ($annotation->noLogs) {
            $line->suppressOutput();
        }

        return $line;
    }

    private function buildCommand($command, $annotation)
    {
        if ($annotation->executor) {
            $command = $annotation->executor . ' ' . $command;
        } else if (isset($this->config['executor'])) {
            $command = $this->config['executor'] . ' ' . $command;
        }
        if ($annotation->params) {
            $command .= ' ' . $annotation->params;
        }
        return $command;
    }
}
