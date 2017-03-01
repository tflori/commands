<?php

namespace tflori\Commands\Test\Commands;

use PHPUnit\Framework\TestCase;
use tflori\Commands\Commands;
use Ulrichsg\Getopt\Getopt;
use Ulrichsg\Getopt\Option;

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

    public function testSplitsArgumentsString()
    {
        $commands = new Commands();
        $commands->addOptions([
            new Option('v', 'verbose')
        ]);

        $_SERVER['argv'] = ['/usr/bin/app'];
        $commands->parse('-v --verbose');

        self::assertSame([
            'v' => 2,
            'verbose' => 2
        ], $commands->getOptions());
    }

    public function testStringWithSingleQuotes()
    {
        $commands = new Commands();
        $commands->addOptions([
            new Option('a', 'optA', Getopt::REQUIRED_ARGUMENT),
        ]);

        $commands->parse('-a \'the value\'');

        self::assertSame('the value', $commands->getOption('a'));
    }

    public function testStringWithDoubleQuotes()
    {
        $commands = new Commands();
        $commands->addOptions([
            new Option('a', 'optA', Getopt::REQUIRED_ARGUMENT),
        ]);

        $commands->parse('-a "the value"');

        self::assertSame('the value', $commands->getOption('a'));
    }

    public function testSingleQuotesInString()
    {
        $commands = new Commands();
        $commands->addOptions([
            new Option('a', 'optA', Getopt::REQUIRED_ARGUMENT),
        ]);

        $commands->parse('-a "the \'"');

        self::assertSame('the \'', $commands->getOption('a'));
    }

    public function testDoubleQuotesInString()
    {
        $commands = new Commands();
        $commands->addOptions([
            new Option('a', 'optA', Getopt::REQUIRED_ARGUMENT),
        ]);

        $commands->parse('-a \'the "\'');

        self::assertSame('the "', $commands->getOption('a'));
    }

    public function testQuoteConcatenation()
    {
        $commands = new Commands();
        $commands->addOptions([
            new Option('a', 'optA', Getopt::REQUIRED_ARGUMENT),
            new Option('b', 'optB', Getopt::REQUIRED_ARGUMENT),
        ]);

        $commands->parse('-a \'this uses \'"\'"\' inside single quote\' -b "this uses "\'"\'" inside double quote"');

        self::assertSame('this uses \' inside single quote', $commands->getOption('a'));
        self::assertSame('this uses " inside double quote', $commands->getOption('b'));
    }

    public function testLinefeedAsSeparator()
    {
        $commands = new Commands();
        $commands->addOptions([
            new Option('a', 'optA', Getopt::REQUIRED_ARGUMENT),
        ]);

        $commands->parse("-a\nvalue");

        self::assertSame('value', $commands->getOption('a'));
    }

    public function testTabAsSeparator()
    {
        $commands = new Commands();
        $commands->addOptions([
            new Option('a', 'optA', Getopt::REQUIRED_ARGUMENT),
        ]);

        $commands->parse("-a\tvalue");

        self::assertSame('value', $commands->getOption('a'));
    }
}
