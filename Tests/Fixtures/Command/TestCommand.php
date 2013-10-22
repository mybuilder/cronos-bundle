<?php

namespace MyBuilder\Bundle\CronosBundle\Tests\Fixtures\Command;

use Symfony\Component\Console\Command\Command;
use MyBuilder\Bundle\CronosBundle\Annotation\Cron;

/**
* Empty command for testing that this bundle can read the cron annotations correctly
*
* @Cron(minute="27", hour="01", dayOfWeek="6", server="web")
* @Cron(minute="/5", hour="/3", server="batch")
*/
class TestCommand extends Command
{
     protected function configure()
    {
        $this->setName('cronos:test-command');
    }
}
