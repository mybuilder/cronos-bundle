<?php

namespace MyBuilder\Bundle\CronosBundle\Exporter;

use MyBuilder\Bundle\CronosBundle\Annotation\Cron as CronAnnotation;
use MyBuilder\Cronos\Formatter\Cron as CronFormatter;
use Symfony\Component\Console\Command\Command;

class AnnotationCronExporter
{
    const ALL_SERVERS = 'all';

    /** @var \Doctrine\Common\Annotations\Reader */
    private $annotationsReader;

    /** @var array */
    private $config = array();

    /** @var array */
    private $includeCommands = array();

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
     * @param array $includeCommands
     */
    public function setIncludeCommands($includeCommands)
    {
        $this->includeCommands = $includeCommands;
    }

    /**
     * Export the cron for the given commands and server
     *
     * @param array $commands
     * @param array $options
     *
     * @return CronFormatter
     */
    public function export(array $commands, array $options)
    {
        $cron = $this->createCronConfiguration();
        foreach ($commands as $name => $command) {
            if ($command instanceof Command) {
                $cron = $this->parseAnnotations($cron, $command, $options);
            }
        }

        foreach ($this->includeCommands as $include) {
            $command = isset($commands[$include['command']]) ? $commands[$include['command']] : null;
            if ($command instanceof Command) {
                unset($include['command']);
                $annotation = new CronAnnotation($include);
                $cron = $this->addLine($command, $annotation, $options, $cron);
            }
        }

        return $cron;
    }

    /**
     * Create and configure Cron
     *
     * @return CronFormatter
     */
    private function createCronConfiguration()
    {
        $cron = new CronFormatter;
        $configurator = new ArrayHeaderConfigurator($cron->header());
        $configurator->configureFrom($this->config);
        return $cron;
    }

    private function parseAnnotations($cron, Command $command, array $options)
    {
        foreach ($this->getAnnotations($command) as $annotation) {
            $cron = $this->addLine($command, $annotation, $options, $cron);
        }

        return $cron;
    }

    private function annotationBelongsToServer($annotation, $serverName)
    {
        return
            $annotation instanceof CronAnnotation &&
            ($serverName === self::ALL_SERVERS || $annotation->server === $serverName);
    }

    private function addLine(Command $command, $annotation, array $options, CronFormatter $cron)
    {
        if ($this->annotationBelongsToServer($annotation, $options['serverName'])) {
            if ($annotation->comment !== null) {
                $cron->comment($annotation->comment);
            }
            if ($command->getDescription()) {
                $cron->comment($command->getDescription());
            }
            $line = $cron->job($this->buildCommand($command->getName(), $annotation, $options));
            $configurator = new AnnotationLineConfigurator($line);
            $configurator->configureFrom($annotation);
        }

        return $cron;
    }

    private function getAnnotations(Command $command)
    {
        $reflectedClass = new \ReflectionClass($command);

        return $this->annotationsReader->getClassAnnotations($reflectedClass);
    }

    /**
     * build the Command to execute with parameters and environment.
     *
     * @param string $commandName Name of command to execute
     * @param $annotation
     * @param array $options
     *
     * @return string
     */
    private function buildCommand($commandName, $annotation, array $options)
    {
        if ($annotation->executor) {
            $executor = $annotation->executor;
        } else if ($this->config['executor']) {
            $executor = $this->config['executor'];
        } else {
            $executor = '';
        }

        $baseDir = empty($options['base_dir']) ? '' : 'cd ' . $options['base_dir'] . '; ';
        $console = isset($this->config['console']) ? ' ' . $this->config['console'] : '';
        $environment = isset($options['environment']) ? ' --env=' . $options['environment'] : '';
        $params = $annotation->params ? ' ' . $annotation->params : '';

        return $baseDir . $executor . $console . ' ' . $commandName . $params . $environment;
    }
}