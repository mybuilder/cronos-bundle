<?php

namespace MyBuilder\Bundle\CronosBundle\Tests\Command;

use MyBuilder\Bundle\CronosBundle\Command\DumpCommand;
use MyBuilder\Bundle\CronosBundle\Command\ReplaceCommand;
use MyBuilder\Bundle\CronosBundle\Tests\CronosTestCase;
use MyBuilder\Bundle\CronosBundle\Tests\Fixtures\Command\TestCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class DumpCommandTest extends CronosTestCase
{
    private Command $command;

    protected function setUp(): void
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $application = new Application($kernel);

        $application->add(new DumpCommand());
        $application->add(new ReplaceCommand());
        $application->add(new TestCommand());

        $this->command = $application->find('cronos:dump');
    }

    protected function tearDown(): void
    {
        self::ensureKernelShutdown();
    }

    /**
     * @dataProvider environmentDumps
     */
    public function test_dump_should_be_as_expected(string $expectedOutput, array $input): void
    {
        $input = array_merge(['command' => $this->command->getName()], $input);

        $commandTester = new CommandTester($this->command);
        $commandTester->execute($input);

        static::assertEquals($expectedOutput, trim($commandTester->getDisplay()));
    }

    public function environmentDumps(): array
    {
        return [
            [
                'Server all
Found 3 lines
We would have put the following in cron
PATH=/bin:~/bin
MAILTO=test@example.com

27   0    *    *    6    php app/console cronos:test-command --env=test

*/5  */3  *    *    *    php app/console cronos:test-command --env=test

41   10   1    *    *    php -d mbstring.func_overload=0 app/console cronos:test-command --env=test',
                [
                    '--env' => 'test',
                ],
            ],
            [
                'Server web
Found 1 lines
We would have put the following in cron
PATH=/bin:~/bin
MAILTO=test@example.com

27   0    *    *    6    php app/console cronos:test-command --env=prod',
                [
                    '--env' => 'prod',
                    '--server' => 'web',
                ],
            ],
        ];
    }
}
