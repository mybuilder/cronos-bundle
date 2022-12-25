<?php

namespace MyBuilder\Bundle\CronosBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Dump the cron file that would be produced from the cron annotations in this project.
 */
class DumpCommand extends CommandBase
{
    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this
            ->setName('cronos:dump')
            ->setDescription('Dump cron configuration');

        $this->addServerOption();
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cron = $this->configureCronExport($input, $output);

        $output->writeln('<info>We would have put the following in cron</info>');
        $output->write($content = $cron->format());

        return 0;
    }
}
