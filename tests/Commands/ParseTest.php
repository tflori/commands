<?php

namespace tflori\Commands\Test\Commands;

use PHPUnit\Framework\TestCase;
use tflori\Commands\Command;
use tflori\Commands\Commands;
use tflori\Getopt\Option;

class ParseTest extends TestCase
{
    public function testUsesArgvByDefault()
    {
        $commands = new Commands();
        $commands->addOptions([
            new Option('v', 'verbose')
        ]);

        $_SERVER['argv'] = ['/usr/bin/app', '-v', '--verbose'];

        $commands->parse();

        self::assertSame([
            'v' => 2,
            'verbose' => 2
        ], $commands->getOptions());
    }

    public function testUsesTheGivenArguments()
    {
        $commands = new Commands();
        $commands->addOptions([
            new Option('v', 'verbose')
        ]);

        $_SERVER['argv'] = ['/usr/bin/app', '-v', '--verbose'];

        $commands->parse([]);

        self::assertSame([], $commands->getOptions());
    }

    public function testThrowsErrorWhenOptionUnknown()
    {
        $commands = new Commands();

        self::expectException(\UnexpectedValueException::class);
        self::expectExceptionMessage('Option \'b\' is unknown');

        $commands->parse('-b');
    }

    public function testAddsOptionsFromCommand()
    {
        $commands = new Commands([new Option('a', 'optA')]);
        $commands->addCommand(new Command(
            'test',
            'test something',
            'var_dump',
            [new Option('b', 'optB')]
        ));

        $commands->parse('-a test -b');

        self::assertSame(1, $commands->getOption('a'));
        self::assertSame(1, $commands->getOption('b'));
    }

    public function testStoresTheCalledCommand()
    {
        $commands = new Commands();
        $command = new Command('help', 'Show the help for command', 'var_dump');
        $commands->addCommand($command);

        $commands->parse('help');

        self::assertSame($command, $commands->getCommand());
    }
}
