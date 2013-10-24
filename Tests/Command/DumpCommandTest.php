<?php

namespace MyBuilder\Bundle\CronosBundle\Tests\Command;

use MyBuilder\Bundle\CronosBundle\Command\DumpCommand;
use MyBuilder\Bundle\CronosBundle\Command\ReplaceCommand;
use MyBuilder\Bundle\CronosBundle\Tests\CronosTestCase;
use MyBuilder\Bundle\CronosBundle\Tests\Fixtures\Command\TestCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * DumpCommandTest
 */
class DumpCommandTest extends CronosTestCase
{
    /**
     * @var Application
     */
    private $application;
    /**
     * @var Command
     */
    private $command;

    protected function setUp()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $this->application = new Application($kernel);

        $this->application->add(new DumpCommand());
        $this->application->add(new ReplaceCommand());
        $this->application->add(new TestCommand());

        $this->command = $this->application->find('cronos:dump');
    }

    /**
     * Test the DumpCommand
     *
     * @param string $expectedOutput
     * @param array  $input   An array of arguments and options
     *
     * @dataProvider providerDumpOfTestCommand
     */
    public function testDumpOfTestCommand($expectedOutput, array $input)
    {
        $input = array_merge(array('command' => $this->command->getName()), $input);

        $commandTester = new CommandTester($this->command);
        $commandTester->execute($input);

        $this->assertEquals($expectedOutput, trim($commandTester->getDisplay()));
    }

    public function providerDumpOfTestCommand()
    {
        return array(
            array(
'Server all
Found 3 lines
We would have put the following in cron
PATH=/bin:~/bin
MAILTO=test@example.com
27   0    *    *    6    php app/console cronos:test-command --env=test
*/5  */3  *    *    *    php app/console cronos:test-command --env=test
41   10   1    *    *    php -d mbstring.func_overload=0 app/console cronos:test-command --env=test',
                array(
                    '--env' => 'test'
                )
            ),
            array(
'Server web
Found 1 lines
We would have put the following in cron
PATH=/bin:~/bin
MAILTO=test@example.com
27   0    *    *    6    php app/console cronos:test-command --env=prod',
                array(
                    '--env' => 'prod',
                    '--server' => 'web'
                )
            )
        );
    }
}
