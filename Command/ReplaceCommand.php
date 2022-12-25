<?php

namespace MyBuilder\Bundle\CronosBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReplaceCommand extends CommandBase
{
    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this
            ->setName('cronos:replace')
            ->setDescription('Replace the current content of your crontab with the cron annotations within this project');

        $this->addServerOption();
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cron = $this->configureCronExport($input, $output);
        $key = $this->getExportKey();

        try {
            $this->getContainer()->get('mybuilder.cronos_bundle.cron_process_updater')->updateWith($cron, $key);
            $output->writeln(sprintf('<info>Cron successfully updated with key </info><comment>%s</comment>', $key));
        } catch (\RuntimeException $e) {
            $output->writeln(sprintf('<comment>Cron cannot be updated - %s</comment>', $e->getMessage()));
        }

        return 0;
    }

    private function getExportKey()
    {
        $config = $this->getContainer()->getParameter('mybuilder.cronos_bundle.exporter_config');

        return $config['key'];
    }
}
