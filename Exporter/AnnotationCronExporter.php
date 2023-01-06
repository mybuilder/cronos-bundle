<?php

namespace MyBuilder\Bundle\CronosBundle\Exporter;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Reader;
use MyBuilder\Bundle\CronosBundle\Annotation\Cron as CronAnnotation;
use MyBuilder\Cronos\Formatter\Cron as CronFormatter;
use Symfony\Component\Console\Command\Command;

class AnnotationCronExporter
{
    public const ALL_SERVERS = 'all';

    /** @var array */
    private array $config = [];

    public function __construct(private Reader $annotationsReader)
    {}

    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    /**
     * Export the cron for the given commands and server
     * @param Command[] $commands
     */
    public function export(array $commands, array $options): CronFormatter
    {
        $cron = $this->createCronConfiguration();

        foreach ($commands as $command) {
            if ($command instanceof Command) {
                $cron = $this->parseAnnotations($cron, $command, $options);
            }
        }

        return $cron;
    }

    private function createCronConfiguration(): CronFormatter
    {
        $cron = new CronFormatter;
        $configurator = new ArrayHeaderConfigurator($cron->header());
        $configurator->configureFrom($this->config);

        return $cron;
    }

    private function parseAnnotations(CronFormatter $cron, Command $command, array $options): CronFormatter
    {
        foreach ($this->getAnnotations($command) as $annotation) {
            if ($this->annotationBelongsToServer($annotation, $options['serverName'])) {
                $cron = $this->addLine($command, $annotation, $options, $cron);
            }
        }

        return $cron;
    }

    private function annotationBelongsToServer(Annotation $annotation, string $serverName): bool
    {
        return
            $annotation instanceof CronAnnotation
            && ($serverName === self::ALL_SERVERS || $annotation->server === $serverName);
    }

    private function addLine(Command $command, CronAnnotation $annotation, array $options, CronFormatter $cron): CronFormatter
    {
        if ($annotation->comment !== null) {
            $cron->comment($annotation->comment);
        }

        if ($command->getDescription()) {
            $cron->comment($command->getDescription());
        }

        $line = $cron->job($this->buildCommand($command->getName(), $annotation, $options));

        $configurator = new AnnotationLineConfigurator($line);
        $configurator->configureFrom($annotation);

        return $cron;
    }

    private function getAnnotations(Command $command): array
    {
        $reflectedClass = new \ReflectionClass($command);

        return $this->annotationsReader->getClassAnnotations($reflectedClass);
    }

    /**
     * Build the Command to execute with parameters and environment.
     */
    private function buildCommand(string $commandName, CronAnnotation $annotation, array $options): string
    {
        $executor = '';

        if ($annotation->executor) {
            $executor = $annotation->executor;
        } elseif ($this->config['executor']) {
            $executor = $this->config['executor'];
        }

        $console = isset($this->config['console']) ? ' ' . $this->config['console'] : '';
        $environment = isset($options['environment']) ? ' --env=' . $options['environment'] : '';
        $params = $annotation->params ? ' ' . $annotation->params : '';

        return $executor . $console . ' ' . $commandName . $params . $environment;
    }
}
