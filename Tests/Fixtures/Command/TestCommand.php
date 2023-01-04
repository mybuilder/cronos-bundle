<?php

namespace MyBuilder\Bundle\CronosBundle\Tests\Fixtures\Command;

use Symfony\Component\Console\Command\Command;
use MyBuilder\Bundle\CronosBundle\Annotation\Cron;

/**
 * Empty command for testing that this bundle can read the cron annotations correctly
 *
 * @Cron(minute="27", hour="0", dayOfWeek="6", server="web")
 * @Cron(minute="/5", hour="/3", server="batch")
 * @Cron(minute="41", hour="10", dayOfMonth="1", server="test", executor="php -d mbstring.func_overload=0")
 */
class TestCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('cronos:test-command');
    }
}
