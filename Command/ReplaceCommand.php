<?php

namespace MyBuilder\Bundle\CronosBundle\Command;

use MyBuilder\Cronos\Updater\CronUpdatingError;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReplaceCommand extends CommandBase
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('cronos:replace')
            ->setDescription('Replace the current content of your crontab with the cron annotations within this project');

        $this->configureSharedOptions();
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cron = $this->configureCronExport($input, $output);

        $updater = $this->getContainer()->get('mybuilder.cronos_bundle.cron_process_updater');
        try {
            $updater->updateWith($cron);
            $output->writeln('<info>Cron successfully updated</info>');
        } catch (CronUpdatingError $e) {
            $output->writeln(sprintf('<Comment>Cron cannot be updated - %s<comment>', $e->getMessage()));
        }
    }
}
