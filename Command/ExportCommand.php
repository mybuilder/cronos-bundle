<?php

namespace MyBuilder\Bundle\CronosBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class ExportCommand extends CommandBase
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('cronos:export')
            ->addArgument('file', InputArgument::REQUIRED, 'Configuration file name')
            ->setDescription('Export cron configuration to file');

        $this->addServerOption();
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cron = $this->configureCronExport($input, $output);
        $file = $input->getArgument('file');

        $fs = new FileSystem();
        $fs->dumpFile($file, $cron->format());

        return 0;
    }
}