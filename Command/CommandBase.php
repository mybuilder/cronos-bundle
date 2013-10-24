<?php

namespace MyBuilder\Bundle\CronosBundle\Command;

use MyBuilder\Bundle\CronosBundle\Exporter\AnnotationCronExporter;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CommandBase extends ContainerAwareCommand
{
    /**
     * Configure the shared command options
     */
    protected function configureSharedOptions()
    {
        $this
            ->addOption('server', null, InputOption::VALUE_REQUIRED, 'Only include cron jobs for the specified server', AnnotationCronExporter::ALL_SERVERS);
    }

    /**
     * Configure cron export
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return mixed
     */
    protected function configureCronExport(InputInterface $input, OutputInterface $output)
    {
        $options = array(
            'serverName' => $input->getOption('server'),
            'environment' => $input->getOption('env')
        );

        $output->writeln(sprintf('Server <comment>%s</comment>', $options['serverName']));
        $cron = $this->exportCron($options);
        $output->writeln(sprintf('<Comment>Found %d lines<comment>', $cron->countLines()));

        return $cron;
    }

    private function exportCron($options)
    {
        $commands = $this->getApplication()->all();
        $exporter = $this->getContainer()->get('mybuilder.cronos_bundle.annotation_cron_exporter');

        return $exporter->export($commands, $options);
    }
}
